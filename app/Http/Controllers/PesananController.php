<?php

namespace App\Http\Controllers;

use App\Exports\LaporanPenjualanExport;
use App\Exports\LaporanLabaRugiExport;
use App\Models\DetailPesanan;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\User;
use App\Notifications\NewMessageNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class PesananController extends Controller
{
    //pembeli
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function keranjang()
    {
        $keranjang = Pesanan::where('status', 'Keranjang')->where('user_id', Auth::user()->id)->first();
        $detail_keranjang = DetailPesanan::join('produk', 'produk.id_produk', '=', 'pesanandetails.produk_id')
            ->where('pesanan_id', $keranjang->id)
            ->where('produk.status_produk', 'Aktif')
            ->get();
        foreach ($detail_keranjang as $data) {
            $jumlah_pesanan[] = $data->jumlah;
        }
        $total_pesanan = $detail_keranjang->sum('jumlah');
        return view('pembeli.keranjang', [
            'keranjang' => $keranjang,
            'detail_keranjang' => $detail_keranjang,
            'total_pesanan' => $total_pesanan
        ]);
    }

    public function datapesanan($id){
        $pesanan = DetailPesanan::where('id', $id)->first();

        if ($pesanan) {
            return response()->json($pesanan);
        } else {
            return response()->json(['error' => 'Product not found'], 404);
        }
    }

    public function hapuskeranjang($id){
        $detpesanan = DetailPesanan::find($id);
        $pesanan = Pesanan::where('id', $detpesanan->pesanan_id)->first();
        $pesanan->update([
            'total_harga' => $pesanan->total_harga - $detpesanan->jumlah_harga,
            'modal_pesanan' => $pesanan->modal_pesanan - $detpesanan->modal_details
        ]);
        $detpesanan->delete();

        return redirect()->route("home.dashboard_pembeli")->with('success', 'Pesanan telah berhasil dihapus dari keranjang');
    }

    public function tambahkeranjang(Request $request, $id)
    {
        for ($i = 1; $i <= $request->jlhV; $i++) {
            $vari = explode(",", $request['rb_' . $i]);
            $variasi[] = $vari;
        }
        if ($request->jlhV != 0) {
            $varPes = json_encode($variasi);
        }


        $produk = Produk::where('id_produk', $id)->first();
        $tanggal = Carbon::now();
        $date = $tanggal->format('Y-m-d');
        $jumlah_pesanan = Pesanan::where('tanggal', $date)->count();


        if ($request->jumlah_pes > $produk->jumlah_produk) {
            return back()->with('error', 'Jumlah Produk Tidak Mencukupi!');
        }

        $cek_keranjang = Pesanan::where('user_id', Auth::user()->id)->where('status', 'Keranjang')->first();
        if (empty($cek_keranjang)) {
            $keranjang = new Pesanan;
            $keranjang->user_id = Auth::user()->id;
            $keranjang->tanggal = $tanggal;
            $keranjang->total_harga = 0;
            $keranjang->nama_pengambil =  Auth::user()->name;
            $keranjang->save();
        }

        $now = $tanggal->format('Ymd');
        $periksa_keranjang = Pesanan::where('user_id', Auth::user()->id)->where('status', 'Keranjang')->first();
        $periksa_keranjang->update([
            'kode' => "DEL$now$jumlah_pesanan"
        ]);
        $cek_detailpesanan = DetailPesanan::where('produk_id', $produk->id_produk)->where('pesanan_id', $periksa_keranjang->id)->first();
        if ($cek_detailpesanan == null) {
            $pesanan_detail = new DetailPesanan();
            $pesanan_detail->produk_id = $produk->id_produk;
            $pesanan_detail->pesanan_id = $periksa_keranjang->id;
            $pesanan_detail->jumlah = $request->jumlah_pes;
            $pesanan_detail->jumlah_harga = $produk->harga * $request->jumlah_pes;
            // $pesanan_detail->ukurans = $request->ukuran;
            // $pesanan_detail->warn    a_produk = $request->warna;
            // $pesanan_detail->angkatans = $request->angkatan;
            $pesanan_detail->modal_details = $produk->modal * $request->jumlah_pes;
            if ($request->jlhV != 0) {
                $pesanan_detail->variasi_pes = $varPes;
            }
            $pesanan_detail->save();
        }

        if ($cek_detailpesanan != null) {
            $jumlah_pesanan_detail_baru = $request->jumlah_pes;
            $cek_detailpesanan->jumlah = $cek_detailpesanan->jumlah + $jumlah_pesanan_detail_baru;
            $harga_pesanan_detail_baru = $produk->harga * $request->jumlah_pes;
            $cek_detailpesanan->jumlah_harga = $cek_detailpesanan->jumlah_harga + $harga_pesanan_detail_baru;
            $modal_pesanan_detail_baru = $produk->modal * $request->jumlah_pes;
            $cek_detailpesanan->modal_details = $cek_detailpesanan->modal_details + $modal_pesanan_detail_baru;
            $cek_detailpesanan->update();
        }

        $periksa_keranjang->total_harga = $periksa_keranjang->total_harga + ($produk->harga * $request->jumlah_pes);
        $periksa_keranjang->modal_pesanan = $periksa_keranjang->modal_pesanan + ($produk->modal * $request->jumlah_pes);
        $periksa_keranjang->update();
        return redirect()->route("pembeli.keranjang")->with('success', 'Produk telah berhasil dimasukkan ke dalam keranjang');
    }

    public function checkout()
    {
        return view('pembeli.checkout');
    }

    public function prosescheckout(Request $request)
    {
        $selectedItems = $request->input('selected_items');
        if($selectedItems == null){
            return back()->with('error', 'Pilih produk terlebih dahaulu!');
        }else{
            foreach ($selectedItems as $itemId) {
                $item[] = DetailPesanan::join('produk', 'produk.id_produk', '=', 'pesanandetails.produk_id')->where('id', $itemId)->where('produk.status_produk', 'Aktif')->first();
            }
            return view('pembeli.checkout', [
                'item' => $item
            ]);
        }
    }

    public function checkoutproduk(Request $request)
    {
        $tanggal = Carbon::now();
        $date = $tanggal->format('Y-m-d');
        $jumlah_pesanan = Pesanan::where('tanggal', $date)->count();
        $now = $tanggal->format('Ymd');

        $selected_id = $request->id_pesanan;
        $arr_id = explode(',', $selected_id);
        foreach ($arr_id as $sel_id) {
            $pesanan[] = DetailPesanan::where('id', $sel_id)->first();
        }

        $keranjang = DetailPesanan::join('pesanans', 'pesanans.id', '=', 'pesanandetails.pesanan_id')->where('pesanans.status', 'Keranjang')->get();
        $checkout = Pesanan::where('id', $pesanan[0]->pesanan_id)->first();
        if (count($keranjang) == count($pesanan)) {
            $checkout->update([
                'status' => "Menunggu",
                'kode' => "DEL$now$jumlah_pesanan"
            ]);
            return redirect()->route('pembeli.riwayatpesanan')->with('success', 'Produk telah berhasil dipesan. Menunggu konfirmasi pesanan!');
        } else {
            $h = 0;
            $m = 0;
            foreach ($pesanan as $pes) {
                $h += $pes->jumlah_harga;
                $m += $pes->modal_details;
            }
            $sisa_hargaK = $checkout->total_harga - $h;
            $sisa_modalK = $checkout->modal_pesanan - $m;

            $checkout->update([
                'total_harga' => $sisa_hargaK,
                'modal_pesanan' => $sisa_modalK
            ]);

            $kode = "DEL$now$jumlah_pesanan";
            $cek_pesanan = Pesanan::where('kode', $kode)->first();

            if (empty($cek_pesanan)) {
                $pesanan_baru = new Pesanan();
                $pesanan_baru->user_id = Auth::user()->id;
                $pesanan_baru->tanggal = $tanggal;
                $pesanan_baru->total_harga = 0;
                $pesanan_baru->status = "Menunggu";
                $pesanan_baru->save();
            }
            $jumlah_Npesanan = Pesanan::where('tanggal', $date)->count();
            $cek_pesanan->update([
                'kode' => "DEL$now$jumlah_Npesanan",
                'total_harga' => $h,
                'modal_pesanan' => $m,
            ]);

            foreach ($pesanan as $pes) {
                $pes->update([
                    'pesanan_id' => $cek_pesanan->id,
                ]);
            }

            $notif_admin = 'Pesanan baru dengan Kode Pesanan ' . $cek_pesanan->kode;
            $notif_pembeli = 'Pesanan anda dengan Kode Pesanan ' . $cek_pesanan->kode . ' Menunggu Konfirmasi';
            $admin = User::where('role_pengguna', "Admin")->first();
            $pembeli = User::where('id', Auth::user()->id)->first();
            $admin->notify(new NewMessageNotification($notif_admin, $cek_pesanan->kode));
            $pembeli->notify(new NewMessageNotification($notif_pembeli, $cek_pesanan->kode));

            return redirect()->route('pembeli.riwayatpesanan')->with('success', 'Produk telah berhasil dipesan. Menunggu konfirmasi pesanan!');
        }
    }

    public function riwayatpesanan()
    {
        $pesanan = Pesanan::leftjoin('users', 'users.id', '=', 'pesanans.user_id')
            ->select('pesanans.*', 'users.name')
            ->where('user_id', Auth::user()->id)->where('status', '!=', 'Keranjang')->get();

        $pesanan_menunggu = Pesanan::leftjoin('users', 'users.id', '=', 'pesanans.user_id')
            ->select('pesanans.*', 'users.name')
            ->where('user_id', Auth::user()->id)->where('status', 'Menunggu')->get();

        $pesanan_diproses = Pesanan::leftjoin('users', 'users.id', '=', 'pesanans.user_id')
            ->select('pesanans.*', 'users.name')
            ->where('user_id', Auth::user()->id)->where('status', 'Diproses')->get();

        $pesanan_selesai = Pesanan::leftjoin('users', 'users.id', '=', 'pesanans.user_id')
            ->select('pesanans.*', 'users.name')
            ->where('user_id', Auth::user()->id)->where('status', 'Selesai')->get();

        $pesanan_dibatalkan = Pesanan::leftjoin('users', 'users.id', '=', 'pesanans.user_id')
            ->select('pesanans.*', 'users.name')
            ->where('user_id', Auth::user()->id)->where('status', 'Dibatalkan')->get();

        return view('pembeli.riwayatpesanan', [
            'pesanan' => $pesanan,
            'pesanan_dibatalkan' => $pesanan_dibatalkan,
            'pesanan_diproses' => $pesanan_diproses,
            'pesanan_menunggu' => $pesanan_menunggu,
            'pesanan_selesai' => $pesanan_selesai
        ]);
    }

    public function batalkanpesanan(Request $request, $id)
    {
        $pesanan = Pesanan::where('kode', $id)->first();

        $pesanan->update([
            'status' => "Dibatalkan"
        ]);

        return back()->with('success', 'Pesanan berhasil Dibatalkan');
    }

    public function detailpesanan($kode)
    {
        $pesanan = Pesanan::where('kode', $kode)->first();
        $detail_pes = DetailPesanan::join('produk', 'produk.id_produk', '=', 'pesanandetails.produk_id')
            ->where('pesanan_id', $pesanan->id)->select('pesanandetails.*', 'produk.gambar_produk', 'produk.nama_produk')->get();
        return view('pembeli.detailpesanan', [
            'detail_pes' => $detail_pes,
            'pesanan' => $pesanan
        ]);
    }

    public function belisekarang($id, $jlh, $variasi)
    {
        $produk = Produk::where('id_produk', $id)->first();


        if ($variasi != 'empty') {
            $aVariasi = explode(',', $variasi);
            $jlhVar = count($aVariasi);
            $value = [];
            for ($i = 1; $i < $jlhVar; $i = $i + 2) {
                array_push($value, $aVariasi[$i]);
            }
            $var = implode(', ', $value);
        } else {
            $var = "";
            $aVariasi = [];
        }



        return view('pembeli.checkout', [
            'produk' => $produk,
            'jlh' => $jlh,
            'aVariasi' => $aVariasi,
            'var' => $var
        ]);
    }

    public function checkoutsekarangproduk(Request $request)
    {
        $variasi = explode(', ', $request->aVariasi);
        $jlhVar = count($variasi);
        $tanggal = Carbon::now();
        $date = $tanggal->format('Y-m-d');
        $jumlah_pesanan = Pesanan::where('tanggal', $date)->count();
        $now = $tanggal->format('Ymd');
        $varPes = json_encode($variasi);

        $produk = Produk::where('id_produk', $request->idPro)->first();
        $user = User::where('id', Auth::user()->id)->first();

        $pesanan = new Pesanan;
        $pesanan->user_id = Auth::user()->id;
        $pesanan->tanggal = $tanggal;
        $pesanan->total_harga = $produk->harga * $request->jlh_pesanan;
        $pesanan->modal_pesanan =  $produk->modal * $request->jlh_pesanan;
        $pesanan->nama_pengambil = $user->name;
        $pesanan->status = "Menunggu";
        $pesanan->save();

        $pesanan_new = Pesanan::orderBy('id', 'DESC')->first();
        $kode = "DEL$now$jumlah_pesanan";

        $pesanan_new->update([
            'kode' => $kode
        ]);

        $pesanan_detail = new DetailPesanan();
        $pesanan_detail->jumlah = $request->jlh_pesanan;
        $pesanan_detail->jumlah_harga = $produk->harga * $request->jlh_pesanan;
        $pesanan_detail->modal_details = $produk->modal * $request->jlh_pesanan;
        $pesanan_detail->produk_id = $produk->id_produk;
        $pesanan_detail->pesanan_id = $pesanan_new->id;
        $pesanan_detail->variasi_pes = $varPes;
        $pesanan_detail->save();

        $cek_pesanan = Pesanan::where('kode', $kode)->first();

        $notif_admin = 'Pesanan baru dengan Kode Pesanan ' . $cek_pesanan->kode;
        $notif_pembeli = 'Pesanan anda dengan Kode Pesanan ' . $cek_pesanan->kode . ' Menunggu Konfirmasi';
        $admin = User::where('role_pengguna', "Admin")->first();
        $pembeli = User::where('id', Auth::user()->id)->first();
        $admin->notify(new NewMessageNotification($notif_admin, $cek_pesanan->kode));
        $pembeli->notify(new NewMessageNotification($notif_pembeli, $cek_pesanan->kode));

        return redirect()->route('pembeli.riwayatpesanan')->with('success', 'Produk telah berhasil dipesan. Menunggu konfirmasi pesanan!');
    }


    //admin

    public function konfirmasipesanan()
    {
        $pesanan = Pesanan::join('users', 'users.id', '=', 'pesanans.user_id')
            ->select('pesanans.*', 'users.name')
            ->where('pesanans.status', "Menunggu")
            ->orderBy('pesanans.tanggal', "DESC")->get();
        return view('admin.konfirmasipesanan', [
            'pesanan' => $pesanan
        ]);
    }

    public function proseskonfirmasi($id)
    {
        $proseskonfirmasi = Pesanan::where('id', $id)->first();
        $proseskonfirmasi->update([
            'status' => "Diproses"
        ]);

        return back()->with('success', 'Pesanan berhasil Dikonfirmasi. Pesanan Sedang Diproses');
    }

    public function pembatalan(Request $request, $id)
    {
        $pesanan =  Pesanan::where('kode', $id)->first();
        $pesanan->update([
            'alasan' => $request->alasan,
            'status' => "Dibatalkan"
        ]);

        return back()->with('success', 'Pesanan berhasil Dibatalkan');
    }

    public function pesanandiproses()
    {
        $pesanan = Pesanan::join('users', 'users.id', '=', 'pesanans.user_id')
            ->select('pesanans.*', 'users.name')
            ->where('pesanans.status', "Diproses")
            ->orderBy('pesanans.tanggal', "DESC")->get();
        return view('admin.pesanandiproses', [
            'pesanan' => $pesanan
        ]);
    }

    public function prosesselesai($id)
    {
        $proseskonfirmasi = Pesanan::where('kode', $id)->first();
        $proseskonfirmasi->update([
            'status' => "Selesai"
        ]);

        return back()->with('success', 'Pesanan telah Diambil. Pesanan Selesai');
    }

    public function pesananselesai()
    {
        $pesanan = Pesanan::join('users', 'users.id', '=', 'pesanans.user_id')
            ->select('pesanans.*', 'users.name')
            ->where('pesanans.status', "Selesai")
            ->orderBy('pesanans.tanggal', "DESC")->get();
        return view('admin.pesananselesai', [
            'pesanan' => $pesanan
        ]);
    }

    public function pesanandibatalkan()
    {
        $pesanan = Pesanan::join('users', 'users.id', '=', 'pesanans.user_id')
            ->select('pesanans.*', 'users.name')
            ->where('pesanans.status', "Dibatalkan")
            ->orderBy('pesanans.tanggal', "DESC")->get();
        return view('admin.pesanandibatalkan', [
            'pesanan' => $pesanan
        ]);
    }

    public function laporanpenjualan(Request $request)
    {
        $tgl_awal = $request->tanggal_awal;
        $tgl_akhir = $request->tanggal_akhir;

        if ($tgl_awal != null && $tgl_akhir != null) {
            $pesanan = Pesanan::whereBetween('tanggal', [$tgl_awal, $tgl_akhir])->where('status', "Selesai")->get();
        }

        if ($tgl_akhir == null || $tgl_awal == null) {
            $pesanan = 0;
        }
        return view('admin.laporanpenjualan', [
            'pesanan' => $pesanan,
            'tgl_awal' => $tgl_awal,
            'tgl_akhir' => $tgl_akhir
        ]);
    }

    public function exportlaporanpenjualan(Request $request)
    {
        $awal = $request->awal;
        $akhir = $request->akhir;

        $tglMin = Carbon::createFromDate($awal)->format('d M Y');
        $tglMax = Carbon::createFromDate($akhir)->format('d M Y');

        return Excel::download(new LaporanPenjualanExport($awal, $akhir), "Data Laporan Penjualan " . $tglMin . " - " . $tglMax . ".xlsx");
    }

    public function laporanlabarugi(Request $request)
    {
        $tgl_awal = $request->tanggal_awal;
        $tgl_akhir = $request->tanggal_akhir;

        if ($tgl_awal != null && $tgl_akhir != null) {
            $detailpes = Pesanan::join('pesanandetails', 'pesanandetails.pesanan_id', '=', 'pesanans.id')->whereBetween('pesanans.tanggal', [$tgl_awal, $tgl_akhir])->where('pesanans.status', "Selesai")->get();
            foreach ($detailpes as $detail) {
                $idProduk[] = $detail->produk_id;
            }

            $penjualan = Produk::whereIn('id_produk', $idProduk)->get();
        }

        if ($tgl_akhir == null || $tgl_awal == null) {
            $penjualan = 0;
        }

        return view('admin.laporanlabarugi', [
            'penjualan' => $penjualan,
            'tgl_awal' => $tgl_awal,
            'tgl_akhir' => $tgl_akhir
        ]);
    }

    public function exportlaporanlabarugi(Request $request)
    {
        $awal = $request->awal;
        $akhir = $request->akhir;

        $tglMin = Carbon::createFromDate($awal)->format('d M Y');
        $tglMax = Carbon::createFromDate($akhir)->format('d M Y');

        return Excel::download(new LaporanLabaRugiExport($awal, $akhir), "Data Laporan Laba Rugi " . $tglMin . " - " . $tglMax . ".xlsx");
    }
}

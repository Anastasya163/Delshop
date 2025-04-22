<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;
use App\Models\DetailPesanan;
use App\Models\Pesanan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //pembeli


    //admin
    public function kelolapengguna()
    {
        $user = User::all();
        return view('admin.kelolapengguna', [
            'user' => $user
        ]);
    }

    public function tambahpengguna()
    {
        $role = Role::all();
        return view('admin.tambahpengguna', [
            'role' => $role
        ]);
    }

    public function prosestambahpengguna(Request $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role_pengguna = $request->role_pengguna;
        $user->save();

        return redirect()->route('admin.kelolapengguna')->with('success', 'Data pengguna berhasil ditambahkan');
    }

    public function importpengguna()
    {
        $role = Role::all();
        return view('admin.tambahpengguna', [
            'role' => $role
        ]);
    }

    public function prosesimportpengguna(Request $request)
    {
        $file = $request->file('file_excel');
        $fileName = $file->getClientOriginalName();
        $file->move('UsersData', $fileName);
        Excel::import(new UsersImport, \public_path('/UsersData/' . $fileName));
        if (Session::has('error')) {
            return redirect()->route("admin.kelolapengguna")->with('error', Session::get('error'));
        }
        return redirect()->route("admin.kelolapengguna")->with('success', 'Data berhasil diimport!');
    }

    public function profile()
    {
        $pesanan_baru = Pesanan::where('user_id', Auth::user()->id)->where('status', 'keranjang')->first();
        if (empty($pesanan_baru)) {
            $pesanan = 0;
        } else {
            $pesanan = DetailPesanan::select(DB::raw('count(id) as total'))->groupBy("pesanan_id")->where('pesanan_id', $pesanan_baru->id)->get();
        }

        $pengguna_prof = User::where('id', Auth::user()->id)->get();
        $pengguna = User::where('id', Auth::user()->id)->get();
        $header = User::where('role_pengguna', "Admin")->first();
        return view('pembeli.profile', [
            'pesanan' => $pesanan,
            'pengguna' => $pengguna,
            'pesanan_baru' => $pesanan_baru,
            'pengguna_prof' => $pengguna_prof,
            'header' => $header
        ]);
    }

    public function uprofile(Request $request)
    {
        $pengguna = User::where('id', Auth::user()->id)->first();

        $fileName = "";

        $name = $request->name;
        $jenis_kelamin = $request->jenis_kelamin;
        $pekerjaan = $request->pekerjaan;
        $alamat = $request->alamat;
        $no_telp = $request->no_telp;
        $tentang = $request->tentang;
        $email = $request->email;
        if ($request->file('gambar_pengguna')) {
            if ($request->hasfile('gambar_pengguna')) {
                $fileName = round(microtime(true) * 1000) . '-' . str_replace(' ', '-', $request->file('gambar_pengguna')->getClientOriginalName());
                $request->file('gambar_pengguna')->move(public_path('user-images'), $fileName);
            }
        }

        if ($request->password_old != null && $request->password_new != null && $request->kpassword_new != null) {
            $password_lama = $request->password_old;
            $password_baru = $request->password_new;
            $kpassword_baru = $request->kpassword_new;
            $password = $pengguna->password;
            if (Hash::check($password_lama, $password)) {
                if ($password_baru == $kpassword_baru) {
                    dd($request->password_new);
                } else {
                    return back()->with('error', 'Konfirmasi Password Salah!');
                }
            } else {
                return back()->with('error', 'Password Lama Salah!');
            }
        }


        //set data for update profile user
        if($fileName != ""){
            $updatePengguna = [
                'name' => $name,
                'jenis_kelamin' => $jenis_kelamin,
                'pekerjaan' => $pekerjaan,
                'alamat' => $alamat,
                'no_telp' => $no_telp,
                'tentang' => $tentang,
                'email' => $email,
                'gambar_pengguna'=>$fileName
            ];
        }
        else{
            $updatePengguna = [
                'name' => $name,
                'jenis_kelamin' => $jenis_kelamin,
                'pekerjaan' => $pekerjaan,
                'alamat' => $alamat,
                'no_telp' => $no_telp,
                'tentang' => $tentang,
                'email' => $email,
                'gambar_pengguna'=>"profile.png"
            ];

        }

        //Query update for update profile user
        DB::table('users')
        ->where('id', Auth::user()->id)
        ->update($updatePengguna);

        // $pengguna->update();

        if (route('pembeli.profile')) {
            return redirect()->route('pembeli.profile')->with('success', 'Data pengguna berhasil diubah');
        }
    }

    public function markAsRead($id)
    {
        Auth::user()->notifications()->find($id)->markAsRead();
        $notif = Auth::user()->notifications()->find($id);
        $pesanan = $notif->data['pesananId'];
        if (Auth::user()->role_pengguna == "Publik" || Auth::user()->role_pengguna == "Mahasiswa" || Auth::user()->role_pengguna == "Dosen/Staff") {
            return redirect("/riwayat-pesanan");
        } else {
            return redirect("/konfirmasi/pesanan");
        }
    }
}




<?php

namespace App\Http\Controllers;

use App\Models\KategoriProdukModel;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Produk;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function dash_pembeli(){
        $kategori_produk = KategoriProdukModel::where('status_kategori', 'Aktif')->get();

        // Memfilter produk berdasarkan status
        $produk_terbaru = Produk::where('status_produk', 'Aktif') // 
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('home.dashboard_pembeli', [
            'kategori_produk' => $kategori_produk,
            'produk_terbaru' => $produk_terbaru
        ]);
    }

}

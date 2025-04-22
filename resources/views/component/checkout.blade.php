@include('navs')
@php
    $current = Illuminate\Support\Facades\Route::currentRouteName();
@endphp
<div class="site-section">
    @if ($current == 'pembeli.checkout' || $current == "pembeli.prosescheckout")
        <div class="container px-3 px-md-4 px-lg-5">
            <div class="row mb-5">
                <div class="col-lg-8">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="fw-bold text-dark">Pemesan</h5>
                            <p class="mb-1 text-dark">{{ Auth::user()->name }}</p>
                            <p class="mb-1 text-dark">{{ Auth::user()->no_telp }}</p>
                            <p class="mb-0 text-dark">{{ Auth::user()->role_pengguna }}</p>
                        </div>
                    </div>
                    @php $total_pro = 0; $total_pri = 0; @endphp
                    @foreach ($item as $data)
                        <div class="card mb-4">
                            <div class="row g-0 p-3">
                                <div class="col-4 col-md-2 d-flex align-items-center">
                                    <img src="/product-images/{{ $data->gambar_produk }}" class="img-fluid rounded" alt="produk">
                                </div>
                                <div class="col-8 col-md-6">
                                    <h6 class="fw-bold text-dark">{{ $data->nama_produk }}</h6>
                                    @php $varPesanan = json_decode($data->variasi_pes, true); @endphp
                                    <p class="mb-1 text-dark">Variasi:
                                        @if ($data->variasi_pes)
                                            {{ implode(', ', array_column($varPesanan, 1)) }}
                                        @endif
                                    </p>
                                    <p class="mb-0 text-dark">Jumlah Produk: {{ $data->jumlah }}</p>
                                </div>
                                <div class="col-md-4 text-end d-flex flex-column justify-content-center">
                                    <p class="mb-1 text-dark fw-bold">Subtotal</p>
                                    <p class="mb-0 text-dark">Rp. {{ number_format($data->jumlah_harga, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                        @php
                            $total_pro += $data->jumlah;
                            $total_pri += $data->jumlah_harga;
                            $id_pes[] = $data->id;
                        @endphp
                    @endforeach
                </div>
                <div class="col-lg-4">
                    <div class="card mb-3" style="min-height: 250px">
                        <div class="card-body">
                            <h5 class="fw-bold text-dark">Total Pesanan</h5>
                            <div class="d-flex justify-content-between">
                                <span class="text-dark">Total Produk</span>
                                <span>{{ $total_pro }} Produk</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-dark">Total Harga Produk</span>
                                <span>Rp. {{ number_format($total_pri, 0, ',', '.') }}</span>
                            </div>
                            <hr>
                            <form action="/checkout/produk" method="POST">
                                @csrf
                                @php $idPes = implode(',', $id_pes); @endphp
                                <input type="hidden" name="id_pesanan" value="{{ $idPes }}">
                                <div class="text-center">
                                    <button type="submit" class="btn btn-sm btn-primary">Pesan Sekarang</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h6 class="fw-bold text-dark">Informasi Penting!</h6>
                            <p class="mb-1 text-dark">Pengambilan dan pembayaran pesanan dilakukan di tempat.</p>
                            <p class="mb-0 text-dark fw-bold">Selamat belanja!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($current == 'pembeli.belisekarang')
        <div class="container px-3 px-md-4 px-lg-5">
            <div class="row mb-5">
                <div class="col-lg-8">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="fw-bold text-dark">Pemesan</h5>
                            <p class="mb-1 text-dark">{{ Auth::user()->name }}</p>
                            <p class="mb-1 text-dark">{{ Auth::user()->no_telp }}</p>
                            <p class="mb-0 text-dark">{{ Auth::user()->role_pengguna }}</p>
                        </div>
                    </div>
                    <div class="card mb-4">
                        <div class="row g-0 p-3">
                            <div class="col-4 col-md-2 d-flex align-items-center">
                                <img src="/product-images/{{ $produk->gambar_produk }}" class="img-fluid rounded" alt="produk">
                            </div>
                            <div class="col-8 col-md-6">
                                <h6 class="fw-bold text-dark">{{ $produk->nama_produk }}</h6>
                                <p class="mb-1 text-dark">Variasi: {{ $var }}</p>
                                <p class="mb-0 text-dark">Jumlah Produk: {{ $jlh }}</p>
                            </div>
                            <div class="col-md-4 text-end d-flex flex-column justify-content-center">
                                <p class="mb-1 text-dark fw-bold">Subtotal</p>
                                <p class="mb-0 text-dark">Rp. {{ number_format($produk->harga * $jlh, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card mb-3" style="min-height: 250px">
                        <div class="card-body">
                            <h5 class="fw-bold text-dark">Total Pesanan</h5>
                            <div class="d-flex justify-content-between">
                                <span class="text-dark">Total Produk</span>
                                <span>{{ $jlh }} Produk</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-dark">Total Harga Produk</span>
                                <span>Rp. {{ number_format($produk->harga * $jlh, 0, ',', '.') }}</span>
                            </div>
                            <hr>
                            <form action="/checkout/sekarang/produk" method="POST">
                                @csrf
                                @php $variasi = implode(', ', $aVariasi); @endphp
                                <input type="hidden" name="jlh_pesanan" value="{{ $jlh }}">
                                <input type="hidden" name="aVariasi" value="{{ $variasi }}">
                                <input type="hidden" name="idPro" value="{{ $produk->id_produk }}">
                                <div class="text-center">
                                    <button type="submit" class="btn btn-sm btn-primary">Pesan Sekarang</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h6 class="fw-bold text-dark">Informasi Penting!</h6>
                            <p class="mb-1 text-dark">Pengambilan dan pembayaran pesanan dilakukan di tempat.</p>
                            <p class="mb-0 text-dark fw-bold">Selamat belanja!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

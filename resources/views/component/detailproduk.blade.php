@include('navs')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
@if (session('error'))
    <script>
        // Tampilkan pesan error dalam pop-up
        Swal.fire({
            icon: 'error',
            title: 'Tidak Berhasil',
            text: '{{ session('error') }}', // Ambil pesan error dari session
        });
    </script>
@endif
@if (session('success'))
    <script>
        // Tampilkan pesan error dalam pop-up
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: '{{ session('success') }}', // Ambil pesan error dari session
        });
    </script>
@endif
<div class="site-section">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <img src="/product-images/{{ $produk->gambar_produk }}" alt="Image" class="img-fluid"
                    style="max-height: 350px">
            </div>
            <div class="col-md-6">
                <h2 class="text-black">{{ $produk->nama_produk }}</h2>
                <p><strong class="text-primary h4">Rp. <?php
                $angka = $produk->harga;
                echo number_format($angka, 0, ',', '.');
                ?></strong></p>
                <p class="text-dark">{{ $produk->deskripsi }}</p>

                {{-- <div class="mb-1 d-flex">
                    <label for="option-sm" class="d-flex mr-3 mb-3">
                        <span class="d-inline-block mr-2" style="top:-2px; position: relative;"><input type="radio"
                                id="option-sm" name="shop-sizes"></span> <span
                            class="d-inline-block text-black">Small</span>
                    </label>
                    <label for="option-md" class="d-flex mr-3 mb-3">
                        <span class="d-inline-block mr-2" style="top:-2px; position: relative;"><input type="radio"
                                id="option-md" name="shop-sizes"></span> <span
                            class="d-inline-block text-black">Medium</span>
                    </label>
                    <label for="option-lg" class="d-flex mr-3 mb-3">
                        <span class="d-inline-block mr-2" style="top:-2px; position: relative;"><input type="radio"
                                id="option-lg" name="shop-sizes"></span> <span
                            class="d-inline-block text-black">Large</span>
                    </label>
                    <label for="option-xl" class="d-flex mr-3 mb-3">
                        <span class="d-inline-block mr-2" style="top:-2px; position: relative;"><input type="radio"
                                id="option-xl" name="shop-sizes"></span> <span class="d-inline-block text-black"> Extra
                            Large</span>
                    </label>
                </div> --}}
                <hr>
                <form action="/tambah/keranjang/{{ $produk->id_produk }}" method="POST">
                    @csrf

                    @if (!empty($variasi_produk))
                        <input type="hidden" name="jlhV" value="{{ count($variasi_produk) }}">
                        @php
                            $ind = 1;
                            $jlhVariasi = $variasi_produk->count();
                        @endphp
                        @foreach ($variasi_produk as $vp)
                            <h5 class="mt-3">{{ $vp->nama_variasi }}</h5>
                            @php
                                $jenisV = json_decode($vp->variasi, true);

                            @endphp
                            <div class="row ml-1">
                                @foreach ($jenisV as $jv)
                                    <div class="form-check form-check-inline mr-4">
                                        <input class="form-check-input" type="radio" name="rb_{{ $ind }}"
                                            id="exampleRadios1" value="{{ $vp->nama_variasi }},{{ $jv }}"
                                            checked>
                                        <label class="form-check-label" for="exampleRadios1">
                                            {{ $jv }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @php
                                $ind++;
                            @endphp
                        @endforeach
                    @endif
                    <div class="row col-md-12 mt-2">
                        <div class="mb-5 mt-2">
                            <div class="input-group mb-3" style="max-width: 120px;">
                                <div class="input-group-prepend">
                                    <button class="btn btn-outline-primary js-btn-minus" type="button">&minus;</button>
                                </div>
                                <input type="text" class="form-control text-center" value="1" placeholder=""
                                    aria-label="Example text with button addon" aria-describedby="button-addon1"
                                    id="jumlah_pes" name="jumlah_pes">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-primary js-btn-plus" type="button">&plus;</button>
                                </div>
                            </div>
                        </div>
                        <div class="mb-5 ml-4" style="place-self: center">
                            <h6 class="text-dark" style="font-weight: bold"> Tersisa : {{ $produk->jumlah_produk }}
                            </h6>
                        </div>
                    </div>
                    <div class="row col-lg-12">
                        @guest
                                <div class="col-sm">
                                    <button type="submit" class="buy-now btn btn-sm btn-primary">Tambah ke
                                            Keranjang</button>
                                </div>
                                <br><br>
                                <div class="col-sm">
                                    <button type="button" onclick="belisekarang()"
                                            class="buy-now btn btn-sm btn-primary">Beli Sekarang</button>
                                    <input type="hidden" name="idPro" id="idPro" value="{{ $produk->id_produk }}">
                                </div>
                        @else
                            @if ($produk->role_pembeli == 'Publik')
                                <div class="col-sm">
                                    <p><button type="submit" class="buy-now btn btn-sm btn-primary">Tambah ke
                                            Keranjang</button></p>
                                </div>
                                <br><br>
                                <div class="col-sm">
                                    <p><button type="button" onclick="belisekarang()"
                                            class="buy-now btn btn-sm btn-primary">Beli Sekarang</button></p>
                                    <input type="hidden" name="idPro" id="idPro" value="{{ $produk->id_produk }}">
                                </div>
                            @else
                                @if (Auth::user()->role_pengguna != $produk->role_pembeli)
                                    <div class="col-sm">
                                        <p><button type="submit" class="buy-now btn btn-sm btn-primary" disabled>Tambah ke
                                                Keranjang</button></p>
                                    </div>
                                    <br><br>
                                    <div class="col-sm">
                                        <p><button type="button" onclick="belisekarang()"
                                                class="buy-now btn btn-sm btn-primary" disabled>Beli Sekarang</button></p>
                                        <input type="hidden" name="idPro" id="idPro"
                                            value="{{ $produk->id_produk }}">
                                    </div>
                                    <div class="col-md-12">
                                        <span>
                                            <i class="fa-solid fa-circle-info text-danger"></i><small
                                                class="ml-2 text-danger">Produk ini hanya dapat dibeli oleh
                                                {{ $produk->role_pembeli }}</small>
                                        </span>
                                    </div>
                                @else
                                    <div class="col-sm">
                                        <p><button type="submit" class="buy-now btn btn-sm btn-primary">Tambah ke
                                                Keranjang</button></p>
                                    </div>
                                    <br><br>
                                    <div class="col-sm">
                                        <p><button type="button" onclick="belisekarang()"
                                                class="buy-now btn btn-sm btn-primary">Beli Sekarang</button></p>
                                        <input type="hidden" name="idPro" id="idPro"
                                            value="{{ $produk->id_produk }}">
                                    </div>
                                @endif
                            @endif
                        @endguest

                    </div>
                    <script>
                        function belisekarang() {
                            var jlhVariasi = "<?php echo $jlhVariasi; ?>";
                            console.log(jlhVariasi);
                            var jlh = document.getElementById('jumlah_pes').value;
                            var idPro = document.getElementById('idPro').value
                            if (jlhVariasi != 0) {
                                var nilai = [];
                                for (let i = 1; i <= jlhVariasi; i++) {
                                    const radios = document.getElementsByName('rb_' + i);
                                    for (let j = 0; j < radios.length; j++) {
                                        if (radios[j].checked) {
                                            nilai.push(radios[j].value);
                                            console.log(nilai);
                                        }
                                    }
                                }
                            }else{
                                var nilai = 'empty'
                            }
                            window.location.href = '/beli/sekarang/' + idPro + '/' + jlh + '/' + nilai;
                            console.log(nilai);
                        }
                    </script>
                </form>
            </div>
        </div>
    </div>
</div>

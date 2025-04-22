@extends('home')

@section('content')
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
    <style>
        /* CSS untuk mengubah warna ikon */
        .carousel-control-prev-icon {
            filter: invert(100%) sepia(0%) saturate(0%) hue-rotate(0deg) brightness(100%) contrast(100%);
        }

        .carousel-control-next-icon {
            filter: invert(100%) sepia(0%) saturate(0%) hue-rotate(0deg) brightness(100%) contrast(100%);
        }
    </style>
    </style>
    <div class="container mt-2">
        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="pembeli/images/sdf.jpg" class="d-block w-100" alt="...">
                </div>
                <div class="carousel-item">
                    <img src="pembeli/images/qwe.jpg" class="d-block w-100" alt="...">
                </div>
            </div>
            <a class="carousel-control-prev" type="button" data-target="#carouselExampleIndicators" data-slide="prev"
                style="border-color: unset; background-color:transparent;justify-content:left">
                <span class="carousel-control-prev-icon ml-4" aria-hidden="true"></span>
            </a>
            <a class="carousel-control-next" type="button" data-target="#carouselExampleIndicators" data-slide="next"
                style="border-color: unset; background-color:transparent;justify-content:right">
                <span class="carousel-control-next-icon mr-4" aria-hidden="true"></span>
            </a>
        </div>
    </div>
    <script>
        // Inisialisasi Carousel
        $(document).ready(function() {
            $('.carousel').carousel();
        });
    </script>

    <div class="site-section site-blocks-2">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12 text-center">
                    <h2 class="h3 mb-3 text-black">Kategori Produk</h2>
                </div>
                <div class="row col-md-12 justify-content-center">
                    @foreach ($kategori_produk as $katpro)
                        <div class="col-sm-6 col-md-6 col-lg-2 mb-4 mb-lg-0">
                            <a class="block-2-item" href="/produk/{{ $katpro->kategori }}">
                                <figure class="image">
                                        <img src="/kategori-produk-images/{{ $katpro->gambar_kategori }}"
                                        alt="Gambar Kategori"
                                        class="img-fluid rounded w-100"
                                        style="height: 150px; object-fit: contain;">
                                </figure>
                                <div class="text">
                                    <span class="text-uppercase">kategori</span>
                                    <h6 class="text-white">{{ $katpro->kategori }}</h6>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="site-section block-3 site-blocks-2 bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-7 site-section-heading text-center pt-4">
                    <h2>Produk Terbaru</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="nonloop-block-3 owl-carousel">
                        @foreach ($produk_terbaru as $terbaru)
                        @php
                            $jumlahTerjual = App\Models\DetailPesanan::join('pesanans','pesanans.id','=', 'pesanandetails.pesanan_id')->where('pesanandetails.produk_id', $terbaru->id_produk)->where('pesanans.status', 'Selesai')->get();

                            $jlhterjual =0;
                            foreach ($jumlahTerjual as $terjual) {
                                $jlhterjual += $terjual->jumlah;
                            }
                        @endphp
                            <div class="item col-12">
                                <div class="block-4 text-center">
                                    <figure class="block-4-image">
                                        <img src="/product-images/{{$terbaru->gambar_produk}}" alt="Image placeholder" class="img-fluid" style="min-height: 250px; max-height: 250px">
                                    </figure>
                                    <div class="block-4-text p-2">
                                        <h6><a href="/detailproduk/{{$terbaru->id_produk}}">{{$terbaru->nama_produk}}</a></h6>
                                        <p class="mb-0">Rp. <?php
                                            echo number_format($terbaru->harga, 0, ',', '.');
                                            ?></p>
                                        <p class="text-primary font-weight-bold">{{$jlhterjual}} Terjual</p>
                                    </div>
                                </div>
                                <div class="block-4 text-center border" style="background-color: #00337C;border-bottom-right-radius: 5px;border-bottom-left-radius: 5px;">
                                    <a href="/detailproduk/{{ $terbaru->id_produk }}"><h5 class="text-light mt-2" style="text-align: center">Lihat Produk</h5></a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

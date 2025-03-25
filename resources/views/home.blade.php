<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" type="image/png" href="/pembeli/images/logo.png">
    <title>Delshop</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="{{ asset('https://fonts.googleapis.com/css?family=Mukta:300,400,700') }}">
    <link rel="stylesheet" href="{{ asset('pembeli/fonts/icomoon/style.css') }}">
    <link rel="stylesheet"
        href="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css') }}"
        crossorigin="anonymous">


    <link rel="stylesheet" href="{{ asset('pembeli/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('pembeli/css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('pembeli/css/jquery-ui.css') }}">
    <link rel="stylesheet" href="{{ asset('pembeli/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('pembeli/css/owl.theme.default.min.css') }}">


    <link rel="stylesheet" href="{{ asset('pembeli/css/aos.css') }}">

    <link rel="stylesheet" href="{{ asset('pembeli/css/style.css') }}">


</head>
<body>
    <div class="site-wrap">
        <header class="site-navbar" role="banner">
            <div class="site-navbar-top" style="padding-top: 2%;padding-bottom:2%">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-6 col-md-5 order-1 order-md-1  text-left">
                            <div class="row">
                                <a href="/" class="col-3"><img src="pembeli/images/logo.png" alt=""
                                        class="w-100"></a>
                                <nav class="site-navigation text-right text-md-center" role="navigation">
                                    <ul class="site-menu js-clone-nav d-none d-md-block">
                                        <li><a href="/">Beranda</a></li>
                                        <li><a href="/produk">Produk</a></li>
                                        <li><a href="/riwayat-pesanan">Pesanan</a></li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        @php
                            use Illuminate\Support\Facades\Route;
                            use Illuminate\Support\Facades\Auth;
                        @endphp

                        @if (Route::currentRouteName() === 'pembeli.produk')
                            <div class="col-6 mb-md-0 col-md-3 order-2 order-md-2 site-search-icon text-left">
                                <form action="" class="site-block-top-search">
                                    <span class="icon icon-search2"></span>
                                    <input type="text" name="search_produk" id="search_produk"
                                        class="form-control border-0" placeholder="Search">
                                </form>
                            </div>
                        @endif
                        @if (Route::currentRouteName() != 'pembeli.produk')
                            <div class="col-6 mb-md-0 col-md-3 order-2 order-md-2 site-search-icon text-left">
                            </div>
                        @endif
                        <div class="col-3 col-md-4 order-4 order-md-3 text-right">
                            <div class="site-top-icons">
                                @guest
                                    <ul>
                                        <a href="javascript:void(0);" id="notificationButton">
                                            <span class="icon icon-bell"></span>
                                        </a>
                                        <li>
                                            <a href="/" class="site-cart">
                                                <span class="icon icon-shopping_cart"></span>
                                                <span class="count">0<span>
                                            </a>
                                        </li>
                                        <li class="d-inline-block d-md-none ml-md-0"><a href="#"
                                                class="site-menu-toggle js-menu-toggle"><span class="icon-menu"></span></a>
                                        </li>
                                        <li class="pl-2 pt-2 pb-2">
                                            <a href="{{ route('login') }}" class="" id="dropdownMenuLink">
                                                <span class="text-white btn btn-primary btn-sm"
                                                    style="vertical-align: super; font-weight:bold">Masuk</span>
                                            </a>
                                        </li>
                                    </ul>
                                @else
                                    <ul>
                                        <li class="mr-2"><a href="javascript:;" class="nav-link p-0 site-cart"
                                                id="dropdownMenuButton" data-toggle="dropdown" aria-expanded="false"><span
                                                    class="icon icon-bell"></span>
                                                <span
                                                    class="count">{{ auth()->user()->unreadNotifications->count() }}</span></a>
                                            <ul class="dropdown-menu  dropdown-menu-end  px-2 py-3 me-sm-n4 scrollable-list"
                                                aria-labelledby="dropdownMenuButton"
                                                style="max-height: 300px;min-width: 480px; overflow-y:auto; top:0rem !important;right: 1.0rem !important">
                                                <span class="text-dark text-lg ml-3"
                                                    style="margin-bottom: 50%; font-weight:bold">Notifikasi!</span><br>
                                                @if (auth()->user()->unreadNotifications->count() == 0)
                                                    <a class="dropdown-item border-radius-md mt-3" href="javascript:;"
                                                        style="background-color: #F5F7F8">
                                                        <div class="d-flex py-1">
                                                            <div class="d-flex flex-column justify-content-center">
                                                                <h6 class="text-sm font-weight-normal mb-1">
                                                                    <small class="font-weight-bold text-danger"
                                                                        style="font-style: italic">
                                                                        Pesan Notifikasi Masih Kosong</small>
                                                                </h6>
                                                            </div>
                                                        </div>
                                                    </a>
                                                @else
                                                    @foreach (auth()->user()->unreadNotifications as $notification)
                                                        <li class="mb-1 mt-1">
                                                            <a class="notification-link dropdown-item border-radius-md"
                                                                href="{{ route('read.notification', ['id' => $notification->id]) }}"
                                                                data-notification-id="{{ $notification->id }}">
                                                                <div class="d-flex flex-column justify-content-center">
                                                                    <small class="text-xs">
                                                                        <span
                                                                            class="font-weight-bold">{{ $notification->data['data'] }}</span>
                                                                    </small>
                                                                    <small class="text-xs text-secondary mb-0">
                                                                        <i class="fa fa-clock fa-xs me-1"></i>
                                                                        {{ $notification->created_at->diffForHumans() }}
                                                                    </small>
                                                                </div>
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                @endif
                                            </ul>
                                        </li>
                                        <li>
                                            @php
                                                $jlhKeranjang = App\Models\DetailPesanan::join(
                                                    'pesanans',
                                                    'pesanans.id',
                                                    '=',
                                                    'pesanandetails.id',
                                                )
                                                    ->where('pesanans.user_id', Auth::user()->id)
                                                    ->where('pesanans.status', 'keranjang')
                                                    ->count();
                                            @endphp
                                            @if ($jlhKeranjang == 0)
                                                <a href="" class="site-cart">
                                                @else
                                                    <a href="/keranjang" class="site-cart">
                                            @endif
                                            <span class="icon icon-shopping_cart"></span>
                                            <span class="count">{{ $jlhKeranjang }}</span>
                                            </a>
                                        </li>
                                        {{-- <li class="d-inline-block d-md-none ml-md-0"><a href="#"
                                                class="site-menu-toggle js-menu-toggle"><span class="icon-menu"></span></a>
                                        </li> --}}
                                        <li class="dropdown pl-3">
                                            <a href="#" class="" id="dropdownMenuLink"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <img src="{{ asset('/user-images/profile.png') }}" alt="Profile"
                                                    class="rounded-circle border mb-3" style="width: 35px; height:35px">
                                                <span class="ml-1"
                                                    style="vertical-align: super">{{ Auth::user()->name }}</span>
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                <a class="dropdown-item" href="/profile">Profil Saya</a>
                                                <a class="dropdown-item" href="{{ route('logout') }}"
                                                    onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">Keluar
                                                </a>
                                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                                    class="d-none">
                                                    @csrf
                                                </form>
                                            </div>
                                        </li>
                                    </ul>
                                @endguest
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </header>

        @yield('content')

        <footer class="border-top" style="background-color: #00337c !important">
            <div class="container" style="padding-top: 2%;padding-bottom: 2%;">
                <div class="row">
                    <div class="col-lg-6 mb-5 mb-lg-0">
                        <div class="row">
                            <div class="col-md-4">
                                <a href="/" class="col-3"><img src="pembeli/images/logo.png" alt=""
                                        class="w-100 mt-1"></a>
                            </div>
                            <div class="col-md-6 col-lg-8">
                                <ul class="list-unstyled text-white">
                                    <li>Institut Teknologi Del</li>
                                    <li>Jl. Sisingamangaraja, Sitoluama</li>
                                    <li>Laguboti, Toba, Sumatera Utara, Indonesia</li>
                                    <li><a class="text-white" href="https://www.del.ac.id/">https://www.del.ac.id/</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6 col-lg-12 text-white">
                                <div class="row col-md-12 mt-2">
                                    <div class="col-md-12">
                                        <h5>Hubungi Kami</h5>
                                    </div>
                                    <div class="col-md-12">
                                        <p>ecommerce.delshop@gmail.com</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3 mb-4 mb-lg-0">
                        <h4 class="footer-heading mb-4 text-white">Menu</h4>
                        <div class="row col-md-12">
                            <ul class="list-unstyled text-white">
                                <li><a class="text-white" href="/"><span
                                            class="icon icon-angle-right mr-1 fw-bold"></span>Beranda</a></li>
                                <li><a class="text-white" href="/produk"><span
                                            class="icon icon-angle-right mr-1 fw-bold"></span>Produk</a></li>
                                <li><a class="text-white" href="/riwayat-pesanan"><span
                                            class="icon icon-angle-right mr-1 fw-bold"></span>Pesanan</a></li>
                                <li><a class="text-white" href="/profile"><span
                                            class="icon icon-angle-right mr-1 fw-bold"></span>Profil</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3 mb-lg-0">
                        <h4 class="footer-heading mb-4 text-white">Partner Delshop</h4>
                        <div class="row col-md-12">
                            <ul class="list-unstyled text-white">
                                <li><a class="text-white" href="https://www.del.ac.id/"><span
                                            class="icon icon-angle-right mr-1 fw-bold"></span>Institut Teknologi
                                        Del</a></li>
                                <li><a class="text-white" href="/"><span
                                            class="icon icon-angle-right mr-1 fw-bold"></span>Yayasan Del</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="row pt-5 mt-1 text-center">
                    <div class="col-md-12">
                        <h6 class="text-white">
                            ©Delshop. All Rights Reserved
                        </h6>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <script src="{{ asset('pembeli/js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('pembeli/js/jquery-ui.js') }}"></script>
    <script src="{{ asset('pembeli/js/popper.min.js') }}"></script>
    <script src="{{ asset('pembeli/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('pembeli/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('pembeli/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('pembeli/js/aos.js') }}"></script>

    <script src="{{ asset('pembeli/js/main.js') }}"></script>

</body>

</html>

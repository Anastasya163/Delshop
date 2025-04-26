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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="{{ asset('pembeli/css/aos.css') }}">

    <link rel="stylesheet" href="{{ asset('pembeli/css/style.css') }}">

    <style>
        .btn-extra-small {
            font-size: 10px;
        }
        .scrollable-list a.notification-link {
        white-space: normal;
        word-break: break-word;
}
    </style>

</head>

<body>

  <div class="site-wrap">
    <header class="site-navbar" role="banner">
      <div class="site-navbar-top">
        <div class="container">
          <div class="row align-items-center">

            <div class="col-6 col-md-3 order-2 order-md-1 site-search-icon text-left">
                <img src="{{asset('pembeli/images/logo.png')}}" alt="" width="100">
            </div>

            <div class="col-12 mb-3 mb-md-0 col-md-6 order-1 order-md-2 text-center">
                <!-- Bottom Navbar for large Screens -->
                <nav class="site-navigation text-right text-md-center d-none d-lg-block" role="navigation">
                    <div class="container">
                        <ul class="site-menu js-clone-nav">
                            <li><a href="/">Beranda</a></li>
                            <li><a href="/produk">Produk</a></li>
                            <li><a href="/riwayat-pesanan">Pesanan</a></li>
                        </ul>
                    </div>
                </nav>
                <!-- Bottom Navbar for Small Screens -->
                <nav class="navbar navbar-light bg-light bottom-nav d-block d-lg-none fixed-bottom rounded-top shadow">
                    <div class="container-fluid">
                        <ul class="navbar-nav w-100 d-flex flex-row justify-content-around">
                            <li class="nav-item text-center">
                                <a class="nav-link active" href="/">
                                    <i class="fas fa-home"></i><br> Beranda
                                </a>
                            </li>
                            <li class="nav-item text-center">
                                <a class="nav-link" href="/produk">
                                    <i class="fas fa-archive"></i><br> Produk
                                </a>
                            </li>
                            <li class="nav-item text-center">
                                <a class="nav-link" href="/riwayat-pesanan">
                                    <i class="fas fa-list"></i><br> Pesanan
                                </a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>

            <div class="col-6 col-md-3 order-3 order-md-3 text-right">
              <div class="site-top-icons">
                <ul>
                  <!-- Change text Sign In to Icon -->
                  @guest
                  <div class="d-none d-lg-block">
                    <li class="pl-2 pt-2 pb-2">
                        <a href="{{ route('login') }}">
                            <span class="icon icon-shopping_cart"></span>
                        </a>
                        <a href="{{ route('login') }}" id="dropdownMenuLink" title="Masuk">
                            <span class="text-white btn btn-primary btn-extra-small" style="vertical-align: super; font-weight:bold">
                            Masuk </i>
                            </span>
                        </a>
                    </li>
                  </div>

                  <div class="d-block d-lg-none">
                    <li class="pl-2 pt-2 pb-2">
                        <a href="{{ route('login') }}">
                            <span class="icon icon-shopping_cart"></span>
                        </a>
                        <a href="{{ route('login') }}" id="dropdownMenuLink" title="Masuk">
                            <span class="text-white btn btn-primary btn-extra-small" style="vertical-align: super; font-weight:bold">
                                <i class="fa fa-sign-in"></i>
                            </span>
                        </a>
                    </li>
                  </div>

                  @else
                  <li class="mr-2">
                    <a href="javascript:;" class="nav-link p-0 site-cart"
                        id="dropdownMenuButton" data-toggle="dropdown" aria-expanded="false">
                        <span class="icon icon-bell"></span>
                        <span class="count">{{ auth()->user()->unreadNotifications->count() }}</span>
                    </a>

                    <div class="dropdown-menu-container d-flex justify-content-center w-100">
                        <ul class="dropdown-menu px-2 py-3 me-sm-n4 scrollable-list"
                            aria-labelledby="dropdownMenuButton"
                            style="max-height: 300px; min-width: 280px; max-width: 90vw; overflow-y: auto;">
                            <span class="text-dark text-lg ml-3" style="margin-bottom: 50%; font-weight:bold">Notifikasi!</span><br>
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
                   @if($jlhKeranjang == 0)
                    <a href="" class="site-cart">
                   @else
                    <a href="/keranjang" class="site-cart">
                   @endif
                      <span class="icon icon-shopping_cart"></span>
                      <span class="count">{{ $jlhKeranjang }}</span>
                    </a>
                  </li>
                  <li class="dropdown pl-3">
                    <a href="#" class="" id="dropdownMenuLink"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img src="{{ asset('/user-images/profile.png') }}" alt="Profile"
                            class="rounded-circle border mb-3" style="width: 30px; height:30px">
                        <span class="fa fa-angle-down" style="vertical-align: super"></span>
                    </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <p class="dropdown-item">{{ Auth::user()->name }}</p>
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
                  @endguest

                </ul>
              </div>
            </div>

          </div>
        </div>
      </div>

    </header>

    @yield('content')

    <footer class="border-top" style="background-color: #00337c !important">
        <div class="container py-4">
            <div class="row gx-4">
                <!-- Kolom Alamat & Kontak -->
                <div class="col-lg-6 mb-4">
                    <h4 class="footer-heading mb-3 text-white">Kontak</h4>
                    <ul class="list-unstyled text-white mb-3">
                        <li>Institut Teknologi Del</li>
                        <li>Jl. Sisingamangaraja, Sitoluama</li>
                        <li>Laguboti, Toba, Sumatera Utara, Indonesia</li>
                        <li>
                            <a class="text-white" href="https://www.del.ac.id/">https://www.del.ac.id/</a>
                        </li>
                    </ul>

                    <h5 class="text-white fw-bold mb-2 mt-4">Hubungi Kami</h5>
                    <ul class="list-unstyled text-white">
                        <li>ecommerce.delshop@gmail.com</li>
                    </ul>
                </div>

                <!-- Kolom Menu -->
                <div class="col-md-6 col-lg-3 mb-4">
                    <h4 class="footer-heading mb-3 text-white">Menu</h4>
                    <ul class="list-unstyled text-white">
                        <li><a class="text-white" href="/">› Beranda</a></li>
                        <li><a class="text-white" href="/produk">› Produk</a></li>
                        <li><a class="text-white" href="/riwayat-pesanan">› Pesanan</a></li>
                        <li><a class="text-white" href="/profile">› Profil</a></li>
                    </ul>
                </div>



                <!-- Kolom Partner -->
                <div class="col-md-6 col-lg-3 mb-4">
                    <h4 class="footer-heading mb-3 text-white">Partner Delshop</h4>
                    <ul class="list-unstyled text-white">
                        <li><a class="text-white" href="https://www.del.ac.id/">Institut Teknologi Del</a></li>
                        <li><a class="text-white" href="/">Yayasan Del</a></li>
                    </ul>
                </div>
            </div>

            <!-- Copyright -->
            <div class="row pt-4 text-center">
                <div class="col-md-12">
                    <h6 class="text-white">©Delshop. All Rights Reserved</h6>
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

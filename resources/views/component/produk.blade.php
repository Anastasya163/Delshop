@include('navs')
<div class="site-section">
    <div class="container">
        <div class="row mb-5">
            <div class="col-md-9 order-2">
                <div class="row">
                    <div class="col-md-12 mb-5">
                        <div class="d-flex">
                            <form action="" class="site-block-top-search">
                                <span class="icon icon-search2"></span>
                                <input type="text" name="search_produk" id="search_produk"
                                class="form-control border-4" placeholder="Search">
                            </form>
                            <div class="dropdown mr-1 ml-md-auto btn-group">

                                    &nbsp&nbsp

                                    <div class="d-flex">
                                        <button type="button" class="btn btn-sm dropdown-toggle text-white"
                                        id="dropdownMenuReference" data-toggle="dropdown"
                                        style="background-color: #00337C !important">Urutkan</button>
                                        @php
                                            $semua = 'semua';
                                            $terlama = 'terlama';
                                            $terbaru = 'terbaru';
                                            $tertinggi = 'tertinggi';
                                            $terendah = 'terendah';
                                        @endphp
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuReference">
                                            <a class="dropdown-item" href="/produk/{{ $terbaru }}">Terbaru</a>
                                            <a class="dropdown-item" href="/produk/{{ $terlama }}">Terlama</a>
                                            <a class="dropdown-item" href="/produk/{{ $tertinggi }}">Harga Tertinggi</a>
                                            <a class="dropdown-item" href="/produk/{{ $terendah }}">Harga Terendah</a>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-5" id="produk_list">
                    @foreach ($produk as $pro)
                        @php
                            $jumlahTerjual = App\Models\DetailPesanan::join(
                                'pesanans',
                                'pesanans.id',
                                '=',
                                'pesanandetails.pesanan_id',
                            )
                                ->where('pesanandetails.produk_id', $pro->id_produk)
                                ->where('pesanans.status', 'Selesai')
                                ->get();

                            $jlhterjual = 0;
                            foreach ($jumlahTerjual as $terjual) {
                                $jlhterjual += $terjual->jumlah;
                            }
                        @endphp
                        <div class="col-sm-6 col-lg-4 mb-4" data-aos="fade-up">
                            <div class="block-4 text-center border" style="border-top-right-radius: 5px;border-top-left-radius: 5px;">
                                <figure class="block-4-image mt-2">
                                    <a href="/detailproduk/{{ $pro->id_produk }}"><img
                                            src="/product-images/{{ $pro->gambar_produk }}" alt="Image placeholder"
                                            class="img-fluid"
                                            style="min-height: 150px; max-height: 150px; min-width: 180px"></a>
                                </figure>

                                <div class="block-4-text">
                                    <a href="/detailproduk/{{ $pro->id_produk }}">
                                        <h6>{{ $pro->nama_produk }}</h6>
                                    </a>
                                    <p class="mb-0">Rp. <?php
                                    $angka = $pro->harga;
                                    echo number_format($angka, 0, ',', '.');
                                    ?></p>
                                    <p class="text-primary font-weight-bold">{{ $jlhterjual }} Terjual</p>
                                </div>

                            </div>
                            <div class="block-4 text-center border" style="background-color: #00337C;border-bottom-right-radius: 5px;border-bottom-left-radius: 5px;">
                                <a href="/detailproduk/{{ $pro->id_produk }}"><h5 class="text-light mt-2" style="text-align: center">Lihat Produk</h5></a>
                            </div>
                        </div>
                    @endforeach
                </div>
                @if ($produk->lastPage() > 1)
                    <div class="row" data-aos="fade-up" name="pagination_pro" id="pagination_pro">
                        <div class="col-md-12 text-center">
                            <div class="site-block-27">
                                <ul>
                                    <li>
                                        @if ($produk->onFirstPage())
                                            <span>&lt;</span>
                                        @else
                                            <a href="{{ $produk->previousPageUrl() }}">&lt;</a>
                                        @endif
                                    </li>

                                    <!-- Tautan untuk setiap halaman -->
                                    @for ($i = 1; $i <= $produk->lastPage(); $i++)
                                        <li class="{{ $produk->currentPage() == $i ? 'active' : '' }}">
                                            <a href="{{ $produk->url($i) }}">{{ $i }}</a>
                                        </li>
                                    @endfor

                                    <!-- Tombol selanjutnya -->
                                    <li>
                                        @if ($produk->hasMorePages())
                                            <a href="{{ $produk->nextPageUrl() }}">&gt;</a>
                                        @else
                                            <span>&gt;</span>
                                        @endif
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-md-3 order-1 mb-5 mb-md-0">
                <div class="border p-4 rounded mb-4">
                    <h3 class="mb-3 h6 text-uppercase text-black d-block">Kategori</h3>
                    <ul class="list-unstyled mb-0">
                        @foreach ($kategori_produk as $kapro)
                            @php
                                $jlhProdukKat = App\Models\Produk::where('kategori_produk', $kapro->kategori)->count();
                            @endphp
                            @if ($jlhProdukKat != 0)
                                <li class="mb-1"><a href="/produk/{{ $kapro->kategori }}"
                                        class="d-flex"><span>{{ $kapro->kategori }}</span></a></li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    function renderProduk(data) {
        if (data.length > 0) {
            var html = '';
            $.each(data, function(index, pro) {
                html += `
                <div class="col-sm-6 col-lg-3 mb-4" data-aos="fade-up">
                    <div class="block-4 text-center border">
                        <figure class="block-4-image">
                            <a href="/detailproduk/${pro.id_produk}">
                                <img src="/product-images/${pro.gambar_produk}" alt="Image placeholder" class="img-fluid" style="min-height: 150px; max-height: 150px; min-width: 180px">
                            </a>
                        </figure>
                        <div class="block-4-text">
                            <a href="/detailproduk/${pro.id_produk}">
                                <h6>${pro.nama_produk}</h6>
                            </a>
                            <p class="mb-0">Rp. ${pro.harga}</p>
                            <p class="text-primary font-weight-bold">Belum Terjual</p>
                        </div>
                    </div>
                </div>`;
            });
            $('#produk_list').html(html);
        } else {
            renderNoResult();
        }
        $('#pagination_pro').prop('hidden', true);
    }

    function renderNoResult() {
        var noResultHtml =
            '<div class="col-md-12 text-center"><p>Tidak ada hasil yang ditemukan.</p></div>';
        $('#produk_list').html(noResultHtml);
    }

    $(document).ready(function() {
        function fetchProduk(query) {
            $.ajax({
                url: '/search',
                method: 'GET',
                data: {
                    query: query
                },
                success: function(data) {
                    renderProduk(data);
                },
                error: function(xhr, status, error) {
                    renderNoResult();
                }
            });
        }

        $('#search_produk').on('input', function() {
            var query = $(this).val();
            fetchProduk(query);
            $('#pagination_pro').prop('hidden', true);
        });
    });
</script>

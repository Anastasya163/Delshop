@include('navs')
@php
    use App\Models\DetailPesanan;
@endphp
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
<style>
    .nav-pills .nav-link.active {
        background-color: #00337C !important;
        /* Warna latar belakang */
        color: white;
        /* Warna teks */
    }

    /* Merubah warna teks pada selector yang tidak aktif */
    .nav-pills .nav-link {
        color: black;
        /* Warna teks default */
    }
</style>

<div class="site-section">
    <div class="container">
        <!-- Navs -->
        <ul class="nav nav-pills nav-fill p-1" role="tablist" style="border: solid 2px; border-radius: 10px">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#semua">Semua</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#menunggu">Menunggu</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#diproses">Diproses</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#selesai">Selesai</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#dibatalkan">Dibatalkan</a>
            </li>
        </ul>
        <!-- Konten untuk setiap tab -->
        <div class="tab-content pt-3">
            <div id="semua" class="tab-pane fade show active">
                @foreach ($pesanan as $pes)
                    <div class="container pb-2">
                        <div class="row" style="border: solid 1px; border-radius: 5px">
                            <div class="col-lg-10 p-4">
                                <div class="row">
                                    @php
                                        $detail_pes = DetailPesanan::join(
                                            'produk',
                                            'produk.id_produk',
                                            '=',
                                            'pesanandetails.produk_id',
                                        )
                                            ->where('pesanan_id', $pes->id)
                                            ->first();
                                        $detail = DetailPesanan::where('pesanan_id', $pes->id)->get();
                                        $j = 0;
                                        $h = 0;
                                        foreach ($detail as $data) {
                                            $j += $data->jumlah;
                                            $h += $data->jumlah_harga;
                                        }
                                        $jumlah_detail = count($detail);
                                        $sisa = $jumlah_detail - 1;
                                    @endphp
                                <div class="col-lg-2 d-flex justify-content-center align-items-center">
                                    <img src="/product-images/{{$detail_pes->gambar_produk}}" alt="" class="img-fluid" style="max-height: 150px; object-fit: contain;">
                                    </div>
                                    @if ($pes->status == 'Menunggu')
                                        <div class="col-lg-6" style="place-self: center">
                                            <div class="row">
                                                <div class="col-12 d-flex flex-column align-items-center align-items-lg-start text-center text-lg-start">
                                                    <span class="pr-3"
                                                        style="color: #00337C;font-weight:bold">{{ $pes->kode }}</span>
                                                    {{ $pes->tanggal }}
                                                </div>

                                                <div class="col-12 d-flex flex-column align-items-center align-items-lg-start text-center text-lg-start">
                                                <div>
                                                    <span class="text-dark me-2">{{ $detail_pes->nama_produk }}</span>
                                                    @if ($sisa != 0)
                                                        <small>
                                                            <a href="/detail-pesanan/{{ $pes->kode }}" class="p-1 text-white bg-secondary rounded">
                                                                +{{ $sisa }} lainnya
                                                            </a>
                                                        </small>
                                                    @endif
                                                </div>

                                                <div>
                                                    <span class="text-dark">Jumlah Produk : {{ $j }}</span>
                                                </div>

                                                <div>
                                                    <span class="text-dark">Nama Pemesan : {{ $pes->name }}</span>
                                                </div>
                                            </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="col-lg-6" style="place-self: center">
                                            <div class="row">
                                                <div class="col-12 d-flex flex-column align-items-center align-items-lg-start text-center text-lg-start">
                                                    <span class="pr-3"
                                                        style="color: #00337C;font-weight:bold">{{ $pes->kode }}</span>
                                                    {{ $pes->tanggal }}
                                                </div>
                                                @php
                                                    $detail_pes = DetailPesanan::join(
                                                        'produk',
                                                        'produk.id_produk',
                                                        '=',
                                                        'pesanandetails.produk_id',
                                                    )
                                                        ->where('pesanan_id', $pes->id)
                                                        ->first();
                                                    $detail = DetailPesanan::where('pesanan_id', $pes->id)->get();
                                                    $j = 0;
                                                    $h = 0;
                                                    foreach ($detail as $data) {
                                                        $j += $data->jumlah;
                                                        $h += $data->jumlah_harga;
                                                    }
                                                    $jumlah_detail = count($detail);
                                                    $sisa = $jumlah_detail - 1;
                                                @endphp
                                                <div class="col-12 d-flex flex-column align-items-center align-items-lg-start text-center text-lg-start">
                                                <div>
                                                    <span class="text-dark me-2">{{ $detail_pes->nama_produk }}</span>
                                                    @if ($sisa != 0)
                                                        <small>
                                                            <a href="/detail-pesanan/{{ $pes->kode }}" class="p-1 text-white bg-secondary rounded">
                                                                +{{ $sisa }} lainnya
                                                            </a>
                                                        </small>
                                                    @endif
                                                </div>

                                                <div>
                                                    <span class="text-dark">Jumlah Produk : {{ $j }}</span>
                                                </div>

                                                <div>
                                                    <span class="text-dark">Nama Pemesan : {{ $pes->name }}</span>
                                                </div>
                                            </div>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-lg-4" style="place-self: center">
                                        <div class="row" style="place-content: center;text-align-last:center">
                                            @if ($pes->status == 'Menunggu')
                                                <div class="col-lg-12 pt-1 pb-1 pl-3 pr-3 bg-warning text-white"
                                                    style="border-radius: 5px; max-width:max-content;font-weight:bold">
                                                    {{ $pes->status }}
                                                </div>
                                            @endif
                                            @if ($pes->status == 'Diproses')
                                                <div class="col-lg-12 pt-1 pb-1 pl-3 pr-3 bg-info text-white"
                                                    style="border-radius: 5px; max-width:max-content;font-weight:bold">
                                                    {{ $pes->status }}
                                                </div>
                                            @endif
                                            @if ($pes->status == 'Selesai')
                                                <div class="col-lg-12 pt-1 pb-1 pl-3 pr-3 bg-success text-white"
                                                    style="border-radius: 5px; max-width:max-content;font-weight:bold">
                                                    {{ $pes->status }}
                                                </div>
                                            @endif
                                            @if ($pes->status == 'Dibatalkan')
                                                <div class="col-lg-12 pt-1 pb-1 pl-3 pr-3 bg-danger text-white"
                                                    style="border-radius: 5px; max-width:max-content;font-weight:bold">
                                                    {{ $pes->status }}
                                                </div>
                                            @endif

                                            <div class="col-lg-12 pt-5">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <h5 class="text-dark">Total Belanja</h5>
                                                    </div>
                                                    <div class="col-12">
                                                        <span class="text-dark" style="font-weight: bold">Rp.
                                                            <?php
                                                            echo number_format($h, 0, ',', '.');
                                                            ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 -4"
                                style="background-color: #00337C !important; border-bottom-right-radius:5px;border-top-right-radius:5px; display: flex; justify-content: center; align-items: center;">
                                <a href="/detail-pesanan/{{ $pes->kode }}"
                                    style="color: #fff;display: flex; justify-content: center; align-items: center;">
                                    <span class="text-white">Lihat Detail Pesanan</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div id="menunggu" class="tab-pane fade">
                @foreach ($pesanan_menunggu as $pes)
                    <div class="container pb-2">
                        <div class="row" style="border: solid 1px; border-radius: 5px">
                            <div class="col-lg-10 p-4">
                                <div class="row">
                                    @php
                                        $detail_pes = DetailPesanan::join(
                                            'produk',
                                            'produk.id_produk',
                                            '=',
                                            'pesanandetails.produk_id',
                                        )
                                            ->where('pesanan_id', $pes->id)
                                            ->first();
                                        $detail = DetailPesanan::where('pesanan_id', $pes->id)->get();
                                        $j = 0;
                                        $h = 0;
                                        foreach ($detail as $data) {
                                            $j += $data->jumlah;
                                            $h += $data->jumlah_harga;
                                        }
                                        $jumlah_detail = count($detail);
                                        $sisa = $jumlah_detail - 1;
                                    @endphp
                                    <div class="col-lg-2 col-4 d-flex justify-content-center align-items-center text-center mx-auto mb-2">
                                        <img src="/product-images/{{$detail_pes->gambar_produk}}" alt="" class="img-fluid" style="max-height: 150px; object-fit: contain;">
                                    </div>
                                    @if ($pes->status == 'Menunggu')
                                        <div class="col-lg-6" style="place-self: center">
                                            <div class="row">
                                                <div class="col-12 d-flex flex-column align-items-center align-items-lg-start text-center text-lg-start">
                                                    <span class="pr-3"
                                                        style="color: #00337C;font-weight:bold">{{ $pes->kode }}</span>
                                                    {{ $pes->tanggal }}
                                                </div>

                                                <div class="col-12 d-flex flex-column align-items-center align-items-lg-start text-center text-lg-start">
                                                <div>
                                                    <span class="text-dark me-2">{{ $detail_pes->nama_produk }}</span>
                                                    @if ($sisa != 0)
                                                        <small>
                                                            <a href="/detail-pesanan/{{ $pes->kode }}" class="p-1 text-white bg-secondary rounded">
                                                                +{{ $sisa }} lainnya
                                                            </a>
                                                        </small>
                                                    @endif
                                                </div>

                                                <div>
                                                    <span class="text-dark">Jumlah Produk : {{ $j }}</span>
                                                </div>

                                                <div>
                                                    <span class="text-dark">Nama Pemesan : {{ $pes->name }}</span>
                                                </div>
                                            </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="col-lg-6" style="place-self: center">
                                            <div class="row">
                                                <div class="col-12 d-flex flex-column align-items-center align-items-lg-start text-center text-lg-start">
                                                    <span class="pr-3"
                                                        style="color: #00337C;font-weight:bold">{{ $pes->kode }}</span>
                                                    {{ $pes->tanggal }}
                                                </div>
                                                @php
                                                    $detail_pes = DetailPesanan::join(
                                                        'produk',
                                                        'produk.id_produk',
                                                        '=',
                                                        'pesanandetails.produk_id',
                                                    )
                                                        ->where('pesanan_id', $pes->id)
                                                        ->first();
                                                    $detail = DetailPesanan::where('pesanan_id', $pes->id)->get();
                                                    $j = 0;
                                                    $h = 0;
                                                    foreach ($detail as $data) {
                                                        $j += $data->jumlah;
                                                        $h += $data->jumlah_harga;
                                                    }
                                                    $jumlah_detail = count($detail);
                                                    $sisa = $jumlah_detail - 1;
                                                @endphp
                                                <div class="col-12 d-flex flex-column align-items-center align-items-lg-start text-center text-lg-start">
                                                <div>
                                                    <span class="text-dark me-2">{{ $detail_pes->nama_produk }}</span>
                                                    @if ($sisa != 0)
                                                        <small>
                                                            <a href="/detail-pesanan/{{ $pes->kode }}" class="p-1 text-white bg-secondary rounded">
                                                                +{{ $sisa }} lainnya
                                                            </a>
                                                        </small>
                                                    @endif
                                                </div>

                                                <div>
                                                    <span class="text-dark">Jumlah Produk : {{ $j }}</span>
                                                </div>

                                                <div>
                                                    <span class="text-dark">Nama Pemesan : {{ $pes->name }}</span>
                                                </div>
                                            </div>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-lg-4" style="place-self: center">
                                        <div class="row" style="place-content: center;text-align-last:center">
                                            @if ($pes->status == 'Menunggu')
                                                <div class="col-lg-12 pt-1 pb-1 pl-3 pr-3 bg-warning text-white"
                                                    style="border-radius: 5px; max-width:max-content;font-weight:bold">
                                                    {{ $pes->status }}
                                                </div>
                                            @endif
                                            @if ($pes->status == 'Diproses')
                                                <div class="col-lg-12 pt-1 pb-1 pl-3 pr-3 bg-info text-white"
                                                    style="border-radius: 5px; max-width:max-content;font-weight:bold">
                                                    {{ $pes->status }}
                                                </div>
                                            @endif
                                            @if ($pes->status == 'Selesai')
                                                <div class="col-lg-12 pt-1 pb-1 pl-3 pr-3 bg-success text-white"
                                                    style="border-radius: 5px; max-width:max-content;font-weight:bold">
                                                    {{ $pes->status }}
                                                </div>
                                            @endif
                                            @if ($pes->status == 'Dibatalkan')
                                                <div class="col-lg-12 pt-1 pb-1 pl-3 pr-3 bg-danger text-white"
                                                    style="border-radius: 5px; max-width:max-content;font-weight:bold">
                                                    {{ $pes->status }}
                                                </div>
                                            @endif

                                            <div class="col-lg-12 pt-5">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <h5 class="text-dark">Total Belanja</h5>
                                                    </div>
                                                    <div class="col-12">
                                                        <span class="text-dark" style="font-weight: bold">Rp.
                                                            <?php
                                                            echo number_format($h, 0, ',', '.');
                                                            ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 -4"
                                style="background-color: #00337C !important; border-bottom-right-radius:5px;border-top-right-radius:5px; display: flex; justify-content: center; align-items: center;">
                                <a href="/detail-pesanan/{{ $pes->kode }}"
                                    style="color: #fff;display: flex; justify-content: center; align-items: center;">
                                    <span class="text-white">Lihat Detail Pesanan</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div id="diproses" class="tab-pane fade">
                @foreach ($pesanan_diproses as $pes)
                    <div class="container pb-2">
                        <div class="row" style="border: solid 1px; border-radius: 5px">
                            <div class="col-lg-10 p-4">
                                <div class="row">
                                    @php
                                        $detail_pes = DetailPesanan::join(
                                            'produk',
                                            'produk.id_produk',
                                            '=',
                                            'pesanandetails.produk_id',
                                        )
                                            ->where('pesanan_id', $pes->id)
                                            ->first();
                                        $detail = DetailPesanan::where('pesanan_id', $pes->id)->get();
                                        $j = 0;
                                        $h = 0;
                                        foreach ($detail as $data) {
                                            $j += $data->jumlah;
                                            $h += $data->jumlah_harga;
                                        }
                                        $jumlah_detail = count($detail);
                                        $sisa = $jumlah_detail - 1;
                                    @endphp
                                    <div class="col-lg-2 col-4 d-flex justify-content-center align-items-center text-center mx-auto mb-2">
                                        <img src="/product-images/{{$detail_pes->gambar_produk}}" alt="" class="img-fluid" style="max-height: 150px; object-fit: contain;">
                                    </div>
                                    @if ($pes->status == 'Menunggu')
                                        <div class="col-lg-4" style="place-self: center">
                                            <div class="row">
                                                <div class="col-12 d-flex flex-column align-items-center align-items-lg-start text-center text-lg-start">
                                                    <span class="pr-3"
                                                        style="color: #00337C;font-weight:bold">{{ $pes->kode }}</span>
                                                    {{ $pes->tanggal }}
                                                </div>

                                                <div class="col-12 d-flex flex-column align-items-center align-items-lg-start text-center text-lg-start">
                                                <div>
                                                    <span class="text-dark me-2">{{ $detail_pes->nama_produk }}</span>
                                                    @if ($sisa != 0)
                                                        <small>
                                                            <a href="/detail-pesanan/{{ $pes->kode }}" class="p-1 text-white bg-secondary rounded">
                                                                +{{ $sisa }} lainnya
                                                            </a>
                                                        </small>
                                                    @endif
                                                </div>

                                                <div>
                                                    <span class="text-dark">Jumlah Produk : {{ $j }}</span>
                                                </div>

                                                <div>
                                                    <span class="text-dark">Nama Pemesan : {{ $pes->name }}</span>
                                                </div>
                                            </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-2" style="place-self: center; text-align:center">
                                            <input type="hidden" name="kodePes" id="kodePes"
                                                value="{{ $pes->kode }}">
                                            <button data-toggle="modal" data-target=".cancelPesanan"
                                                class="btn btn-danger">Batalkan Pesanan</button>
                                        </div>
                                        <div class="modal fade cancelPesanan" id="defaultModal" tabindex="-1"
                                            role="dialog" aria-labelledby="defaultModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-md" role="document">
                                                <div class="modal-content">
                                                    <form action="/pembatalan/pesanan/{{ $pes->kode }}"
                                                        method="POST" name="form-cancel" id="form-cancel">
                                                        @csrf
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="defaultModalLabel">Konfirmasi
                                                                Pembatalan
                                                                Pesanan
                                                            </h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="col-lg-12">
                                                                <div class="form-group mb-3">
                                                                    <label for="alasan">Alasan Pembatalan</label>
                                                                    <input type="text" id="alasan" name="alasan"
                                                                        class="form-control">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn mb-2 btn-secondary"
                                                                data-dismiss="modal">Tutup</button>
                                                            <button type="button" onclick="canPes()"
                                                                class="btn mb-2 btn-danger">Batalkan Pesanan</button>
                                                        </div>
                                                        <script>
                                                            function canPes() {
                                                                Swal.fire({
                                                                    title: 'Konfirmasi',
                                                                    text: 'Apakah Anda yakin ingin membatalkan pesanan ini?',
                                                                    icon: 'warning',
                                                                    showCancelButton: true,
                                                                    confirmButtonText: 'Ya',
                                                                    cancelButtonText: 'Batal',
                                                                }).then((result) => {
                                                                    if (result.isConfirmed) {
                                                                        document.getElementById('form-cancel').submit();
                                                                    }
                                                                });
                                                            }
                                                        </script>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <script>
                                            function batalkanPesanan() {
                                                var kode = $('#kodePes').val();
                                                Swal.fire({
                                                    title: 'Konfirmasi',
                                                    text: 'Apakah Anda yakin ingin membatalkan pesanan ini?',
                                                    icon: 'warning',
                                                    showCancelButton: true,
                                                    confirmButtonText: 'Ya',
                                                    cancelButtonText: 'Batal',
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        window.location.href = '/batalkan/pesanan/' + kode;
                                                    }
                                                });
                                            }
                                        </script>
                                    @else
                                        <div class="col-lg-6" style="place-self: center">
                                            <div class="row">
                                                <div class="col-12 d-flex flex-column align-items-center align-items-lg-start text-center text-lg-start">
                                                    <span class="pr-3"
                                                        style="color: #00337C;font-weight:bold">{{ $pes->kode }}</span>
                                                    {{ $pes->tanggal }}
                                                </div>
                                                @php
                                                    $detail_pes = DetailPesanan::join(
                                                        'produk',
                                                        'produk.id_produk',
                                                        '=',
                                                        'pesanandetails.produk_id',
                                                    )
                                                        ->where('pesanan_id', $pes->id)
                                                        ->first();
                                                    $detail = DetailPesanan::where('pesanan_id', $pes->id)->get();
                                                    $j = 0;
                                                    $h = 0;
                                                    foreach ($detail as $data) {
                                                        $j += $data->jumlah;
                                                        $h += $data->jumlah_harga;
                                                    }
                                                    $jumlah_detail = count($detail);
                                                    $sisa = $jumlah_detail - 1;
                                                @endphp
                                                <div class="col-12 d-flex flex-column align-items-center align-items-lg-start text-center text-lg-start">
                                                <div>
                                                    <span class="text-dark me-2">{{ $detail_pes->nama_produk }}</span>
                                                    @if ($sisa != 0)
                                                        <small>
                                                            <a href="/detail-pesanan/{{ $pes->kode }}" class="p-1 text-white bg-secondary rounded">
                                                                +{{ $sisa }} lainnya
                                                            </a>
                                                        </small>
                                                    @endif
                                                </div>

                                                <div>
                                                    <span class="text-dark">Jumlah Produk : {{ $j }}</span>
                                                </div>

                                                <div>
                                                    <span class="text-dark">Nama Pemesan : {{ $pes->name }}</span>
                                                </div>
                                            </div>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-lg-4" style="place-self: center">
                                        <div class="row" style="place-content: center;text-align-last:center">
                                            @if ($pes->status == 'Menunggu')
                                                <div class="col-lg-12 pt-1 pb-1 pl-3 pr-3 bg-warning text-white"
                                                    style="border-radius: 5px; max-width:max-content;font-weight:bold">
                                                    {{ $pes->status }}
                                                </div>
                                            @endif
                                            @if ($pes->status == 'Diproses')
                                                <div class="col-lg-12 pt-1 pb-1 pl-3 pr-3 bg-info text-white"
                                                    style="border-radius: 5px; max-width:max-content;font-weight:bold">
                                                    {{ $pes->status }}
                                                </div>
                                            @endif
                                            @if ($pes->status == 'Selesai')
                                                <div class="col-lg-12 pt-1 pb-1 pl-3 pr-3 bg-success text-white"
                                                    style="border-radius: 5px; max-width:max-content;font-weight:bold">
                                                    {{ $pes->status }}
                                                </div>
                                            @endif
                                            @if ($pes->status == 'Dibatalkan')
                                                <div class="col-lg-12 pt-1 pb-1 pl-3 pr-3 bg-danger text-white"
                                                    style="border-radius: 5px; max-width:max-content;font-weight:bold">
                                                    {{ $pes->status }}
                                                </div>
                                            @endif

                                            <div class="col-lg-12 pt-5">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <h5 class="text-dark">Total Belanja</h5>
                                                    </div>
                                                    <div class="col-12">
                                                        <span class="text-dark" style="font-weight: bold">Rp.
                                                            <?php
                                                            echo number_format($h, 0, ',', '.');
                                                            ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 -4"
                                style="background-color: #00337C !important; border-bottom-right-radius:5px;border-top-right-radius:5px; display: flex; justify-content: center; align-items: center;">
                                <a href="/detail-pesanan/{{ $pes->kode }}"
                                    style="color: #fff;display: flex; justify-content: center; align-items: center;">
                                    <span class="text-white">Lihat Detail Pesanan</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div id="selesai" class="tab-pane fade">
                @foreach ($pesanan_selesai as $pes)
                    <div class="container pb-2">
                        <div class="row" style="border: solid 1px; border-radius: 5px">
                            <div class="col-lg-10 p-4">
                                <div class="row">
                                    @php
                                        $detail_pes = DetailPesanan::join(
                                            'produk',
                                            'produk.id_produk',
                                            '=',
                                            'pesanandetails.produk_id',
                                        )
                                            ->where('pesanan_id', $pes->id)
                                            ->first();
                                        $detail = DetailPesanan::where('pesanan_id', $pes->id)->get();
                                        $j = 0;
                                        $h = 0;
                                        foreach ($detail as $data) {
                                            $j += $data->jumlah;
                                            $h += $data->jumlah_harga;
                                        }
                                        $jumlah_detail = count($detail);
                                        $sisa = $jumlah_detail - 1;
                                    @endphp
                                    <div class="col-lg-2 col-4 d-flex justify-content-center align-items-center text-center mx-auto mb-2">
                                        <img src="/product-images/{{$detail_pes->gambar_produk}}" alt="" class="img-fluid" style="max-height: 150px; object-fit: contain;">
                                    </div>
                                    @if ($pes->status == 'Menunggu')
                                        <div class="col-lg-4" style="place-self: center">
                                            <div class="row">
                                                <div class="col-12 d-flex flex-column align-items-center align-items-lg-start text-center text-lg-start">
                                                    <span class="pr-3"
                                                        style="color: #00337C;font-weight:bold">{{ $pes->kode }}</span>
                                                    {{ $pes->tanggal }}
                                                </div>

                                                <div class="col-12 d-flex flex-column align-items-center align-items-lg-start text-center text-lg-start">
                                                <div>
                                                    <span class="text-dark me-2">{{ $detail_pes->nama_produk }}</span>
                                                    @if ($sisa != 0)
                                                        <small>
                                                            <a href="/detail-pesanan/{{ $pes->kode }}" class="p-1 text-white bg-secondary rounded">
                                                                +{{ $sisa }} lainnya
                                                            </a>
                                                        </small>
                                                    @endif
                                                </div>

                                                <div>
                                                    <span class="text-dark">Jumlah Produk : {{ $j }}</span>
                                                </div>

                                                <div>
                                                    <span class="text-dark">Nama Pemesan : {{ $pes->name }}</span>
                                                </div>
                                            </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-2" style="place-self: center; text-align:center">
                                            <input type="hidden" name="kodePes" id="kodePes"
                                                value="{{ $pes->kode }}">
                                            <button data-toggle="modal" data-target=".cancelPesanan"
                                                class="btn btn-danger">Batalkan Pesanan</button>
                                        </div>
                                        <div class="modal fade cancelPesanan" id="defaultModal" tabindex="-1"
                                            role="dialog" aria-labelledby="defaultModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-md" role="document">
                                                <div class="modal-content">
                                                    <form action="/pembatalan/pesanan/{{ $pes->kode }}"
                                                        method="POST" name="form-cancel" id="form-cancel">
                                                        @csrf
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="defaultModalLabel">Konfirmasi
                                                                Pembatalan
                                                                Pesanan
                                                            </h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="col-lg-12">
                                                                <div class="form-group mb-3">
                                                                    <label for="alasan">Alasan Pembatalan</label>
                                                                    <input type="text" id="alasan" name="alasan"
                                                                        class="form-control">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn mb-2 btn-secondary"
                                                                data-dismiss="modal">Tutup</button>
                                                            <button type="button" onclick="canPes()"
                                                                class="btn mb-2 btn-danger">Batalkan Pesanan</button>
                                                        </div>
                                                        <script>
                                                            function canPes() {
                                                                Swal.fire({
                                                                    title: 'Konfirmasi',
                                                                    text: 'Apakah Anda yakin ingin membatalkan pesanan ini?',
                                                                    icon: 'warning',
                                                                    showCancelButton: true,
                                                                    confirmButtonText: 'Ya',
                                                                    cancelButtonText: 'Batal',
                                                                }).then((result) => {
                                                                    if (result.isConfirmed) {
                                                                        document.getElementById('form-cancel').submit();
                                                                    }
                                                                });
                                                            }
                                                        </script>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <script>
                                            function batalkanPesanan() {
                                                var kode = $('#kodePes').val();
                                                Swal.fire({
                                                    title: 'Konfirmasi',
                                                    text: 'Apakah Anda yakin ingin membatalkan pesanan ini?',
                                                    icon: 'warning',
                                                    showCancelButton: true,
                                                    confirmButtonText: 'Ya',
                                                    cancelButtonText: 'Batal',
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        window.location.href = '/batalkan/pesanan/' + kode;
                                                    }
                                                });
                                            }
                                        </script>
                                    @else
                                        <div class="col-lg-6" style="place-self: center">
                                            <div class="row">
                                                <div class="col-12 d-flex flex-column align-items-center align-items-lg-start text-center text-lg-start">
                                                    <span class="pr-3"
                                                        style="color: #00337C;font-weight:bold">{{ $pes->kode }}</span>
                                                    {{ $pes->tanggal }}
                                                </div>
                                                @php
                                                    $detail_pes = DetailPesanan::join(
                                                        'produk',
                                                        'produk.id_produk',
                                                        '=',
                                                        'pesanandetails.produk_id',
                                                    )
                                                        ->where('pesanan_id', $pes->id)
                                                        ->first();
                                                    $detail = DetailPesanan::where('pesanan_id', $pes->id)->get();
                                                    $j = 0;
                                                    $h = 0;
                                                    foreach ($detail as $data) {
                                                        $j += $data->jumlah;
                                                        $h += $data->jumlah_harga;
                                                    }
                                                    $jumlah_detail = count($detail);
                                                    $sisa = $jumlah_detail - 1;
                                                @endphp
                                                <div class="col-12 d-flex flex-column align-items-center align-items-lg-start text-center text-lg-start">
                                                <div>
                                                    <span class="text-dark me-2">{{ $detail_pes->nama_produk }}</span>
                                                    @if ($sisa != 0)
                                                        <small>
                                                            <a href="/detail-pesanan/{{ $pes->kode }}" class="p-1 text-white bg-secondary rounded">
                                                                +{{ $sisa }} lainnya
                                                            </a>
                                                        </small>
                                                    @endif
                                                </div>

                                                <div>
                                                    <span class="text-dark">Jumlah Produk : {{ $j }}</span>
                                                </div>

                                                <div>
                                                    <span class="text-dark">Nama Pemesan : {{ $pes->name }}</span>
                                                </div>
                                            </div>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-lg-4" style="place-self: center">
                                        <div class="row" style="place-content: center;text-align-last:center">
                                            @if ($pes->status == 'Menunggu')
                                                <div class="col-lg-12 pt-1 pb-1 pl-3 pr-3 bg-warning text-white"
                                                    style="border-radius: 5px; max-width:max-content;font-weight:bold">
                                                    {{ $pes->status }}
                                                </div>
                                            @endif
                                            @if ($pes->status == 'Diproses')
                                                <div class="col-lg-12 pt-1 pb-1 pl-3 pr-3 bg-info text-white"
                                                    style="border-radius: 5px; max-width:max-content;font-weight:bold">
                                                    {{ $pes->status }}
                                                </div>
                                            @endif
                                            @if ($pes->status == 'Selesai')
                                                <div class="col-lg-12 pt-1 pb-1 pl-3 pr-3 bg-success text-white"
                                                    style="border-radius: 5px; max-width:max-content;font-weight:bold">
                                                    {{ $pes->status }}
                                                </div>
                                            @endif
                                            @if ($pes->status == 'Dibatalkan')
                                                <div class="col-lg-12 pt-1 pb-1 pl-3 pr-3 bg-danger text-white"
                                                    style="border-radius: 5px; max-width:max-content;font-weight:bold">
                                                    {{ $pes->status }}
                                                </div>
                                            @endif

                                            <div class="col-lg-12 pt-5">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <h5 class="text-dark">Total Belanja</h5>
                                                    </div>
                                                    <div class="col-12">
                                                        <span class="text-dark" style="font-weight: bold">Rp.
                                                            <?php
                                                            echo number_format($h, 0, ',', '.');
                                                            ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 -4"
                                style="background-color: #00337C !important; border-bottom-right-radius:5px;border-top-right-radius:5px; display: flex; justify-content: center; align-items: center;">
                                <a href="/detail-pesanan/{{ $pes->kode }}"
                                    style="color: #fff;display: flex; justify-content: center; align-items: center;">
                                    <span class="text-white">Lihat Detail Pesanan</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div id="dibatalkan" class="tab-pane fade">
                @foreach ($pesanan_dibatalkan as $pes)
                    <div class="container pb-2">
                        <div class="row" style="border: solid 1px; border-radius: 5px">
                            <div class="col-lg-10 p-4">
                                <div class="row">
                                    @php
                                        $detail_pes = DetailPesanan::join(
                                            'produk',
                                            'produk.id_produk',
                                            '=',
                                            'pesanandetails.produk_id',
                                        )
                                            ->where('pesanan_id', $pes->id)
                                            ->first();
                                        $detail = DetailPesanan::where('pesanan_id', $pes->id)->get();
                                        $j = 0;
                                        $h = 0;
                                        foreach ($detail as $data) {
                                            $j += $data->jumlah;
                                            $h += $data->jumlah_harga;
                                        }
                                        $jumlah_detail = count($detail);
                                        $sisa = $jumlah_detail - 1;
                                    @endphp
                                    <div class="col-lg-2 col-4 d-flex justify-content-center align-items-center text-center mx-auto mb-2">
                                        <img src="/product-images/{{$detail_pes->gambar_produk}}" alt="" class="img-fluid" style="max-height: 150px; object-fit: contain;">
                                    </div>
                                    @if ($pes->status == 'Menunggu')
                                        <div class="col-lg-4" style="place-self: center">
                                            <div class="row">
                                                <div class="col-12 d-flex flex-column align-items-center align-items-lg-start text-center text-lg-start">
                                                    <span class="pr-3"
                                                        style="color: #00337C;font-weight:bold">{{ $pes->kode }}</span>
                                                    {{ $pes->tanggal }}
                                                </div>

                                                <div class="col-12 d-flex flex-column align-items-center align-items-lg-start text-center text-lg-start">
                                                <div>
                                                    <span class="text-dark me-2">{{ $detail_pes->nama_produk }}</span>
                                                    @if ($sisa != 0)
                                                        <small>
                                                            <a href="/detail-pesanan/{{ $pes->kode }}" class="p-1 text-white bg-secondary rounded">
                                                                +{{ $sisa }} lainnya
                                                            </a>
                                                        </small>
                                                    @endif
                                                </div>

                                                <div>
                                                    <span class="text-dark">Jumlah Produk : {{ $j }}</span>
                                                </div>

                                                <div>
                                                    <span class="text-dark">Nama Pemesan : {{ $pes->name }}</span>
                                                </div>
                                            </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-2" style="place-self: center; text-align:center">
                                            <input type="hidden" name="kodePes" id="kodePes"
                                                value="{{ $pes->kode }}">
                                            <button data-toggle="modal" data-target=".cancelPesanan"
                                                class="btn btn-danger">Batalkan Pesanan</button>
                                        </div>
                                        <div class="modal fade cancelPesanan" id="defaultModal" tabindex="-1"
                                            role="dialog" aria-labelledby="defaultModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-md" role="document">
                                                <div class="modal-content">
                                                    <form action="/pembatalan/pesanan/{{ $pes->kode }}"
                                                        method="POST" name="form-cancel" id="form-cancel">
                                                        @csrf
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="defaultModalLabel">Konfirmasi
                                                                Pembatalan
                                                                Pesanan
                                                            </h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="col-lg-12">
                                                                <div class="form-group mb-3">
                                                                    <label for="alasan">Alasan Pembatalan</label>
                                                                    <input type="text" id="alasan" name="alasan"
                                                                        class="form-control">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn mb-2 btn-secondary"
                                                                data-dismiss="modal">Tutup</button>
                                                            <button type="button" onclick="canPes()"
                                                                class="btn mb-2 btn-danger">Batalkan Pesanan</button>
                                                        </div>
                                                        <script>
                                                            function canPes() {
                                                                Swal.fire({
                                                                    title: 'Konfirmasi',
                                                                    text: 'Apakah Anda yakin ingin membatalkan pesanan ini?',
                                                                    icon: 'warning',
                                                                    showCancelButton: true,
                                                                    confirmButtonText: 'Ya',
                                                                    cancelButtonText: 'Batal',
                                                                }).then((result) => {
                                                                    if (result.isConfirmed) {
                                                                        document.getElementById('form-cancel').submit();
                                                                    }
                                                                });
                                                            }
                                                        </script>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <script>
                                            function batalkanPesanan() {
                                                var kode = $('#kodePes').val();
                                                Swal.fire({
                                                    title: 'Konfirmasi',
                                                    text: 'Apakah Anda yakin ingin membatalkan pesanan ini?',
                                                    icon: 'warning',
                                                    showCancelButton: true,
                                                    confirmButtonText: 'Ya',
                                                    cancelButtonText: 'Batal',
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        window.location.href = '/batalkan/pesanan/' + kode;
                                                    }
                                                });
                                            }
                                        </script>
                                    @else
                                        <div class="col-lg-6" style="place-self: center">
                                            <div class="row">
                                                <div class="col-12 d-flex flex-column align-items-center align-items-lg-start text-center text-lg-start">
                                                    <span class="pr-3"
                                                        style="color: #00337C;font-weight:bold">{{ $pes->kode }}</span>
                                                    {{ $pes->tanggal }}
                                                </div>
                                                @php
                                                    $detail_pes = DetailPesanan::join(
                                                        'produk',
                                                        'produk.id_produk',
                                                        '=',
                                                        'pesanandetails.produk_id',
                                                    )
                                                        ->where('pesanan_id', $pes->id)
                                                        ->first();
                                                    $detail = DetailPesanan::where('pesanan_id', $pes->id)->get();
                                                    $j = 0;
                                                    $h = 0;
                                                    foreach ($detail as $data) {
                                                        $j += $data->jumlah;
                                                        $h += $data->jumlah_harga;
                                                    }
                                                    $jumlah_detail = count($detail);
                                                    $sisa = $jumlah_detail - 1;
                                                @endphp
                                                <div class="col-12 d-flex flex-column align-items-center align-items-lg-start text-center text-lg-start">
                                                <div>
                                                    <span class="text-dark me-2">{{ $detail_pes->nama_produk }}</span>
                                                    @if ($sisa != 0)
                                                        <small>
                                                            <a href="/detail-pesanan/{{ $pes->kode }}" class="p-1 text-white bg-secondary rounded">
                                                                +{{ $sisa }} lainnya
                                                            </a>
                                                        </small>
                                                    @endif
                                                </div>

                                                <div>
                                                    <span class="text-dark">Jumlah Produk : {{ $j }}</span>
                                                </div>

                                                <div>
                                                    <span class="text-dark">Nama Pemesan : {{ $pes->name }}</span>
                                                </div>
                                            </div>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-lg-4" style="place-self: center">
                                        <div class="row" style="place-content: center;text-align-last:center">
                                            @if ($pes->status == 'Menunggu')
                                                <div class="col-lg-12 pt-1 pb-1 pl-3 pr-3 bg-warning text-white"
                                                    style="border-radius: 5px; max-width:max-content;font-weight:bold">
                                                    {{ $pes->status }}
                                                </div>
                                            @endif
                                            @if ($pes->status == 'Diproses')
                                                <div class="col-lg-12 pt-1 pb-1 pl-3 pr-3 bg-info text-white"
                                                    style="border-radius: 5px; max-width:max-content;font-weight:bold">
                                                    {{ $pes->status }}
                                                </div>
                                            @endif
                                            @if ($pes->status == 'Selesai')
                                                <div class="col-lg-12 pt-1 pb-1 pl-3 pr-3 bg-success text-white"
                                                    style="border-radius: 5px; max-width:max-content;font-weight:bold">
                                                    {{ $pes->status }}
                                                </div>
                                            @endif
                                            @if ($pes->status == 'Dibatalkan')
                                                <div class="col-lg-12 pt-1 pb-1 pl-3 pr-3 bg-danger text-white"
                                                    style="border-radius: 5px; max-width:max-content;font-weight:bold">
                                                    {{ $pes->status }}
                                                </div>
                                            @endif

                                            <div class="col-lg-12 pt-5">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <h5 class="text-dark">Total Belanja</h5>
                                                    </div>
                                                    <div class="col-12">
                                                        <span class="text-dark" style="font-weight: bold">Rp.
                                                            <?php
                                                            echo number_format($h, 0, ',', '.');
                                                            ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 -4"
                                style="background-color: #00337C !important; border-bottom-right-radius:5px;border-top-right-radius:5px; display: flex; justify-content: center; align-items: center;">
                                <a href="/detail-pesanan/{{ $pes->kode }}"
                                    style="color: #fff;display: flex; justify-content: center; align-items: center;">
                                    <span class="text-white">Lihat Detail Pesanan</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

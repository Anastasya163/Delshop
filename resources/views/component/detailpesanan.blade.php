@include('navs')
<div class="site-section">
    <div class="container px-3 px-md-4 px-lg-5">
        <div class="row mb-4">
            <div class="col-12">
                {{-- Navigasi dan Aksi --}}
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <a href="/riwayat-pesanan" class="btn btn-secondary mb-2 mt-n3" style="border:solid 2px; border-color: black">
                        <i class="fa-solid fa-angle-left"></i>
                        <span class="d-none d-sm-inline"> Kembali ke Pesanan Saya</span>
                    </a>
                    @if ($pesanan->status == 'Menunggu')
                        <input type="hidden" name="kodePes" id="kodePes" value="{{ $pesanan->kode }}">
                        <button data-toggle="modal" data-target=".cancelPesanan" class="btn btn-danger mb-2">Batalkan Pesanan</button>
                    @endif
                </div>

                {{-- Status --}}
                @if ($pesanan->status == 'Menunggu' || $pesanan->status == 'Diproses')
                    <div class="col-12 p-3 mb-3 rounded {{ $pesanan->status == 'Menunggu' ? 'bg-warning' : 'bg-info' }}">
                        <div class="d-flex flex-column flex-md-row">
                            <div class="pr-md-3 mb-2 mb-md-0 text-center text-md-left">
                                @if ($pesanan->status == 'Menunggu')
                                    <i class="fa-solid fa-stopwatch fa-2x text-dark"></i>
                                @else
                                    <i class="fa-solid fa-spinner fa-2x text-white"></i>
                                @endif
                            </div>
                            <div>
                                <h5 class="text-light mb-1">
                                    {{ $pesanan->status == 'Menunggu' ? 'Menunggu Pesanan Dikonfirmasi' : 'Pesanan Sedang Diproses' }}
                                </h5>
                                <h6 class="text-light mb-0">
                                    {{ $pesanan->status == 'Menunggu' ? 'Pesanan dalam antrian konfirmasi, mohon menunggu.' : 'Pesanan sedang diproses oleh Petugas, mohon menunggu.' }}
                                </h6>
                                <h6 class="text-light">Terima kasih!</h6>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Info Pesanan --}}
                <div class="row mb-3">
                    <div class="col-md-6 mb-3">
                        <div class="border p-3 rounded">
                            <h5 class="text-dark">ID Pesanan</h5>
                            <h6 class="text-dark">{{ $pesanan->kode }}</h6>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="border p-3 rounded">
                            <h5 class="text-dark">Tanggal Pesanan</h5>
                            <h6 class="text-dark">{{ $pesanan->tanggal }}</h6>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6 mb-3">
                        <div class="border p-3 rounded">
                            <h5 class="text-dark">Penerima</h5>
                            <h6 class="text-dark">{{ $pesanan->nama_pengambil }}</h6>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="border p-3 rounded">
                            <h5 class="text-dark">Pemesan</h5>
                            <h6 class="text-dark">{{ $pesanan->nama_pengambil }}, +62 812 3456 7890, Mahasiswa</h6>
                        </div>
                    </div>
                </div>

                {{-- Produk --}}
                @foreach ($detail_pes as $det)
                <div class="border p-3 rounded custom-card">
                    <div class="row">
                        <div class="col-md-3 col-12 text-center mb-3 mb-md-0 d-flex align-items-center justify-content-center">
                            <img src="/product-images/{{ $det->gambar_produk }}" alt="" class="img-fluid" style="max-height: 100px;">
                        </div>
                        <div class="col-md-6 col-12 d-flex flex-column justify-content-center">
                            <strong class="text-dark">{{ $det->nama_produk }}</strong>
                            <span class="text-dark">Variasi:</span>
                            <span class="text-dark">Jumlah Produk: {{ $det->jumlah }}</span>
                        </div>
                        <div class="col-md-3 col-12 d-flex flex-column justify-content-center align-items-md-end align-items-start text-dark">
                            <span><strong>Subtotal</strong></span>
                            <span>Rp. {{ number_format($det->jumlah_harga, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@if ($pesanan->status == 'Menunggu')
    {{-- Modal Batalkan Pesanan --}}
    <div class="modal fade cancelPesanan" id="defaultModal" tabindex="-1" role="dialog" aria-labelledby="defaultModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable" role="document" style="z-index: 1000">
            <div class="modal-content">
                <form action="/pembatalan/pesanan/{{ $pesanan->kode }}" method="POST" name="form-cancel" id="form-cancel">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="defaultModalLabel">Konfirmasi Pembatalan Pesanan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="alasan">Alasan Pembatalan</label>
                            <input type="text" id="alasan" name="alasan" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="button" onclick="batalkanPesanan()" class="btn btn-danger">Batalkan Pesanan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function batalkanPesanan() {
            const kode = document.getElementById('kodePes').value;
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
@endif



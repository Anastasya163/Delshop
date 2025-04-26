<link rel="stylesheet" type="text/css"
    href="https://cdn.datatables.net/v/bs4/dt-1.10.18/r-2.2.2/sc-2.0.0/datatables.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

{{-- <a href="/tambah/produk" class="btn btn-primary"> <i class="fa-solid fa-circle-plus pr-2"></i> Tambah Produk</a> --}}
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
<div class="row my-4">
    <!-- Small table -->
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-body">
                <!-- table -->
                <table class="table datatables" id="dataTable-1">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>ID Pesanan</th>
                            <th>Tanggal Pesanan</th>
                            <th>Harga Total (Rp)</th>
                            <th>Nama Pemesan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $no = 1;
                        @endphp
                        @foreach ($pesanan as $pes)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $pes->kode }}</td>
                                <td>{{ $pes->tanggal }}</td>
                                <td><?php
                                $angka = $pes->total_harga;
                                echo number_format($angka, 0, ',', '.');
                                ?></td>
                                <td>{{ $pes->name }}</td>
                                <td style="text-align: center"><span class="badge badge-lg bg-warning text-white"
                                        style="width: 100%; height: 100%;">{{ $pes->status }}</span></td>
                                <td><button class="btn btn-sm dropdown-toggle more-horizontal" type="button"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="text-muted sr-only">Action</span>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <button onclick="konfPes({{$pes->id}})" class="dropdown-item">Konfirmasi</button>
                                        <input type="hidden" name="idPes" id="idPes" value="{{ $pes->id }}">
                                        <script>
                                            function konfPes(code) {
                                                // var kode = document.getElementById('idPes').value;
                                                console.log(code);

                                                Swal.fire({
                                                    title: 'Konfirmasi',
                                                    text: 'Apakah Anda yakin ingin mengkonfirmasi pesanan ini?',
                                                    icon: 'warning',
                                                    showCancelButton: true,
                                                    confirmButtonText: 'Ya',
                                                    cancelButtonText: 'Batal',
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        window.location.href = '/proses/konfirmasi/pesanan/' + code;
                                                    }
                                                });
                                            }
                                        </script>
                                        <button class="dropdown-item" href="#" data-toggle="modal"
                                            data-target=".cancelPesanan">Batalkan</button>

                                        <button class="dropdown-item" href="#" data-toggle="modal"
                                            data-target=".modal-detail-{{ $pes->kode }}">Detail
                                            Pesanan</button>
                                </td>
                            </tr>


                            <div class="modal fade cancelPesanan" id="defaultModal" tabindex="-1" role="dialog"
                                aria-labelledby="defaultModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-md" role="document">
                                    <div class="modal-content">
                                        <form action="/pembatalan/pesanan/{{ $pes->kode }}" method="POST"
                                            name="form-cancel" id="form-cancel">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="defaultModalLabel">Konfirmasi Pembatalan
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

                            <div class="modal fade modal-detail-{{ $pes->kode }} modal-slide" tabindex="-1"
                                role="dialog" aria-labelledby="defaultModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-md" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="defaultModalLabel">Detail Pesanan</h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <div class="row">
                                                <div class="col-lg-12 pt-3 pb-2" style="text-align: -webkit-right">
                                                    <div class="col-lg-4">
                                                        <h4><span class="badge badge-lg bg-warning text-white"
                                                                style="width: 100%; height: 100%;">{{ $pes->status }}</span>
                                                        </h4>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 pt-3">
                                                    <div class="row">
                                                        <div class="col-5">
                                                            <h5>ID Pesanan</h5>
                                                        </div>
                                                        <div class="col-1">
                                                            <h5> : </h5>
                                                        </div>
                                                        <div class="col-6">
                                                            <h5 class="text-secondary">{{ $pes->kode }}</h5>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 pt-3">
                                                    <div class="row">
                                                        <div class="col-5">
                                                            <h5>Nama Pemesan</h5>
                                                        </div>
                                                        <div class="col-1">
                                                            <h5> : </h5>
                                                        </div>
                                                        <div class="col-6">
                                                            <h5 class="text-secondary">{{ $pes->name }}</h5>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 pt-3">
                                                    <div class="row">
                                                        <div class="col-5">
                                                            <h5>Tanggal Pesanan</h5>
                                                        </div>
                                                        <div class="col-1">
                                                            <h5> : </h5>
                                                        </div>
                                                        <div class="col-6">
                                                            <h5 class="text-secondary">{{ $pes->tanggal }}</h5>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 pt-3">
                                                    <div class="row">
                                                        <div class="col-5">
                                                            <h5>Total Harga Pesanan</h5>
                                                        </div>
                                                        <div class="col-1">
                                                            <h5> : </h5>
                                                        </div>
                                                        <div class="col-6">
                                                            <h5 class="text-secondary"> Rp. <?php
                                                            $angka = $pes->total_harga;
                                                            echo number_format($angka, 0, ',', '.');
                                                            ?></h5>
                                                        </div>
                                                    </div>
                                                </div>
                                                @php
                                                    $detail_pesanan = App\Models\DetailPesanan::join(
                                                        'produk',
                                                        'produk.id_produk',
                                                        '=',
                                                        'pesanandetails.produk_id',
                                                    )
                                                        ->select(
                                                            'pesanandetails.*',
                                                            'produk.nama_produk',
                                                            'produk.gambar_produk',
                                                        )
                                                        ->where('pesanandetails.pesanan_id', $pes->id)
                                                        ->get();
                                                    $num = 1;
                                                @endphp
                                                <div class="col-lg-12 pt-4">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <h5>Detail Produk Pesanan</h5>
                                                            <div class="col-lg-12"
                                                                style="overflow-y: auto; max-height: 230px;">
                                                                @foreach ($detail_pesanan as $det)
                                                                    <div class="col-lg-12 pt-2 pb-2">
                                                                        <div class="row">
                                                                            <div class="col-lg-2">
                                                                                <small>{{ $num++ }}. </small>
                                                                            </div>
                                                                            <div class="col-lg-2"
                                                                                style="align-self: center">
                                                                                <img src="/product-images/{{ $det->gambar_produk }}"
                                                                                    alt=""
                                                                                    class="w-100 img-fluid"
                                                                                    style="max-height: 100px; max-width: 100px">
                                                                            </div>
                                                                            <div class="col-lg-8">
                                                                                <div class="row">
                                                                                    <div class="col-lg-12">
                                                                                        @php
                                                                                            $varPesanan = json_decode(
                                                                                                $det->variasi_pes,
                                                                                                true,
                                                                                            );
                                                                                            $j = 0;
                                                                                        @endphp
                                                                                        <div class="col-lg-12">
                                                                                            <div class="row">
                                                                                                <small
                                                                                                    style="font-weight: bold">{{ $det->nama_produk }}</small><br>
                                                                                                    @if ($varPesanan != "")
                                                                                                    @for ($i = 0; $i < count($varPesanan); $i++)
                                                                                                        @if (isset($varPesanan[$i][1]))  <!-- Cek apakah indeks 1 ada -->
                                                                                                            <small>{{ $varPesanan[$i][1] }}</small>
                                                                                                        @else
                                                                                                            <small>-</small>  <!-- Tampilkan tanda '-' jika tidak ada -->
                                                                                                        @endif
                                                                                                        @if ($i < count($varPesanan) - 1)
                                                                                                            ,
                                                                                                        @endif
                                                                                                    @endfor
                                                                                                @else
                                                                                                    <small>-</small>
                                                                                                @endif
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-lg-12">
                                                                                        <div class="row">
                                                                                            <div class="col-lg-6">
                                                                                                <small
                                                                                                    style="font-weight: bold">Jumlah
                                                                                                    Produk</small>
                                                                                            </div>
                                                                                            <div class="col-lg-5">
                                                                                                <small>{{ $det->jumlah }}</small>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-lg-12">
                                                                                        <div class="row">
                                                                                            <div class="col-lg-6">
                                                                                                <small
                                                                                                    style="font-weight: bold">Subtotal
                                                                                                    Harga</small>
                                                                                            </div>
                                                                                            <div class="col-lg-5">
                                                                                                <small>Rp.
                                                                                                    <?php
                                                                                                    $angka = $det->jumlah_harga;
                                                                                                    echo number_format($angka, 0, ',', '.');
                                                                                                    ?></small>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn mb-2 btn-secondary"
                                                data-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div> <!-- simple table -->
</div>
<script src="http://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
    crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
    integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.18/r-2.2.2/sc-2.0.0/datatables.min.js">
</script>

<script>
    var table = $('#dataTable-1').DataTable({
        "pageLength": 5,
        "lengthMenu": [5, 10, 25, 50, 100],
        "language": {
            "lengthMenu": "Menampilkan _MENU_ Data per halaman",
            "zeroRecords": "Maaf, tidak dapat menemukan apapun",
            "info": "Menampilkan halaman _PAGE_ dari _PAGES_ halaman",
            "infoEmpty": "Tidak ada data yang dapat ditampilkan",
            "infoFiltered": "(dari _MAX_ total data)",
            "search": "Cari :",
            "paginate": {
                "first": "Pertama",
                "last": "Terakhir",
                "next": "",
                "previous": ""
            },
            "dom": 'lrtip',
            "columnDefs": [{
                    type: 'date',
                    targets: 5
                } // Sesuaikan dengan indeks kolom tanggal Anda
            ],
        },

    });
</script>

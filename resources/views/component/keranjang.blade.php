@include('navs')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<style>
    .custom-control-input:checked~.custom-control-label::before {
        background-color: #007bff;
        border-color: #007bff;
    }

    .custom-control-input:focus~.custom-control-label::before {
        box-shadow: 0 0 0 1px #007bff, 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .custom-control-input:hover:not(:disabled):not(:checked)~.custom-control-label::before {
        border-color: #007bff;
    }

    .custom-checkbox .custom-control-label::before {
        border-radius: 0.25rem;
        background-color: white;
        border: solid 1px;
    }
</style>

@if (session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Tidak Berhasil',
        text: '{{ session('error') }}',
    });
</script>
@endif
@if (session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: '{{ session('success') }}',
    });
</script>
@endif

<form action="/proses/checkout" method="post">
    @csrf
    <div class="site-section">
        <div class="container px-3 px-md-5">
            <div class="row mb-5">
                <div class="col-lg-8">
                    <div class="mb-3 p-3 border rounded">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="checkAll">
                            <label class="custom-control-label font-weight-bold" for="checkAll">Pilih semua</label>
                        </div>
                    </div>

                    @foreach ($detail_keranjang as $index => $detail)
                        <div class="mb-4 border rounded p-3">
                            <div class="row align-items-center">
                                {{-- Checkbox --}}
                                <div class="col-2 col-md-1 text-center">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="check-{{ $index }}" name="selected_items[]" value="{{ $detail->id }}">
                                        <label class="custom-control-label" for="check-{{ $index }}"></label>
                                    </div>
                                </div>

                                {{-- Gambar + Info Produk --}}
                                <div class="col-12 col-md-7 mb-3 mb-md-0 d-flex flex-column flex-md-row align-items-center text-center text-md-left">
                                    <div class="mb-2 mb-md-0 me-md-3" style="width: 100px;">
                                        <img src="/product-images/{{ $detail->gambar_produk }}" alt="" class="img-fluid rounded">
                                    </div>
                                    <div>
                                        <div class="text-dark font-weight-bold">{{ $detail->nama_produk }}</div>
                                        <div class="text-muted">Variasi:
                                            @if ($detail->variasi_pes)
                                                @foreach (json_decode($detail->variasi_pes, true) as $variasi)
                                                    {{ $variasi[1] }}@if (!$loop->last), @endif
                                                @endforeach
                                            @endif
                                        </div>
                                        <div class="font-weight-bold text-dark" data-harga="{{ $detail->harga }}" id="harga-{{ $detail->id }}">
                                            Rp. {{ number_format($detail->harga, 0, ',', '.') }}
                                        </div>
                                        <div class="text-muted subtotal" id="subtotal-{{ $detail->id }}">
                                            Subtotal: Rp. {{ number_format($detail->jumlah_harga, 0, ',', '.') }}
                                        </div>


                                    </div>
                                </div>

                                {{-- Tombol Hapus --}}
                                <div class="col-2 col-md-1 text-center">
                                    <button type="button" class="btn btn-light" onclick="hapusConfirmation({{ $detail->id }})">
                                        <span class="icon icon-trash"></span>
                                    </button>
                                </div>

                                {{-- Jumlah dengan tombol + dan - --}}
                                <div class="col-8 col-md-3">
                                    <div class="input-group input-group-sm jumlah-wrapper" style="max-width: 120px; margin: auto;">
                                        <div class="input-group-prepend">
                                            <button class="btn btn-outline-secondary btn-decrease" type="button" data-id="{{ $detail->id }}">−</button>
                                        </div>
                                        <input type="number" class="form-control text-center jumlah-input" name="jumlah_pes[{{ $detail->id }}]" value="{{ $detail->jumlah }}" min="1" data-id="{{ $detail->id }}">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary btn-increase" type="button" data-id="{{ $detail->id }}">+</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="col-lg-4">
                    <div class="border rounded p-4" style="min-height: 200px;">
                        <div class="d-flex justify-content-between mb-2">
                            <h5 class="text-dark">Total Produk</h5>
                            <h6 id="totalProduk">0 Produk</h6>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <h5 class="text-dark">Total Harga Produk</h5>
                            <h6 id="totalHarga">Rp. 0</h6>
                        </div>
                        <hr>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-sm">Lanjutkan Pemesanan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function hapusConfirmation(id) {
            Swal.fire({
                title: 'Konfirmasi',
                text: 'Apakah Anda yakin ingin menghapus data ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "/hapus/keranjang/" + id;
                }
            });
        }

        // Tombol plus dan minus
        $(document).on('click', '.btn-decrease', function () {
            let id = $(this).data('id');
            let input = $('input[name="jumlah_pes[' + id + ']"]');
            let current = parseInt(input.val());
            if (current > 1) {
                input.val(current - 1).trigger('change');
            }
        });

        $(document).on('click', '.btn-increase', function () {
            let id = $(this).data('id');
            let input = $('input[name="jumlah_pes[' + id + ']"]');
            let current = parseInt(input.val());
            input.val(current + 1).trigger('change');
        });


        $(document).ready(function () {
            $('#checkAll').click(function () {
                $('input[name="selected_items[]"]').prop('checked', this.checked);
                updateTotal();
            });

            $(document).on('change', 'input[name="selected_items[]"], .jumlah-input', function () {
                updateTotal();
            });

            function updateTotal() {
            let totalItems = 0;
            let totalPrice = 0;

            $('input[name="selected_items[]"]:checked').each(function () {
                let id = $(this).val();
                let qty = parseInt($('input[name="jumlah_pes[' + id + ']"]').val());
                let harga = parseInt($('#harga-' + id).data('harga'));

                let subtotal = harga * qty;

                // Update subtotal per item
                $('#subtotal-' + id).text('Subtotal: ' + subtotal.toLocaleString('id-ID', {
                    style: 'currency',
                    currency: 'IDR'
                }));

                totalItems += qty;
                totalPrice += subtotal;
            });

            $('#totalProduk').text(totalItems + ' Produk');
            $('#totalHarga').text(totalPrice.toLocaleString('id-ID', {
                style: 'currency',
                currency: 'IDR'
            }));
        }
        });
    </script>
</form>

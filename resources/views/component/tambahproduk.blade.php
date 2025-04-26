<form class="mb-5" action="/proses/tambah/produk" id="form-tambahproduk" method="post" enctype="multipart/form-data">
    @csrf
    <p class="text-muted">Lengkapi form berikut untuk menambahkan data produk!</p>
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="form-group mb-3">
                        <label for="nama_produk">Nama Produk</label>
                        <input type="text" id="nama_produk" name="nama_produk" class="form-control" placeholder="">
                        <p style="color:red" id="error_product_name"></p>
                    </div>
                    <div class="form-group mb-3">
                        <label for="harga_jual">Harga Jual</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp.</span>
                            </div>
                            <input type="number" class="form-control" aria-label="" id="harga_jual" name="harga_jual">
                            <p style="color:red" id="error_price"></p>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="harga_modal">Modal Produk</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp.</span>
                            </div>
                            <input type="number" class="form-control" aria-label="" id="harga_modal"
                                name="harga_modal">
                            <p style="color:red" id="error_modal"></p>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="jumlah_produk">Jumlah Produk</label>
                        <input type="number" id="jumlah_produk" name="jumlah_produk" class="form-control">
                        <p style="color:red" id="error_quantity"></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="form-group mb-3">
                        <label for="kategori_produk">Kategori Produk</label>
                        <select class="form-control" id="kategori_produk" name="kategori_produk">
                            <option disabled selected>Pilih Kategori Produk</option>
                            @foreach ($kategori_produk as $kapro)
                                <option value="{{$kapro->kategori}}">{{$kapro->kategori}}</option>
                            @endforeach
                        </select>
                        <p style="color:red" id="error_kategori"></p>
                    </div>
                    <div class="form-group mb-3">
                        <label for="kategori_pembeli">Kategori Pembeli</label>
                        <select class="form-control" id="kategori_pembeli" name="kategori_pembeli">
                            <option disabled selected>Pilih Kategori Pembeli</option>
                            <option value="Admin">Admin</option>
                            <option value="Dosen/Staff">Dosen/Staff</option>
                            <option value="Mahasiswa">Mahasiswa</option>
                            <option value="Pegawai">Pegawai</option>
                            <option value="Publik">Publik</option>
                        </select>
                        <p style="color:red" id="error_customer"></p>
                    </div>
                    <div class="form-group mb-3">
                        <label for="gambar_produk">Gambar Produk</label>
                        <div class="custom-file">
                            <input type="file" class="form-control-file" id="gambar_produk" name="gambar_produk">
                        </div>
                        <p style="color:red" id="error_image"></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 mb-3" id="tambah-variasi">
            <div class="col-md-12 mb-4">
                <button type="button" class="btn btn-success add-variasi-produk"><i
                        class="fa-solid fa-square-plus fa-xl"></i> Tambah Variasi Produk</button>
            </div>
            <div class="col-md-12">
                <div class="row" id="variasi_produks">

                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-body">
                    <div class="form-group mb-3">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4"></textarea>
                    </div>
                </div> <!-- /.card-body -->
            </div>
        </div>
        <div class="col-md-12 pt-4 pb-5">
            <div class="row" style="justify-content: right">
                <button class="btn btn-success col-md-1" type="button" onclick="tambahConfirmation()">Tambah</button>
                <script>
                    function tambahConfirmation() {
                        let produkName = document.getElementById("nama_produk").value.trim();
                        let errorProductName = document.getElementById("error_product_name");
                        let hargaJual = document.getElementById("harga_jual").value.trim();
                        let errorPrice = document.getElementById("error_price");
                        let hargaModal = document.getElementById("harga_modal").value.trim();
                        let errorModal = document.getElementById("error_modal");
                        let jumlahProduk = document.getElementById("jumlah_produk").value.trim();
                        let errorQuantity = document.getElementById("error_quantity");
                        let kategoriProduk = document.getElementById("kategori_produk").value.trim();
                        let errorKategori = document.getElementById("error_kategori");
                        let kategoriPembeli = document.getElementById("kategori_pembeli").value.trim();
                        let errorCustomer = document.getElementById("error_customer");
                        let gambarProduk = document.getElementById("gambar_produk").value.trim();
                        let errorImage = document.getElementById("error_image");

                        if(produkName === ""){
                            errorProductName.innerHTML = "Nama produk harus diisi";
                            return;
                        }
                        else if(hargaJual === ""){
                            errorPrice.innerHTML = "Harga jual produk harus diisi";
                            return;
                        }
                        else if(hargaModal === ""){
                            errorModal.innerHTML = "Harga modal produk harus diisi";
                            return;
                        }
                        else if(jumlahProduk === ""){
                            errorQuantity.innerHTML = "Jumlah produk harus diisi";
                            return;
                        }
                        else if(kategoriProduk == "Pilih Kategori Produk"){
                            errorKategori.innerHTML = "Pilih Kategori Produk";
                            return;
                        }
                        else if(kategoriPembeli == "Pilih Kategori Pembeli"){
                            errorCustomer.innerHTML = "Pilih Kategori Pembeli";
                            return;
                        }
                        else if(gambarProduk === ""){
                            errorImage.innerHTML = "Masukkan gambar produk";
                            return;
                        }
                        else{
                            Swal.fire({
                            title: 'Konfirmasi',
                            text: 'Apakah Anda yakin ingin menambahkan data ini?',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Ya',
                            cancelButtonText: 'Batal',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    document.getElementById('form-tambahproduk').submit();
                                }
                            });
                        }
                    }
                </script>
                <button class="btn btn-secondary col-md-1 ml-3 mr-3" type="reset">Reset</button>
            </div>
        </div>
    </div>
</form>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('.add-variasi-produk').click(function() {
            var elements = document.querySelectorAll('#variasi_produk');
            var jlhElement = elements.length + 1;
            console.log(elements);
            var newInput = '<div class="col-md-6 mb-3 hapus-variasi"><div class="card shadow">' +
                '<div class="card-body">' +
                '<div class="form-group mb-3" id="variasi">' +
                '<div style="text-align: -webkit-right">' +
                '<button type="button" class="btn btn-danger remove-variasi-produk" style="background-color: transparent; border-color:transparent"><i class="fa-regular fa-circle-xmark fa-2xl text-danger"></i></button>' +
                '</div>' +
                '<div class="form-group mb-3">' +
                '<label for="nama_produk">Nama Variasi Produk</label>' +
                '<input type="text" id="variasi_produk" name="variasi_produk_' + jlhElement +
                '" class="form-control"' +
                'placeholder="">' +
                '</div>' +
                '<div class="form-group mb-3" name="variasiProduk">' +
                '<label for="nama_produk">Jenis Variasi Produk</label>' +
                '<div class="input-group">' +
                '<input type="hidden" id="idVariasi" name="idVariasi" value="' + jlhElement + '">' +
                '<input type="text" id="jenis_variasi" name="jenis_variasi_' + jlhElement +
                '[]" class="form-control"' +
                'placeholder="">' +
                '<button type="button" class="btn btn btn-primary add-variasi"><i ' +
                'class="fa-solid fa-circle-plus fa-xl text-white"></i></button>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '</div></div>';
            $('#variasi_produks').append(newInput);
        });

        $('#variasi_produks').on('click', '.add-variasi', function() {
            var idVariasi = $(this).closest('#variasi').find('#idVariasi').val();
            var newInput =
                '<div class="input-group mt-3 tambahan-variasi"><input type="text" id="jenis_variasi" name="jenis_variasi_' +
                idVariasi +
                '[]" class="form-control" placeholder=""><button type="button" class="btn btn btn-danger remove-variasi"><i class="fa-solid fa-trash fa-xl text-white"></i></button></div>';
            $(this).closest('#variasi').append(newInput);
        });

        $('#variasi_produks').on('click', '.remove-variasi-produk', function() {
            $(this).closest('.hapus-variasi').remove();
        });

        $('#variasi_produks').on('click', '.remove-variasi', function() {
            $(this).closest('.tambahan-variasi').remove();
        });
    });
</script>

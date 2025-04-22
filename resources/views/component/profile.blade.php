@include('navs')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

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

<div class="container py-4">
    @foreach ($pengguna as $data)
    <div class="row">
        <!-- Foto Profil -->
        <div class="col-md-4 text-center mb-4">
            <img src="/user-images/{{ $data->gambar_pengguna }}" alt="Profile" class="rounded-circle img-fluid mb-3"
                 style="width: 200px; height: 200px; border: 5px solid #ccc;" id="uploadPreview">

            <form action="{{ Auth::user()->role_pengguna == 'Admin' ? '/aprofile/update' : '/profile/update' }}"
                  method="POST" enctype="multipart/form-data" id="form_prof">
                @csrf
                <input type="file" class="d-none" name="gambar_pengguna" id="gambar_pengguna" onchange="PreviewImage();">
                <label for="gambar_pengguna" class="btn btn-primary btn-sm">
                    <i class="fa-solid fa-upload"></i> Unggah Foto
                </label>
        </div>

        <!-- Form Profil -->
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body">

                    <div class="mb-3">
                        <label class="form-label">Nama Pengguna</label>
                        <input name="name" type="text" class="form-control" value="{{ $data->name }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input name="email" type="email" class="form-control" value="{{ $data->email }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jenis Kelamin</label>
                        <input name="jenis_kelamin" type="text" class="form-control" value="{{ $data->jenis_kelamin }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Pekerjaan</label>
                        <input name="pekerjaan" type="text" class="form-control" value="{{ $data->pekerjaan }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <input name="alamat" type="text" class="form-control" value="{{ $data->alamat }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">No. Telp</label>
                        <input name="no_telp" type="text" class="form-control" value="{{ $data->no_telp }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tentang</label>
                        <textarea name="tentang" class="form-control" rows="3">{{ $data->tentang }}</textarea>
                    </div>

                    <hr>
                    <h6 class="fw-bold">Ubah Password</h6>

                    <div class="mb-3">
                        <label class="form-label">Password Lama</label>
                        <input name="password_old" type="password" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password Baru</label>
                        <input name="password_new" type="password" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password</label>
                        <input name="kpassword_new" type="password" class="form-control">
                    </div>

                    <div class="text-center">
                        <button type="button" onclick="ubahConfirmation()" class="btn btn-success">
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </div>
        </div>
        </form>
    </div>
    @endforeach
</div>

<script>
    function PreviewImage() {
        var oFReader = new FileReader();
        oFReader.readAsDataURL(document.getElementById("gambar_pengguna").files[0]);
        oFReader.onload = function(oFREvent) {
            document.getElementById("uploadPreview").src = oFREvent.target.result;
        };
    }

    function ubahConfirmation() {
        Swal.fire({
            title: 'Konfirmasi',
            text: 'Apakah Anda yakin ingin mengubah data ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form_prof').submit();
            }
        });
    }
</script>

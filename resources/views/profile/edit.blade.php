{{-- <x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
</h2>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</div>
</x-app-layout> --}}

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard - profile</title>
</head>

<body>
    @include('dashboard::bases.css')
    @include('dashboard::bases.js')

    <div id="global-loader">
        <div class="whirly-loader"> </div>
    </div>

    <div class="main-wrapper">
        @include('dashboard::layouts.header')
        @include('dashboard::layouts.sidebar')

        @if (session()->has('status'))
        <script>
            Swal.fire({
                icon: 'success'
                , title: 'Berhasil!'
                , text: "{{ session('status') }}"
                , confirmButtonText: 'OK'
            });

        </script>
        @endif

        <div class="page-wrapper">
            <div class="content">
                {{-- name & email --}}
                <div class="page-header">
                    <div class="page-title">
                        <h4>Profile Information</h4>
                        <p>Perbarui informasi profil dan alamat email akun Anda.</p>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
                            @csrf
                            @method('patch')
                            <div class="row">
                                <div class="col-lg-3 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label>Nama</label>
                                        <input type="text" value="{{ $user->name }}" name="name" required>
                                    </div>
                                    @error('name')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-lg-3 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="text" value="{{ $user->email }}" name="email" required>
                                    </div>
                                    @error('email')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-lg-12">
                                    <button type="submit" class="btn btn-submit me-2">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- password --}}
                <div class="page-header">
                    <div class="page-title">
                        <h4>Perbarui Password</h4>
                        <p>Pastikan akun Anda menggunakan kata sandi yang panjang dan acak agar tetap aman.</p>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
                            @csrf
                            @method('put')

                            <div class="row">
                                <div class="col-lg-3 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label>Password Lama</label>
                                        <div class="pass-group">
                                            <input type="password" class="pass-input" name="current_password" required>
                                            <span class="fas toggle-password fa-eye-slash"></span>
                                        </div>
                                        @error('current_password')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-3 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label>Password Baru</label>
                                        <div class="pass-group">
                                            <input type="password" class="pass-input" name="password" required>
                                            <span class="fas toggle-password fa-eye-slash"></span>
                                        </div>
                                        @error('password')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-3 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label>Password Konfirmasi</label>
                                        <div class="pass-group">
                                            <input type="password" class="pass-input" name="password_confirmation" required>
                                            <span class="fas toggle-password fa-eye-slash"></span>
                                        </div>
                                        @error('password_confirmation')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <button type="submit" class="btn btn-submit me-2">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- foto profile --}}
                <div class="page-header">
                    <div class="page-title">
                        <h4>Perbarui foto profil</h4>
                        <p>Gunakan foto profile yang jelas.</p>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <form action="/profile/foto" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('patch')
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Foto Profile</label>

                                        <div class="d-flex justify-content-center mb-2 image-preview">
                                            <img src="{{ $dataUserAuth[0]->foto_profile ? $dataUserAuth[0]->foto_profile : 'https://www.its.ac.id/aktuaria/wp-content/uploads/sites/100/2018/03/user.png' }}" alt="preview foto profile" id="image_preview" height="200" width="200" style="display: none;">
                                        </div>

                                        <div class="image-upload">
                                            <input type="file" name="foto_profile" id="foto_profile_input" accept="image/jpeg,image/png" max="3072" required>

                                            <div class="image-uploads">
                                                <img src="{{ asset('assets/dashboard/img/icons/upload.svg') }}" alt="img">
                                                <h4>Drag and drop a file to upload</h4>
                                            </div>
                                        </div>
                                        @error('foto_profile')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <button type="submit" class="btn btn-submit me-2">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const fileInput = document.getElementById("foto_profile_input");
        const imagePreviewContainer = document.querySelector(".image-preview");
        const imagePreview = document.getElementById("image_preview");

        // Sembunyikan pratinjau gambar pada awalnya
        imagePreview.style.display = "none";

        fileInput.addEventListener("change", function() {
            const file = fileInput.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = "block"; // Tampilkan pratinjau
                };
                reader.readAsDataURL(file);

                const allowedTypes = ["image/jpeg", "image/png"];
                const maxSize = 3072 * 1024; // 3 MB in bytes

                if (!allowedTypes.includes(file.type)) {
                    alert("Hanya format jpg dan png yang diizinkan!");
                    imagePreview.style.display = "none"; // Sembunyikan pratinjau
                    fileInput.value = ""; // Atur nilai input menjadi kosong
                    return;
                }

                if (file.size > maxSize) {
                    alert("File harus dibawah ukuran 3MB.");
                    imagePreview.style.display = "none"; // Sembunyikan pratinjau
                    fileInput.value = ""; // Atur nilai input menjadi kosong
                    return;
                }
            } else {
                imagePreview.style.display = "none"; // Sembunyikan pratinjau
            }
        });

        // Jika pratinjau diklik, sembunyikan pratinjau dan atur nilai input menjadi kosong
        imagePreviewContainer.addEventListener("click", function() {
            imagePreview.style.display = "none"; // Sembunyikan pratinjau
            fileInput.value = ""; // Atur nilai input menjadi kosong
        });

        // Jika tag input diklik, atur nilai input menjadi kosong
        fileInput.addEventListener("click", function() {
            fileInput.value = ""; // Atur nilai input menjadi kosong
            imagePreview.style.display = "none";
        });
    });

</script>

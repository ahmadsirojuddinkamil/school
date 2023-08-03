<div class="container border">
    @if (session()->has('success'))
        <div class="alert alert-success d-flex justify-content-center" role="alert">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-warning d-flex justify-content-center" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <form action="/ppdb" method="POST" enctype="multipart/form-data">
        @csrf

        <h4 class="d-flex justify-content-center mt-3">Data Siswa :</h4>

        <div class="mb-3">
            <label for="email-siswa" class="form-label">Email</label>
            <input type="email" class="form-control" id="email-siswa" value="example@gmail.com" name="email"
                required>
        </div>

        <div class="mb-3">
            <label for="nisn-siswa" class="form-label">NISN</label>
            <input type="number" class="form-control" id="nisn-siswa" value="0201034059" name="nisn" required>
        </div>

        <div class="mb-3">
            <label for="nama-siswa" class="form-label">Nama Lengkap</label>
            <input type="text" class="form-control" id="nama-siswa" value="Yudi Amanda" name="nama_lengkap" required>
        </div>

        <div class="mb-3">
            <label for="asal-sekolah-siswa" class="form-label">Asal Sekolah</label>
            <input type="text" class="form-control" id="asal-sekolah-siswa" value="smp negeri 1 semarang"
                name="asal_sekolah" required>
        </div>

        <div class="mb-3">
            <label for="alamat-siswa" class="form-label">Alamat</label>
            <input type="text" class="form-control" id="alamat-siswa" value="jl. kebon jeruk" name="alamat"
                required>
        </div>

        <div class="mb-3">
            <label for="telpon-siswa" class="form-label">No Telp / WhatsApp</label>
            <input type="number" class="form-control" id="telpon-siswa" value="08121060302" name="telpon_siswa"
                required maxlength="12">
        </div>

        <div class="mb-3">
            <label class="form-label">Jenis Kelamin</label>

            <select class="form-select" name="jenis_kelamin" required>
                <option value="laki-laki">Laki - laki</option>
                <option value="perempuan">Perempuan</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="tempat-lahir-siswa" class="form-label">Tempat Lahir</label>
            <input type="text" class="form-control" id="tempat-lahir-siswa" value="Jakarta" name="tempat_lahir"
                required>
        </div>

        <div class="mb-3">
            <label for="tanggal-lahir-siswa" class="form-label">Tanggal Lahir</label>
            <input type="date" class="form-control" id="tanggal-lahir-siswa" name="tanggal_lahir" required
                min="<?php echo $minDate; ?>" max="<?php echo $today->format('Y-m-d'); ?>">
        </div>


        <div class="mb-4">
            <label class="form-label">Bidang Peminatan</label>

            <select class="form-select" name="jurusan" required>
                <option value="teknik komputer jaringan">Teknik komputer jaringan</option>
                <option value="rekayasa perangkat lunak">Rekayasa perangkat lunak</option>
                <option value="multimedia">Multimedia</option>
            </select>
        </div>

        <h4 class="d-flex justify-content-center">Data Orang Tua :</h4>

        <div class="mb-3">
            <label for="nama-ayah" class="form-label">Nama Ayah</label>
            <input type="text" class="form-control" id="nama-ayah" value="Sumandi" name="nama_ayah" required>
        </div>

        <div class="mb-3">
            <label for="nama-ibu" class="form-label">Nama Ibu</label>
            <input type="text" class="form-control" id="nama-ibu" value="Siti" name="nama_ibu" required>
        </div>

        <div class="mb-3">
            <label for="telpon-orang-tua" class="form-label">No Telp / WhatsApp</label>
            <input type="number" class="form-control" id="telpon-orang-tua" value="081232722384"
                name="telpon_orang_tua" required maxlength="12">
        </div>

        <div class="mb-3">
            <label for="uang-pendaftaran" class="form-label">Uang Pendaftaran *100,000Rb transfer ke BCA 5220304312
                A.N RAHARJO</label>
            <div class="text-danger mb-3">Simpan bukti pembayaran!</div>
            <input type="file" class="form-control" id="uang-pendaftaran" accept=".jpg, .jpeg, .png"
                name="bukti_pendaftaran_siswa_baru" required>
        </div>

        <div id="image-preview-container" class=" mb-2">
            <img id="image-preview" src="#" alt="Preview Gambar"
                style="display: none; max-width: 300px; max-height: 300px;">
        </div>

        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#exampleModal">
            Kirim Data
        </button>

        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Apakah data yang di isi sudah benar?</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        Setelah tekan tombol ya, silahkan check email dalam 24 jam!
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
                        <button type="submit" class="btn btn-primary">Ya!</button>
                    </div>
                </div>
            </div>
        </div>

    </form>
</div>

<script>
    const inputElement = document.getElementById('uang-pendaftaran');
    const imagePreviewElement = document.getElementById('image-preview');
    const imagePreviewContainer = document.getElementById('image-preview-container');

    inputElement.addEventListener('change', function() {
        const file = inputElement.files[0];

        if (file) {
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
            const maxFileSizeMB = 3;
            const maxFileSizeBytes = maxFileSizeMB * 1024 * 1024;

            if (allowedTypes.includes(file.type) && file.size <= maxFileSizeBytes) {
                const reader = new FileReader();

                reader.onload = function() {
                    imagePreviewElement.src = reader.result;
                    imagePreviewElement.style.display = 'block';
                }

                reader.readAsDataURL(file);
            } else {
                imagePreviewElement.src = '#';
                imagePreviewElement.style.display = 'none';
                inputElement.value = ''; // Reset nilai input file
                alert('File harus berformat jpg, jpeg, atau png dan ukuran file harus kurang dari 3 MB.');
            }
        } else {
            imagePreviewElement.src = '#';
            imagePreviewElement.style.display = 'none';
        }
    });

    // Untuk menghapus pratinjau gambar saat input file ditekan lagi
    inputElement.addEventListener('click', function() {
        imagePreviewElement.src = '#';
        imagePreviewElement.style.display = 'none';
        inputElement.value = ''; // Reset nilai input file
    });
</script>

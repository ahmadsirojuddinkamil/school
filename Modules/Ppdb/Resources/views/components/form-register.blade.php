<form action="{{ route('ppdb.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <h4 class="d-flex justify-content-center mt-3">Form Data PPDB :</h4>

    <div class="mb-3">
        <label for="email-siswa" class="form-label">Email</label>
        <input type="email" class="form-control" id="email-siswa" placeholder="example@gmail.com" name="email" required>
    </div>
    @error('email')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="nisn-siswa" class="form-label">NISN</label>
        <input type="number" class="form-control" id="nisn-siswa" placeholder="0201034059" name="nisn" required>
    </div>
    @error('nisn')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="nama-siswa" class="form-label">Nama</label>
        <input type="text" class="form-control" id="nama-siswa" placeholder="Yudi Amanda" name="name" required>
    </div>
    @error('name')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="asal-sekolah-siswa" class="form-label">Asal Sekolah</label>
        <input type="text" class="form-control" id="asal-sekolah-siswa" placeholder="smp negeri 1 semarang" name="asal_sekolah" required>
    </div>
    @error('asal_sekolah')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="alamat-siswa" class="form-label">Alamat</label>
        <input type="text" class="form-control" id="alamat-siswa" placeholder="jl. kebon jeruk" name="alamat" required>
    </div>
    @error('alamat')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="telpon-siswa" class="form-label">No Telp / WhatsApp</label>
        <input type="number" class="form-control" id="telpon-siswa" placeholder="08121060302" name="telpon_siswa" required maxlength="12">
    </div>
    @error('telpon_siswa')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label class="form-label">Jenis Kelamin</label>

        <select class="form-select" name="jenis_kelamin" required>
            <option value="laki-laki">Laki - laki</option>
            <option value="perempuan">Perempuan</option>
        </select>
    </div>
    @error('jenis_kelamin')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="tempat-lahir-siswa" class="form-label">Tempat Lahir</label>
        <input type="text" class="form-control" id="tempat-lahir-siswa" placeholder="Jakarta" name="tempat_lahir" required>
    </div>
    @error('tempat_lahir')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="tanggal-lahir-siswa" class="form-label">Tanggal Lahir</label>
        <input type="date" class="form-control" id="tanggal-lahir-siswa" name="tanggal_lahir" required min="<?php echo $timeBox['minDate']; ?>" max="<?php echo $timeBox['todayDate']; ?>">
    </div>
    @error('tanggal_lahir')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="nama-ayah" class="form-label">Nama Ayah</label>
        <input type="text" class="form-control" id="nama-ayah" placeholder="Sumandi" name="nama_ayah" required>
    </div>
    @error('nama_ayah')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="nama-ibu" class="form-label">Nama Ibu</label>
        <input type="text" class="form-control" id="nama-ibu" placeholder="Siti" name="nama_ibu" required>
    </div>
    @error('nama_ibu')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="telpon-orang-tua" class="form-label">No Telp / WhatsApp</label>
        <input type="number" class="form-control" id="telpon-orang-tua" placeholder="081232722384" name="telpon_orang_tua" required maxlength="12">
    </div>
    @error('telpon_orang_tua')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="uang-pendaftaran" class="form-label">Uang Pendaftaran *100,000Rb transfer ke BCA 5220304312
            A.N RAHARJO</label>
        <div class="text-danger mb-3">Simpan bukti pembayaran!</div>
        <input type="file" class="form-control" id="uang-pendaftaran" accept=".jpg, .jpeg, .png" name="bukti_pendaftaran" required>
    </div>
    @error('bukti_pendaftaran')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div id="image-preview-container" class=" mb-2">
        <img id="image-preview" src="#" alt="Preview Gambar" style="display: none; max-width: 300px; max-height: 300px;">
    </div>

    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#exampleModal">
        Kirim Data
    </button>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Apakah data yang di isi sudah benar?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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

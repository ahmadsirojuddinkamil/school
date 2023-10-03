<div class="mb-3">
    <label for="nama-siswa" class="form-label">Nama Lengkap</label>
    <input type="text" class="form-control" id="nama-siswa" value="{{ $getDataSiswa->nama_lengkap }}" name="nama_lengkap"
        required>
</div>
@error('nama_lengkap')
    <div class="alert alert-danger">{{ $message }}</div>
@enderror

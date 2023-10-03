<div class="mb-3">
    <label for="asal-sekolah-siswa" class="form-label">Asal Sekolah</label>
    <input type="text" class="form-control" id="asal-sekolah-siswa" value="{{ $getDataSiswa->asal_sekolah }}"
        name="asal_sekolah" required>
</div>
@error('asal_sekolah')
    <div class="alert alert-danger">{{ $message }}</div>
@enderror

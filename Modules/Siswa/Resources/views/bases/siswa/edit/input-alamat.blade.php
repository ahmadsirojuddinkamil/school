<div class="mb-3">
    <label for="alamat-siswa" class="form-label">Alamat</label>
    <input type="text" class="form-control" id="alamat-siswa" value="{{ $getDataSiswa->alamat }}" name="alamat" required>
</div>
@error('alamat')
    <div class="alert alert-danger">{{ $message }}</div>
@enderror

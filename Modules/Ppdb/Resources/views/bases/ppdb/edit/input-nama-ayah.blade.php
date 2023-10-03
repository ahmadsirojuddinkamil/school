<div class="mb-3">
    <label for="nama-ayah" class="form-label">Nama Ayah</label>
    <input type="text" class="form-control" id="nama-ayah" value="{{ $getDataUserPpdb->nama_ayah }}" name="nama_ayah"
        required>
</div>
@error('nama_ayah')
    <div class="alert alert-danger">{{ $message }}</div>
@enderror

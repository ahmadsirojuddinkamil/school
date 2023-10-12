<div class="mb-3">
    <label for="tahun-daftar" class="form-label">Tahun Daftar</label>
    <input type="number" class="form-control" id="tahun-daftar" value="{{ $getDataUserPpdb->tahun_daftar }}"
        name="tahun_daftar" required maxlength="12">
</div>
@error('tahun_daftar')
    <div class="alert alert-danger">{{ $message }}</div>
@enderror

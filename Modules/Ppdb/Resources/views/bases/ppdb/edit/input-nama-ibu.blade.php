<div class="mb-3">
    <label for="nama-ibu" class="form-label">Nama Ibu</label>
    <input type="text" class="form-control" id="nama-ibu" value="{{ $getPpdb->nama_ibu }}" name="nama_ibu" required>
</div>
@error('nama_ibu')
    <div class="alert alert-danger">{{ $message }}</div>
@enderror

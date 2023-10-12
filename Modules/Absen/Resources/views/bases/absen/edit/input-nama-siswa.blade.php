<div class="mb-3">
    <label for="nama-siswa" class="form-label">Nama Lengkap</label>
    <input type="text" class="form-control" id="nama-siswa" value="{{ $getDataAbsen->name }}" name="name" required>
</div>
@error('name')
<div class="alert alert-danger">{{ $message }}</div>
@enderror

<div class="mb-3">
    <label for="tempat-lahir-siswa" class="form-label">Tempat Lahir</label>
    <input type="text" class="form-control" id="tempat-lahir-siswa" placeholder="Jakarta" name="tempat_lahir" required>
</div>
@error('tempat_lahir')
    <div class="alert alert-danger">{{ $message }}</div>
@enderror

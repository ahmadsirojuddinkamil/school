<div class="mb-3">
    <label for="nisn-siswa" class="form-label">NISN</label>
    <input type="number" class="form-control" id="nisn-siswa" placeholder="0201034059" name="nisn" required>
</div>
@error('nisn')
    <div class="alert alert-danger">{{ $message }}</div>
@enderror

<div class="mb-3">
    <label for="nisn-siswa" class="form-label">NISN</label>
    <input type="number" class="form-control" id="nisn-siswa" value="{{ $getPpdb->nisn }}" name="nisn" required>
</div>
@error('nisn')
    <div class="alert alert-danger">{{ $message }}</div>
@enderror
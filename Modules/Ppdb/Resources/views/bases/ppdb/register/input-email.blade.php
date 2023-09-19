<div class="mb-3">
    <label for="email-siswa" class="form-label">Email</label>
    <input type="email" class="form-control" id="email-siswa" placeholder="example@gmail.com" name="email" required>
</div>
@error('email')
    <div class="alert alert-danger">{{ $message }}</div>
@enderror

<div class="mb-3">
    <label class="form-label">Jenis Kelamin</label>

    <select class="form-select" name="jenis_kelamin" required>
        <option value="laki-laki">Laki - laki</option>
        <option value="perempuan">Perempuan</option>
    </select>
</div>
@error('jenis_kelamin')
    <div class="alert alert-danger">{{ $message }}</div>
@enderror

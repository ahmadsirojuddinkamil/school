<div class="mb-3">
    <label for="telpon-siswa" class="form-label">No Telp / WhatsApp</label>
    <input type="number" class="form-control" id="telpon-siswa" placeholder="08121060302" name="telpon_siswa" required
        maxlength="12">
</div>
@error('telpon_siswa')
    <div class="alert alert-danger">{{ $message }}</div>
@enderror

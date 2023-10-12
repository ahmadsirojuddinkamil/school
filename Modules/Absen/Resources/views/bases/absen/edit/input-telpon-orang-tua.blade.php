<div class="mb-3">
    <label for="telpon-orang-tua" class="form-label">No Telp / WhatsApp</label>
    <input type="number" class="form-control" id="telpon-orang-tua" value="{{ $getDataUserPpdb->telpon_orang_tua }}"
        name="telpon_orang_tua" required maxlength="12">
</div>
@error('telpon_orang_tua')
    <div class="alert alert-danger">{{ $message }}</div>
@enderror

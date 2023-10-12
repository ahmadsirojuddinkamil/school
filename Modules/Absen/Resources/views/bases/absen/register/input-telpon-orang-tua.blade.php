<div class="mb-3">
    <label for="telpon-orang-tua" class="form-label">No Telp / WhatsApp</label>
    <input type="number" class="form-control" id="telpon-orang-tua" placeholder="081232722384" name="telpon_orang_tua"
        required maxlength="12">
</div>
@error('telpon_orang_tua')
    <div class="alert alert-danger">{{ $message }}</div>
@enderror

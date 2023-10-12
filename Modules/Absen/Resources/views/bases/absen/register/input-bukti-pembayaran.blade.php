<div class="mb-3">
    <label for="uang-pendaftaran" class="form-label">Uang Pendaftaran *100,000Rb transfer ke BCA 5220304312
        A.N RAHARJO</label>
    <div class="text-danger mb-3">Simpan bukti pembayaran!</div>
    <input type="file" class="form-control" id="uang-pendaftaran" accept=".jpg, .jpeg, .png" name="bukti_pendaftaran"
        required>
</div>
@error('bukti_pendaftaran')
    <div class="alert alert-danger">{{ $message }}</div>
@enderror

<div id="image-preview-container" class=" mb-2">
    <img id="image-preview" src="#" alt="Preview Gambar"
        style="display: none; max-width: 300px; max-height: 300px;">
</div>

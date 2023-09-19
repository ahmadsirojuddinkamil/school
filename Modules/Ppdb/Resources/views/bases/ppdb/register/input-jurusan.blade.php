<div class="mb-4">
    <label class="form-label">Bidang Peminatan</label>

    <select class="form-select" name="jurusan" required>
        <option value="teknik komputer jaringan">Teknik komputer jaringan</option>
        <option value="rekayasa perangkat lunak">Rekayasa perangkat lunak</option>
        <option value="multimedia">Multimedia</option>
    </select>
</div>
@error('jurusan')
    <div class="alert alert-danger">{{ $message }}</div>
@enderror

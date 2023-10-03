<div class="mb-3">
    @if ($getDataSiswa->tahun_lulus !== null)
        <label for="tahun-lulus" class="form-label">Tahun Lulus</label>
        <input type="number" class="form-control" id="tahun-lulus" value="{{ $getDataSiswa->tahun_lulus }}"
            name="tahun_lulus" required maxlength="12">
    @else
        <input type="hidden" name="tahun_lulus" value="null">
    @endif
</div>
@error('tahun_lulus')
    <div class="alert alert-danger">{{ $message }}</div>
@enderror

<div class="mb-3">
    <label for="tanggal-lahir-siswa" class="form-label">Tanggal Lahir</label>
    <input type="date" class="form-control" id="tanggal-lahir-siswa" name="tanggal_lahir" required
        min="<?php echo $timeBox['minDate']; ?>" max="<?php echo $timeBox['todayDate']; ?>" value="{{ $getDataSiswa->tanggal_lahir ?? '' }}">
</div>
@error('tanggal_lahir')
    <div class="alert alert-danger">{{ $message }}</div>
@enderror

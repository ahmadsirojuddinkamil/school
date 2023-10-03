<div class="mb-4">
    <label class="form-label">Jenis Kelamin</label>
    <select class="form-select" name="jenis_kelamin" required>
        @foreach (['laki-laki', 'perempuan'] as $option)
            <option value="{{ $option }}" {{ $getDataUserPpdb->jenis_kelamin == $option ? 'selected' : '' }}>
                {{ ucfirst($option) }}
            </option>
        @endforeach
    </select>
</div>
@error('jenis_kelamin')
    <div class="alert alert-danger">{{ $message }}</div>
@enderror

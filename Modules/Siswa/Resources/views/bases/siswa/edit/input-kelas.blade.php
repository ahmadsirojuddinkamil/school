@if ($getDataSiswa->kelas !== 'lulus')
    <div class="mb-3">
        <label class="form-label">Kelas</label>
        <select class="form-select" name="kelas" required>
            @foreach ([10, 11, 12] as $option)
                <option value="{{ $option }}" {{ $getDataSiswa->kelas == $option ? 'selected' : '' }}>
                    {{ ucfirst($option) }}
                </option>
            @endforeach
        </select>
    </div>
@else
    <input type="hidden" name="kelas" value="lulus">
@endif

@error('kelas')
    <div class="alert alert-danger">{{ $message }}</div>
@enderror

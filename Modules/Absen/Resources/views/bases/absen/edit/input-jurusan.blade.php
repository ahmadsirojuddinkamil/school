<div class="mb-4">
    <label class="form-label">Bidang Peminatan</label>
    <select class="form-select" name="jurusan" required>
        @foreach (['teknik komputer jaringan', 'rekayasa perangkat lunak', 'multimedia'] as $option)
            <option value="{{ $option }}" {{ $getDataUserPpdb->jurusan == $option ? 'selected' : '' }}>
                {{ ucfirst($option) }}
            </option>
        @endforeach
    </select>
</div>
@error('jurusan')
    <div class="alert alert-danger">{{ $message }}</div>
@enderror

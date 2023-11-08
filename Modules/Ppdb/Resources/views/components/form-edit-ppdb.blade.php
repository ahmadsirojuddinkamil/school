<form action="{{ route('ppdb.update', ['save_uuid_from_event' => $dataPpdb->uuid]) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label for="email-siswa" class="form-label">Email</label>
        <input type="email" class="form-control" id="email-siswa" value="{{ $dataPpdb->email }}" name="email" required>
    </div>
    @error('email')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="nisn-siswa" class="form-label">NISN</label>
        <input type="number" class="form-control" id="nisn-siswa" value="{{ $dataPpdb->nisn }}" name="nisn" required>
    </div>
    @error('nisn')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="nama-siswa" class="form-label">Nama</label>
        <input type="text" class="form-control" id="nama-siswa" value="{{ $dataPpdb->name }}" name="name" required>
    </div>
    @error('name')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="asal-sekolah-siswa" class="form-label">Asal Sekolah Smp / Mts</label>
        <input type="text" class="form-control" id="asal-sekolah-siswa" value="{{ $dataPpdb->asal_sekolah }}" name="asal_sekolah" required>
    </div>
    @error('asal_sekolah')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="alamat-siswa" class="form-label">Alamat</label>
        <input type="text" class="form-control" id="alamat-siswa" value="{{ $dataPpdb->alamat }}" name="alamat" required>
    </div>
    @error('alamat')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="telpon-siswa" class="form-label">No Telp / WhatsApp</label>
        <input type="number" class="form-control" id="telpon-siswa" value="{{ $dataPpdb->telpon_siswa }}" name="telpon_siswa" required maxlength="12">
    </div>
    @error('telpon_siswa')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-4">
        <label class="form-label">Jenis Kelamin</label>
        <select class="form-select" name="jenis_kelamin" required>
            @foreach (['laki-laki', 'perempuan'] as $option)
            <option value="{{ $option }}" {{ $dataPpdb->jenis_kelamin == $option ? 'selected' : '' }}>
                {{ ucfirst($option) }}
            </option>
            @endforeach
        </select>
    </div>
    @error('jenis_kelamin')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="tempat-lahir-siswa" class="form-label">Tempat Lahir</label>
        <input type="text" class="form-control" id="tempat-lahir-siswa" value="{{ $dataPpdb->tempat_lahir }}" name="tempat_lahir" required>
    </div>
    @error('tempat_lahir')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="tanggal-lahir-siswa" class="form-label">Tanggal Lahir</label>
        <input type="date" class="form-control" id="tanggal-lahir-siswa" name="tanggal_lahir" required min="<?php echo $dataPpdb['minDate']; ?>" max="<?php echo $dataPpdb['todayDate']; ?>" value="{{ $dataPpdb->tanggal_lahir ?? '' }}">
    </div>
    @error('tanggal_lahir')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="tahun-daftar" class="form-label">Tahun Daftar</label>
        <input type="number" class="form-control" id="tahun-daftar" value="{{ $dataPpdb->tahun_daftar }}" name="tahun_daftar" required maxlength="12">
    </div>
    @error('tahun_daftar')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="nama-ayah" class="form-label">Nama Ayah</label>
        <input type="text" class="form-control" id="nama-ayah" value="{{ $dataPpdb->nama_ayah }}" name="nama_ayah" required>
    </div>
    @error('nama_ayah')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="nama-ibu" class="form-label">Nama Ibu</label>
        <input type="text" class="form-control" id="nama-ibu" value="{{ $dataPpdb->nama_ibu }}" name="nama_ibu" required>
    </div>
    @error('nama_ibu')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="telpon-orang-tua" class="form-label">No Telp / WhatsApp</label>
        <input type="number" class="form-control" id="telpon-orang-tua" value="{{ $dataPpdb->telpon_orang_tua }}" name="telpon_orang_tua" required maxlength="12">
    </div>
    @error('telpon_orang_tua')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="col-lg-12">
        <div class="form-group">
            <label>Bukti Pendaftaran</label>
            <input type="file" id="fileInput" name="bukti_pendaftaran_new">
            <input type="hidden" id="hiddenInput" value="{{ $dataPpdb->bukti_pendaftaran }}" name="bukti_pendaftaran_old">
            <br><br>
            <img id="previewImagePpdb" src="{{ $dataPpdb->bukti_pendaftaran ? asset($dataPpdb->bukti_pendaftaran) : asset('assets/dashboard/img/warning.png') }}" alt="img" height="100" width="100">
        </div>
    </div>
    @error('bukti_pendaftaran')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="col-lg-12">
        <button type="button" class="btn btn-submit me-2" data-bs-toggle="modal" data-bs-target="#exampleModal">
            Submit
        </button>

        <a href="/ppdb-data/year" class="btn btn-cancel">Cancel</a>

        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Apakah anda yakin?</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        Data ppdb akan terupdate!
                    </div>

                    <div class="modal-footer d-flex justify-content-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
                        <button type="submit" class="btn btn-submit me-2">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</form>

{{-- preview image edit ppdb --}}
<script>
    const fileInput = document.getElementById('fileInput');
    const hiddenInput = document.getElementById('hiddenInput');
    const previewImagePpdb = document.getElementById('previewImagePpdb');
    let originalImageSrc = '{{ asset($dataPpdb->bukti_pendaftaran) }}';

    fileInput.addEventListener('change', function() {
        if (fileInput.files.length > 0) {
            const file = fileInput.files[0];
            const reader = new FileReader();

            reader.onload = function(e) {
                previewImagePpdb.src = e.target.result;
            };

            reader.readAsDataURL(file);
        } else {
            previewImagePpdb.src = originalImageSrc;
        }
    });

    fileInput.addEventListener('click', function() {
        fileInput.value = null;
        previewImagePpdb.src = originalImageSrc;
    });

</script>

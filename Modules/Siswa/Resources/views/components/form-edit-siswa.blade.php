<form action="{{ route('siswa.update', [$getDataSiswa->uuid]) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label for="nama-siswa" class="form-label">Nama</label>
        <input type="text" class="form-control" id="nama-siswa" value="{{ $getDataSiswa->name }}" name="name" required>
    </div>
    @error('name')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    @if(in_array($dataUserAuth[1], ['super_admin', 'admin']))
    <div class="mb-3">
        <label for="nisn-siswa" class="form-label">NISN</label>
        <input type="number" class="form-control" id="nisn-siswa" value="{{ $getDataSiswa->nisn }}" name="nisn" required>
    </div>
    @error('nisn')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror
    @else
    <input type="hidden" name="nisn" value="{{ $getDataSiswa->nisn }}" disabled>
    @endif

    @if(in_array($dataUserAuth[1], ['super_admin', 'admin']))
    <div class="mb-3">
        @if ($getDataSiswa->kelas === 'lulus')
        <input type="hidden" name="kelas" value="lulus">
        @else
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
        @endif
    </div>
    @error('kelas')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror
    @else
    <input type="hidden" name="kelas" value="{{ $getDataSiswa->kelas }}" disabled>
    @endif

    <div class="mb-3">
        <label for="tempat-lahir" class="form-label">Tempat Lahir</label>
        <input type="text" class="form-control" id="tempat-lahir" value="{{ $getDataSiswa->tempat_lahir }}" name="tempat_lahir" required>
    </div>
    @error('tempat_lahir')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="tanggal-lahir" class="form-label">Tanggal Lahir</label>
        <input type="date" class="form-control" id="tanggal-lahir-siswa" name="tanggal_lahir" required min="<?php echo $timeBox['minDate']; ?>" max="<?php echo $timeBox['todayDate']; ?>" value="{{ $getDataSiswa->tanggal_lahir ?? '' }}">
    </div>
    @error('tanggal_lahir')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label class="form-label">Agama</label>
        <select class="form-select" name="agama" required>
            @foreach (['islam', 'kristen', 'katolik', 'hindu', 'buddha', 'konghucu'] as $option)
            <option value="{{ $option }}" {{ $getDataSiswa->agama == $option ? 'selected' : '' }}>
                {{ ucfirst($option) }}
            </option>
            @endforeach
        </select>
    </div>
    @error('agama')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-4">
        <label class="form-label">Jenis Kelamin</label>
        <select class="form-select" name="jenis_kelamin" required>
            @foreach (['laki-laki', 'perempuan'] as $option)
            <option value="{{ $option }}" {{ $getDataSiswa->jenis_kelamin == $option ? 'selected' : '' }}>
                {{ ucfirst($option) }}
            </option>
            @endforeach
        </select>
    </div>
    @error('jenis_kelamin')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="asal-sekolah" class="form-label">Asal Sekolah</label>
        <input type="text" class="form-control" id="asal-sekolah" value="{{ $getDataSiswa->asal_sekolah }}" name="asal_sekolah" required>
    </div>
    @error('asal_sekolah')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    @if (in_array($dataUserAuth[1], ['super_admin', 'admin']))
    <div class="mb-3">
        <label for="nem" class="form-label">Nem</label>
        <input type="number" class="form-control" id="nem" value="{{ $getDataSiswa->nem }}" name="nem" required>
    </div>
    @error('nem')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror
    @else
    <input type="hidden" name="nem" value="{{ $getDataSiswa->nem }}">
    @endif

    @if (in_array($dataUserAuth[1], ['super_admin', 'admin']))
    <div class="mb-3">
        @if ($getDataSiswa->tahun_lulus)
        <label for="tahun-lulus" class="form-label">Tahun Lulus</label>
        <input type="date" class="form-control" id="tahun-lulus" value="{{ $getDataSiswa->tahun_lulus }}" name="tahun_lulus" required>
        @else
        <input type="hidden" name="tahun_lulus" value="null">
        @endif
    </div>
    @error('tahun_lulus')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror
    @else
    <input type="hidden" name="tahun_lulus" value="{{ $getDataSiswa->tahun_lulus }}">
    @endif

    <div class="mb-3">
        <label for="alamat-rumah" class="form-label">Alamat Rumah</label>
        <input type="text" class="form-control" id="alamat-rumah" value="{{ $getDataSiswa->alamat_rumah }}" name="alamat_rumah" required>
    </div>
    @error('alamat_rumah')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="provinsi" class="form-label">Provinsi</label>
        <input type="text" class="form-control" id="provinsi" value="{{ $getDataSiswa->provinsi }}" name="provinsi" required>
    </div>
    @error('provinsi')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="kecamatan" class="form-label">Kecamatan</label>
        <input type="text" class="form-control" id="kecamatan" value="{{ $getDataSiswa->kecamatan }}" name="kecamatan" required>
    </div>
    @error('kecamatan')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="kelurahan" class="form-label">Kelurahan</label>
        <input type="text" class="form-control" id="kelurahan" value="{{ $getDataSiswa->kelurahan }}" name="kelurahan" required>
    </div>
    @error('kelurahan')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="kode-pos" class="form-label">Kode Pos</label>
        <input type="number" class="form-control" id="kode-pos" value="{{ $getDataSiswa->kode_pos }}" name="kode_pos" required>
    </div>
    @error('kode_pos')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" value="{{ $getDataSiswa->email }}" name="email" required>
    </div>
    @error('email')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="no_telpon" class="form-label">No Telpon</label>
        <input type="number" class="form-control" id="no_telpon" value="{{ $getDataSiswa->no_telpon }}" name="no_telpon" required>
    </div>
    @error('no_telpon')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="tahun_daftar" class="form-label">Tahun Daftar</label>
        <input type="date" class="form-control" id="tahun_daftar" value="{{ $getDataSiswa->tahun_daftar }}" name="tahun_daftar" required>
    </div>
    @error('tahun_daftar')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    @if (in_array($dataUserAuth[1], ['super_admin', 'admin']))
    <div class="mb-3">
        @if($getDataSiswa->tahun_keluar)
        <label for="tahun_keluar" class="form-label">Tahun Keluar</label>
        <input type="text" class="form-control" id="tahun_keluar" value="{{ $getDataSiswa->tahun_keluar }}" name="tahun_keluar" required>
        @else
        <input type="hidden" name="tahun_keluar" value="null">
        @endif
    </div>
    @error('tahun_keluar')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror
    @else
    <input type="hidden" name="nem" value="null">
    @endif

    <div class="mb-3">
        <label for="nama_bank" class="form-label">Nama Bank</label>
        <input type="text" class="form-control" id="nama_bank" value="{{ $getDataSiswa->nama_bank }}" name="nama_bank" required>
    </div>
    @error('nama_bank')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="nama_buku_rekening" class="form-label">Nama Pemilik Buku Rekening</label>
        <input type="text" class="form-control" id="nama_buku_rekening" value="{{ $getDataSiswa->nama_buku_rekening }}" name="nama_buku_rekening" required>
    </div>
    @error('nama_buku_rekening')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="no_rekening" class="form-label">No Rekening</label>
        <input type="text" class="form-control" id="no_rekening" value="{{ $getDataSiswa->no_rekening }}" name="no_rekening" required>
    </div>
    @error('no_rekening')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="nama_ayah" class="form-label">Nama Ayah</label>
        <input type="text" class="form-control" id="nama_ayah" value="{{ $getDataSiswa->nama_ayah }}" name="nama_ayah" required>
    </div>
    @error('nama_ayah')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="nama_ibu" class="form-label">Nama Ibu</label>
        <input type="text" class="form-control" id="nama_ibu" value="{{ $getDataSiswa->nama_ibu }}" name="nama_ibu" required>
    </div>
    @error('nama_ibu')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="nama_wali" class="form-label">Nama Wali</label>
        <input type="text" class="form-control" id="nama_wali" value="{{ $getDataSiswa->nama_wali }}" name="nama_wali" required>
    </div>
    @error('nama_wali')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="telpon_orang_tua" class="form-label">Telpon Orang Tua</label>
        <input type="number" class="form-control" id="telpon_orang_tua" value="{{ $getDataSiswa->telpon_orang_tua }}" name="telpon_orang_tua" required>
    </div>
    @error('telpon_orang_tua')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="col-lg-12">
        <div class="form-group">
            <label>Foto</label>
            <input type="file" id="fileInput" name="foto_new" accept=".jpg, .jpeg, .png" onchange="validateFile(this)">
            <input type="hidden" id="hiddenInput" value="{{ $getDataSiswa->foto }}" name="foto_old">
            <br><br>
            <img id="previewImageSiswa" src="{{ $getDataSiswa->foto ? asset($getDataSiswa->foto) : asset('assets/dashboard/img/warning.png') }}" alt="foto-siswa" height="100" width="100">
        </div>
    </div>
    @error('foto_new')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="col-lg-12">
        <button type="button" class="btn btn-submit me-2" data-bs-toggle="modal" data-bs-target="#exampleModal">
            Submit
        </button>

        <a href="{{ route('siswa.status') }}" class="btn btn-cancel">Cancel</a>

        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Apakah anda yakin?</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        Data siswa akan terupdate!
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

<script>
    const fileInput = document.getElementById('fileInput');
    const hiddenInput = document.getElementById('hiddenInput');
    const previewImageSiswa = document.getElementById('previewImageSiswa');
    const maxFileSize = 3 * 1024 * 1024; // 3MB

    fileInput.addEventListener('change', function() {
        const file = fileInput.files[0];

        if (file) {
            if (file.type === 'image/jpeg' || file.type === 'image/png') {
                if (file.size <= maxFileSize) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        previewImageSiswa.src = e.target.result;
                    };

                    reader.readAsDataURL(file);
                } else {
                    alert('Ukuran file foto melebihi batas 3MB.');
                    fileInput.value = null;
                }
            } else {
                alert('Format file tidak valid. Hanya diperbolehkan JPG dan PNG.');
                fileInput.value = null;
            }
        } else {
            previewImageSiswa.src = '{{ asset($getDataSiswa->foto) }}';
        }
    });

    fileInput.addEventListener('click', function() {
        fileInput.value = null;
        previewImageSiswa.src = '{{ asset($getDataSiswa->foto) }}';
    });

</script>

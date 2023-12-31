<form action="{{ route('siswa.update', [$dataSiswa->uuid]) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label for="nama-siswa" class="form-label">Nama</label>
        <input type="text" class="form-control" id="nama-siswa" value="{{ $dataSiswa->name }}" name="name" required>
    </div>
    @error('name')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    @if(in_array($dataUserAuth[1], ['super_admin', 'admin']))
    <div class="mb-3">
        <label for="nisn-siswa" class="form-label">NISN</label>
        <input type="number" class="form-control" id="nisn-siswa" value="{{ $dataSiswa->nisn }}" name="nisn" required>
    </div>
    @error('nisn')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror
    @else
    <input type="hidden" name="nisn" value="{{ $dataSiswa->nisn }}">
    @endif

    @if(in_array($dataUserAuth[1], ['super_admin', 'admin']))
    <div class="mb-3">
        @if ($dataSiswa->kelas === null)
        <input type="hidden" name="kelas" value="">
        @else
        <div class="mb-3">
            <label class="form-label">Kelas</label>
            <select class="form-select" name="kelas" required>
                @foreach ([10, 11, 12] as $option)
                <option value="{{ $option }}" {{ $dataSiswa->kelas == $option ? 'selected' : '' }}>
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
    <input type="hidden" name="kelas" value="{{ $dataSiswa->kelas }}">
    @endif

    <div class="mb-3">
        <label for="tempat-lahir" class="form-label">Tempat Lahir</label>
        <input type="text" class="form-control" id="tempat-lahir" value="{{ $dataSiswa->tempat_lahir }}" name="tempat_lahir" required>
    </div>
    @error('tempat_lahir')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="tanggal-lahir" class="form-label">Tanggal Lahir</label>
        <input type="date" class="form-control" id="tanggal-lahir-siswa" name="tanggal_lahir" required min="<?php echo $timeBox['minDate']; ?>" max="<?php echo $timeBox['todayDate']; ?>" value="{{ $dataSiswa->tanggal_lahir ?? '' }}">
    </div>
    @error('tanggal_lahir')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label class="form-label">Agama</label>
        <select class="form-select" name="agama">
            @foreach (['islam', 'kristen', 'katolik', 'hindu', 'buddha', 'konghucu'] as $option)
            <option value="{{ $option }}" {{ $dataSiswa->agama == $option ? 'selected' : '' }}>
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
            <option value="{{ $option }}" {{ $dataSiswa->jenis_kelamin == $option ? 'selected' : '' }}>
                {{ ucfirst($option) }}
            </option>
            @endforeach
        </select>
    </div>
    @error('jenis_kelamin')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="asal-sekolah" class="form-label">Asal Sekolah Smp / Mts</label>
        <input type="text" class="form-control" id="asal-sekolah" value="{{ $dataSiswa->asal_sekolah }}" name="asal_sekolah" required>
    </div>
    @error('asal_sekolah')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    @if (in_array($dataUserAuth[1], ['super_admin', 'admin']))
    <div class="mb-3">
        <label for="nem" class="form-label">Nem</label>
        <input type="number" class="form-control" id="nem" value="{{ $dataSiswa->nem }}" name="nem">
    </div>
    @error('nem')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror
    @else
    <input type="hidden" name="nem" value="{{ $dataSiswa->nem }}">
    @endif

    @if (in_array($dataUserAuth[1], ['super_admin', 'admin']))
    <div class="mb-3">
        <label for="tahun-lulus" class="form-label">Tahun Lulus Smp / Mts</label>
        <input type="text" class="form-control" id="tahun-lulus" value="{{ $dataSiswa->tahun_lulus }}" name="tahun_lulus">
    </div>
    @error('tahun_lulus')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror
    @else
    <input type="hidden" name="tahun_lulus" value="{{ $dataSiswa->tahun_lulus }}">
    @endif

    <div class="mb-3">
        <label for="alamat-rumah" class="form-label">Alamat Rumah</label>
        <input type="text" class="form-control" id="alamat-rumah" value="{{ $dataSiswa->alamat_rumah }}" name="alamat_rumah">
    </div>
    @error('alamat_rumah')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="provinsi" class="form-label">Provinsi</label>
        <input type="text" class="form-control" id="provinsi" value="{{ $dataSiswa->provinsi }}" name="provinsi">
    </div>
    @error('provinsi')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="kecamatan" class="form-label">Kecamatan</label>
        <input type="text" class="form-control" id="kecamatan" value="{{ $dataSiswa->kecamatan }}" name="kecamatan">
    </div>
    @error('kecamatan')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="kelurahan" class="form-label">Kelurahan</label>
        <input type="text" class="form-control" id="kelurahan" value="{{ $dataSiswa->kelurahan }}" name="kelurahan">
    </div>
    @error('kelurahan')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="kode-pos" class="form-label">Kode Pos</label>
        <input type="number" class="form-control" id="kode-pos" value="{{ $dataSiswa->kode_pos }}" name="kode_pos">
    </div>
    @error('kode_pos')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" value="{{ $dataSiswa->email }}" name="email" required>
    </div>
    @error('email')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="no_telpon" class="form-label">No Telpon</label>
        <input type="number" class="form-control" id="no_telpon" value="{{ $dataSiswa->no_telpon }}" name="no_telpon" required>
    </div>
    @error('no_telpon')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    @if (in_array($dataUserAuth[1], ['super_admin', 'admin']))
    <div class="mb-3">
        <label for="tahun_daftar" class="form-label">Tahun Daftar</label>
        <input type="text" class="form-control" id="tahun_daftar" value="{{ $dataSiswa->tahun_daftar }}" name="tahun_daftar" required>
    </div>
    @error('tahun_daftar')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror
    @else
    <input type="hidden" name="tahun_daftar" value="{{ $dataSiswa->tahun_daftar }}" readonly>
    @endif

    @if (in_array($dataUserAuth[1], ['super_admin', 'admin']))
    @if($dataSiswa->tahun_keluar)
    <div class="mb-3">
        <label for="tahun_keluar" class="form-label">Tahun Keluar</label>
        <input type="text" class="form-control" id="tahun_keluar" value="{{ $dataSiswa->tahun_keluar }}" name="tahun_keluar">
    </div>
    @error('tahun_keluar')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror
    @else
    <input type="hidden" name="tahun_keluar" value="" readonly>
    @endif
    @else
    <input type="hidden" name="tahun_keluar" value="" readonly>
    @endif

    <div class="mb-3">
        <label for="nama_bank" class="form-label">Nama Bank</label>
        <input type="text" class="form-control" id="nama_bank" value="{{ $dataSiswa->nama_bank }}" name="nama_bank">
    </div>
    @error('nama_bank')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="nama_buku_rekening" class="form-label">Nama Pemilik Buku Rekening</label>
        <input type="text" class="form-control" id="nama_buku_rekening" value="{{ $dataSiswa->nama_buku_rekening }}" name="nama_buku_rekening">
    </div>
    @error('nama_buku_rekening')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="no_rekening" class="form-label">No Rekening</label>
        <input type="number" class="form-control" id="no_rekening" value="{{ $dataSiswa->no_rekening }}" name="no_rekening">
    </div>
    @error('no_rekening')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="nama_ayah" class="form-label">Nama Ayah</label>
        <input type="text" class="form-control" id="nama_ayah" value="{{ $dataSiswa->nama_ayah }}" name="nama_ayah" required>
    </div>
    @error('nama_ayah')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="nama_ibu" class="form-label">Nama Ibu</label>
        <input type="text" class="form-control" id="nama_ibu" value="{{ $dataSiswa->nama_ibu }}" name="nama_ibu" required>
    </div>
    @error('nama_ibu')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="nama_wali" class="form-label">Nama Wali</label>
        <input type="text" class="form-control" id="nama_wali" value="{{ $dataSiswa->nama_wali }}" name="nama_wali">
    </div>
    @error('nama_wali')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="telpon_orang_tua" class="form-label">Telpon Orang Tua</label>
        <input type="number" class="form-control" id="telpon_orang_tua" value="{{ $dataSiswa->telpon_orang_tua }}" name="telpon_orang_tua" required>
    </div>
    @error('telpon_orang_tua')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="col-lg-12">
        <div class="form-group">
            <label>Foto</label>
            <input type="file" id="fileInput" name="foto_new" accept=".jpg, .jpeg, .png" onchange="validateFile(this)">
            <input type="hidden" id="hiddenInput" value="{{ $dataSiswa->foto }}" name="foto_old">
            <br><br>
            <img id="previewImageSiswa" src="{{ $dataSiswa->foto ? asset($dataSiswa->foto) : asset('assets/dashboard/img/warning.png') }}" alt="foto-siswa" height="100" width="100">
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
            previewImageSiswa.src = '{{ asset($dataSiswa->foto) }}';
        }
    });

    fileInput.addEventListener('click', function() {
        fileInput.value = null;
        previewImageSiswa.src = '{{ asset($dataSiswa->foto) }}';
    });

</script>

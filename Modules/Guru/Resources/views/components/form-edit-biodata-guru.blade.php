<form action="{{ route('data.guru.update', $dataGuru->uuid) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label for="name" class="form-label">Nama</label>
        <input type="text" class="form-control" id="name" value="{{ $dataGuru->name }}" name="name" required>
    </div>
    @error('name')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="nuptk" class="form-label">Nuptk</label>
        <input type="number" class="form-control" id="nuptk" value="{{ $dataGuru->nuptk }}" name="nuptk" required>
    </div>
    @error('nuptk')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="nip" class="form-label">Nip</label>
        @if($dataGuru->nip != 'belum ada')
        <input type="number" class="form-control" id="nip" value="{{ $dataGuru->nip }}" name="nip" required>
        @else
        <input type="text" class="form-control" id="nip" value="belum ada" name="nip" required>
        @endif
    </div>
    @error('nip')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
        <input type="text" class="form-control" id="tempat_lahir" value="{{ $dataGuru->tempat_lahir }}" name="tempat_lahir" required>
    </div>
    @error('tempat_lahir')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
        <input type="date" class="form-control" id="tanggal_lahir" value="{{ $dataGuru->tanggal_lahir }}" name="tanggal_lahir" required>
    </div>
    @error('tanggal_lahir')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    {{-- <div class="mb-3">
        <label for="mata_pelajaran" class="form-label">Mata Pelajaran</label>
        <input type="text" class="form-control" id="mata_pelajaran" value="{{ $dataGuru->mata_pelajaran }}" name="mata_pelajaran" required>
    </div>
    @error('mata_pelajaran')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror --}}

    <div class="mb-3">
        <label for="agama" class="form-label">Agama</label>
        <select class="form-select" name="agama" required>
            @foreach (['islam', 'kristen', 'katolik', 'hindu', 'buddha', 'konghucu',] as $option)
            <option value="{{ $option }}" {{ $dataGuru->agama == $option ? 'selected' : '' }}>
                {{ ucfirst($option) }}
            </option>
            @endforeach
        </select>
    </div>
    @error('agama')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
        <select class="form-select" name="jenis_kelamin" required>
            @foreach (['laki-laki', 'perempuan',] as $option)
            <option value="{{ $option }}" {{ $dataGuru->jenis_kelamin == $option ? 'selected' : '' }}>
                {{ ucfirst($option) }}
            </option>
            @endforeach
        </select>
    </div>
    @error('jenis_kelamin')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="status_perkawinan" class="form-label">Status Perkawinan</label>
        <select class="form-select" name="status_perkawinan" required>
            @foreach (['belum-menikah', 'sudah-menikah',] as $option)
            <option value="{{ $option }}" {{ $dataGuru->status_perkawinan == $option ? 'selected' : '' }}>
                {{ ucfirst($option) }}
            </option>
            @endforeach
        </select>
    </div>
    @error('status_perkawinan')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="jam_mengajar" class="form-label">Jam Mengajar</label>
        <input type="datetime-local" class="form-control" id="jam_mengajar" value="{{ $dataGuru->jam_mengajar }}" name="jam_mengajar" required>
    </div>
    @error('jam_mengajar')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="pendidikan_terakhir" class="form-label">Pendidikan Terakhir</label>
        <input type="text" class="form-control" id="pendidikan_terakhir" value="{{ $dataGuru->pendidikan_terakhir }}" name="pendidikan_terakhir" required>
    </div>
    @error('pendidikan_terakhir')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="nama_tempat_pendidikan" class="form-label">Nama Tempat Pendidikan</label>
        <input type="text" class="form-control" id="nama_tempat_pendidikan" value="{{ $dataGuru->nama_tempat_pendidikan }}" name="nama_tempat_pendidikan" required>
    </div>
    @error('nama_tempat_pendidikan')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="ipk" class="form-label">Ipk</label>
        <input type="text" class="form-control" id="ipk" value="{{ $dataGuru->ipk }}" name="ipk" required>
    </div>
    @error('ipk')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="tahun_lulus" class="form-label">Tahun Lulus</label>
        <input type="date" class="form-control" id="tahun_lulus" value="{{ $dataGuru->tahun_lulus }}" name="tahun_lulus" required>
    </div>
    @error('tahun_lulus')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="alamat_rumah" class="form-label">Alamat Rumah</label>
        <input type="text" class="form-control" id="alamat_rumah" value="{{ $dataGuru->alamat_rumah }}" name="alamat_rumah" required>
    </div>
    @error('alamat_rumah')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="provinsi" class="form-label">Provinsi</label>
        <input type="text" class="form-control" id="provinsi" value="{{ $dataGuru->provinsi }}" name="provinsi" required>
    </div>
    @error('provinsi')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="kecamatan" class="form-label">Kecamatan</label>
        <input type="text" class="form-control" id="kecamatan" value="{{ $dataGuru->kecamatan }}" name="kecamatan" required>
    </div>
    @error('kecamatan')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="kelurahan" class="form-label">Kelurahan</label>
        <input type="text" class="form-control" id="kelurahan" value="{{ $dataGuru->kelurahan }}" name="kelurahan" required>
    </div>
    @error('kelurahan')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="kode_pos" class="form-label">Kode Pos</label>
        <input type="number" class="form-control" id="kode_pos" value="{{ $dataGuru->kode_pos }}" name="kode_pos" required>
    </div>
    @error('kode_pos')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="text" class="form-control" id="email" value="{{ $dataGuru->email }}" name="email" required>
    </div>
    @error('email')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="no_telpon" class="form-label">No Telpon</label>
        <input type="number" class="form-control" id="no_telpon" value="{{ $dataGuru->no_telpon }}" name="no_telpon" required>
    </div>
    @error('no_telpon')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="tahun_daftar" class="form-label">Tahun Daftar</label>
        <input type="date" class="form-control" id="tahun_daftar" value="{{ $dataGuru->tahun_daftar }}" name="tahun_daftar" required>
    </div>
    @error('tahun_daftar')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="tahun_keluar" class="form-label">Tahun Keluar</label>
        @if($dataGuru->tahun_keluar)
        <input type="date" class="form-control" id="tahun_keluar" value="{{ $dataGuru->tahun_keluar }}" name="tahun_keluar" required>
        @else
        <select class="form-select" name="tahun_keluar" id="tahun_keluar" required>
            <option value="aktif" {{ $dataGuru->tahun_keluar == 'aktif' ? 'selected' : '' }}>
                aktif
            </option>

            <option value="tanggal_waktu_sekarang" id="tahun_tidak_aktif">
                tidak aktif
            </option>
        </select>
        @endif
    </div>
    @error('tahun_keluar')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="nama_bank" class="form-label">Nama Bank</label>
        <input type="text" class="form-control" id="nama_bank" value="{{ $dataGuru->nama_bank }}" name="nama_bank" required>
    </div>
    @error('nama_bank')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="nama_buku_rekening" class="form-label">Nama Buku Rekening</label>
        <input type="text" class="form-control" id="nama_buku_rekening" value="{{ $dataGuru->nama_buku_rekening }}" name="nama_buku_rekening" required>
    </div>
    @error('nama_buku_rekening')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="no_rekening" class="form-label">No Rekening</label>
        <input type="number" class="form-control" id="no_rekening" value="{{ $dataGuru->no_rekening }}" name="no_rekening" required>
    </div>
    @error('no_rekening')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="col-lg-12">
        <div class="form-group">
            <label>Foto</label>
            <input type="file" id="fileInput" name="foto_new" accept=".jpg, .jpeg, .png" onchange="validateFile(this)">
            <input type="hidden" id="hiddenInput" value="{{ $dataGuru->foto }}" name="foto_old">
            <br><br>
            <img id="previewImageSiswa" src="{{ $dataGuru->foto ? asset($dataGuru->foto) : asset('assets/dashboard/img/warning.png') }}" alt="foto-siswa" height="100" width="100">
        </div>
    </div>
    @error('foto_new')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="col-lg-12">
        <button type="button" class="btn btn-submit me-2" data-bs-toggle="modal" data-bs-target="#exampleModal">
            Submit
        </button>

        <a href="/data-guru" class="btn btn-cancel">Cancel</a>

        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Apakah anda yakin?</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        Data <span style="font-weight: bold">{{ $dataGuru->name }}</span> akan berubah!
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

{{-- tahun_keluar --}}
<script>
    const selectElement = document.getElementById('tahun_keluar');

    selectElement.addEventListener('change', function() {
        const selectedValue = selectElement.value;

        if (selectedValue === 'tanggal_waktu_sekarang') {
            const selectTidakAktif = document.getElementById('tahun_tidak_aktif');
            const currentDate = new Date().toISOString().slice(0, 19).replace('T', ' ');
            selectTidakAktif.value = currentDate;
            console.log(selectTidakAktif.value)
        }
    });

</script>

{{-- foto --}}
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
            previewImageSiswa.src = '{{ asset($dataGuru->foto) }}';
        }
    });

    fileInput.addEventListener('click', function() {
        fileInput.value = null;
        previewImageSiswa.src = '{{ asset($dataGuru->foto) }}';
    });

</script>

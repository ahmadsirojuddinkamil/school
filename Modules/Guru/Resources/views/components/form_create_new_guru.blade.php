<form action="{{ route('data.guru.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="mb-3">
        <label for="name" class="form-label">Nama</label>
        <input type="text" class="form-control" id="name" name="name" required>
    </div>
    @error('name')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="nuptk" class="form-label">Nuptk</label>
        <input type="text" class="form-control" id="nuptk" name="nuptk" required>
    </div>
    @error('nuptk')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="nip" class="form-label">Nip</label>
        <input type="text" class="form-control" id="nip" name="nip">
    </div>
    @error('nip')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
        <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" required>
    </div>
    @error('tempat_lahir')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
        <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" required>
    </div>
    @error('tanggal_lahir')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    {{-- <div class="mb-3">
        <label for="mata_pelajaran" class="form-label">Mata Pelajaran</label>
        <input type="text" class="form-control" id="mata_pelajaran" name="mata_pelajaran" required>
    </div>
    @error('mata_pelajaran')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror --}}

    <div class="mb-3">
        <label for="agama" class="form-label">Agama</label>
        <select class="form-select" name="agama" required>
            <option value="islam">islam</option>
            <option value="kristen">kristen</option>
            <option value="katolik">katolik</option>
            <option value="hindu">hindu</option>
            <option value="buddha">buddha</option>
            <option value="konghucu">konghucu</option>
        </select>
    </div>
    @error('agama')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
        <select class="form-select" name="jenis_kelamin" required>
            <option value="laki-laki">laki-laki</option>
            <option value="perempuan">perempuan</option>
        </select>
    </div>
    @error('jenis_kelamin')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="status_perkawinan" class="form-label">Status Perkawinan</label>
        <select class="form-select" name="status_perkawinan" required>
            <option value="belum-menikah">belum-menikah</option>
            <option value="sudah-menikah">sudah-menikah</option>
        </select>
    </div>
    @error('status_perkawinan')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="jam_mengajar_awal" class="form-label">Jam Mengajar Awal</label>
        <input type="time" class="form-control" id="jam_mengajar_awal" name="jam_mengajar_awal" required>
    </div>
    @error('jam_mengajar_awal')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="jam_mengajar_akhir" class="form-label">Jam Mengajar Akhir</label>
        <input type="time" class="form-control" id="jam_mengajar_akhir" name="jam_mengajar_akhir" required>
    </div>
    @error('jam_mengajar_akhir')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="pendidikan_terakhir" class="form-label">Pendidikan Terakhir</label>
        <input type="text" class="form-control" id="pendidikan_terakhir" name="pendidikan_terakhir" required>
    </div>
    @error('pendidikan_terakhir')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="nama_tempat_pendidikan" class="form-label">Nama Tempat Pendidikan</label>
        <input type="text" class="form-control" id="nama_tempat_pendidikan" name="nama_tempat_pendidikan" required>
    </div>
    @error('nama_tempat_pendidikan')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="ipk" class="form-label">Ipk</label>
        <input type="number" class="form-control" id="ipk" name="ipk" required>
    </div>
    @error('ipk')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="tahun_lulus" class="form-label">Tahun Lulus</label>
        <input type="text" class="form-control" id="tahun_lulus" name="tahun_lulus" required>
    </div>
    @error('tahun_lulus')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="alamat_rumah" class="form-label">Alamat Rumah</label>
        <input type="text" class="form-control" id="alamat_rumah" name="alamat_rumah" required>
    </div>
    @error('alamat_rumah')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="provinsi" class="form-label">Provinsi</label>
        <input type="text" class="form-control" id="provinsi" name="provinsi" required>
    </div>
    @error('provinsi')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="kecamatan" class="form-label">Kecamatan</label>
        <input type="text" class="form-control" id="kecamatan" name="kecamatan" required>
    </div>
    @error('kecamatan')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="kelurahan" class="form-label">Kelurahan</label>
        <input type="text" class="form-control" id="kelurahan" name="kelurahan" required>
    </div>
    @error('kelurahan')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="kode_pos" class="form-label">Kode Pos</label>
        <input type="number" class="form-control" id="kode_pos" name="kode_pos" required>
    </div>
    @error('kode_pos')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="text" class="form-control" id="email" name="email" required>
    </div>
    @error('email')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="no_telpon" class="form-label">No Telpon</label>
        <input type="number" class="form-control" id="no_telpon" name="no_telpon" required>
    </div>
    @error('no_telpon')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <input type="hidden" class="form-control" id="tahun_daftar" name="tahun_daftar" value="{{ date('Y') }}" required>
    </div>
    @error('tahun_daftar')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <input type="hidden" class="form-control" id="tahun_keluar" name="tahun_keluar" value="">
    </div>
    @error('tahun_keluar')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="nama_bank" class="form-label">Nama Bank</label>
        <input type="text" class="form-control" id="nama_bank" name="nama_bank">
    </div>
    @error('nama_bank')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="nama_buku_rekening" class="form-label">Nama Buku Rekening</label>
        <input type="text" class="form-control" id="nama_buku_rekening" name="nama_buku_rekening">
    </div>
    @error('nama_buku_rekening')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="no_rekening" class="form-label">No Rekening</label>
        <input type="string" class="form-control" id="no_rekening" name="no_rekening">
    </div>
    @error('no_rekening')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="col-lg-12">
        <div class="form-group">
            <label>Foto</label>
            <input type="file" id="fileInput" name="foto" accept=".jpg, .jpeg, .png" onchange="validateFile(this)" required>
            <br><br>
            <img id="previewImageGuru" alt="foto-guru" height="100" width="100" style="display: none;">
        </div>
    </div>
    @error('foto')
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
                        Data harus sudah valid!
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
    const previewImageGuru = document.getElementById('previewImageGuru');
    const maxFileSize = 3 * 1024 * 1024; // 3MB

    fileInput.addEventListener('change', function() {
        const file = fileInput.files[0];

        if (file) {
            if (file.type === 'image/jpeg' || file.type === 'image/png') {
                if (file.size <= maxFileSize) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        previewImageGuru.src = e.target.result;
                        previewImageGuru.style.display = 'block'; // Menampilkan gambar
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
            previewImageGuru.src = '';
            previewImageGuru.style.display = 'none'; // Sembunyikan gambar ketika tidak ada gambar yang dipilih
        }
    });

    fileInput.addEventListener('click', function() {
        fileInput.value = null;
        previewImageGuru.src = '';
        previewImageGuru.style.display = 'none'; // Sembunyikan gambar ketika tombol 'Browse' diklik
    });

</script>

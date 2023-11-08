<form action="{{ route('siswa.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="mb-3">
        <label for="nama-siswa" class="form-label">Nama</label>
        <input type="text" class="form-control" id="nama-siswa" name="name" required placeholder="Yudi Amanda">
    </div>
    @error('name')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="nisn-siswa" class="form-label">NISN</label>
        <input type="number" class="form-control" id="nisn-siswa" name="nisn" required placeholder="0493849382">
    </div>
    @error('nisn')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label class="form-label">Kelas</label>
        <select class="form-select" name="kelas" required>
            <option value="10">
                10
            </option>

            <option value="11">
                11
            </option>

            <option value="12">
                12
            </option>
        </select>
    </div>
    @error('kelas')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="tempat-lahir" class="form-label">Tempat Lahir</label>
        <input type="text" class="form-control" id="tempat-lahir" name="tempat_lahir" required placeholder="Jakarta">
    </div>
    @error('tempat_lahir')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
        <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir">
    </div>
    @error('tanggal_lahir')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label class="form-label">Agama</label>
        <select class="form-select" name="agama" required>
            <option value="islam">
                islam
            </option>

            <option value="kristen">
                kristen
            </option>

            <option value="katolik">
                katolik
            </option>

            <option value="hindu">
                hindu
            </option>

            <option value="buddha">
                buddha
            </option>

            <option value="konghucu">
                konghucu
            </option>
        </select>
    </div>
    @error('agama')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-4">
        <label class="form-label">Jenis Kelamin</label>
        <select class="form-select" name="jenis_kelamin" required>
            <option value="laki-laki">
                laki-laki
            </option>

            <option value="perempuan">
                perempuan
            </option>
        </select>
    </div>
    @error('jenis_kelamin')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="asal-sekolah" class="form-label">Asal Sekolah Smp / Mts</label>
        <input type="text" class="form-control" id="asal-sekolah" name="asal_sekolah" required placeholder="Smp Mutiara 1">
    </div>
    @error('asal_sekolah')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="nem" class="form-label">Nem</label>
        <input type="number" class="form-control" id="nem" name="nem" required placeholder="2.50">
    </div>
    @error('nem')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="tahun-lulus" class="form-label">Tahun Lulus</label>
        <input type="number" class="form-control" id="tahun-lulus" name="tahun_lulus" required placeholder="2018">
    </div>
    @error('tahun_lulus')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="alamat-rumah" class="form-label">Alamat Rumah</label>
        <input type="text" class="form-control" id="alamat-rumah" name="alamat_rumah" placeholder="Jl. simatupang selatan">
    </div>
    @error('alamat_rumah')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="provinsi" class="form-label">Provinsi</label>
        <input type="text" class="form-control" id="provinsi" name="provinsi" placeholder="DKI Jakarta">
    </div>
    @error('provinsi')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="kecamatan" class="form-label">Kecamatan</label>
        <input type="text" class="form-control" id="kecamatan" name="kecamatan" placeholder="Pasar Minggu">
    </div>
    @error('kecamatan')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="kelurahan" class="form-label">Kelurahan</label>
        <input type="text" class="form-control" id="kelurahan" name="kelurahan" placeholder="Jati Padang">
    </div>
    @error('kelurahan')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="kode-pos" class="form-label">Kode Pos</label>
        <input type="number" class="form-control" id="kode-pos" name="kode_pos" placeholder="12520">
    </div>
    @error('kode_pos')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" required placeholder="example@gmail.com">
    </div>
    @error('email')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="no_telpon" class="form-label">No Telpon</label>
        <input type="number" class="form-control" id="no_telpon" name="no_telpon" required placeholder="081280303338">
    </div>
    @error('no_telpon')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <input type="hidden" class="form-control" id="tahun_daftar" name="tahun_daftar" required value="{{ date('Y') }}">
    </div>
    @error('tahun_daftar')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <input type="hidden" class="form-control" name="tahun_keluar" value="">
    </div>
    @error('tahun_keluar')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="nama_bank" class="form-label">Nama Bank</label>
        <input type="text" class="form-control" name="nama_bank" id="nama_bank" placeholder="BCA">
    </div>
    @error('nama_bank')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="nama_buku_rekening" class="form-label">Nama Pemilik Buku Rekening</label>
        <input type="text" class="form-control" id="nama_buku_rekening" name="nama_buku_rekening" placeholder="Sumanti">
    </div>
    @error('nama_buku_rekening')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="no_rekening" class="form-label">No Rekening</label>
        <input type="text" class="form-control" id="no_rekening" name="no_rekening" placeholder="4243458593">
    </div>
    @error('no_rekening')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="nama_ayah" class="form-label">Nama Ayah</label>
        <input type="text" class="form-control" id="nama_ayah" name="nama_ayah" required placeholder="Joko">
    </div>
    @error('nama_ayah')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="nama_ibu" class="form-label">Nama Ibu</label>
        <input type="text" class="form-control" id="nama_ibu" name="nama_ibu" required placeholder="Sumanti">
    </div>
    @error('nama_ibu')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="nama_wali" class="form-label">Nama Wali</label>
        <input type="text" class="form-control" id="nama_wali" name="nama_wali" placeholder="Hartono">
    </div>
    @error('nama_wali')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="telpon_orang_tua" class="form-label">Telpon Orang Tua</label>
        <input type="number" class="form-control" id="telpon_orang_tua" name="telpon_orang_tua" required placeholder="081280303338">
    </div>
    @error('telpon_orang_tua')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="col-lg-12">
        <div class="form-group">
            <label>Foto</label>
            <input type="file" id="fileInput" name="foto" accept=".jpg, .jpeg, .png" onchange="validateFile(this)" required>
            <br><br>
            <img id="previewImageSiswa" alt="foto-siswa" height="100" width="100" style="display: none;">
        </div>
    </div>

    @error('foto_new')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="col-lg-12">
        <button type="button" class="btn btn-submit me-2" data-bs-toggle="modal" data-bs-target="#exampleModal">
            Submit
        </button>

        <a href="/data-siswa/status" class="btn btn-cancel">Cancel</a>

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
                        previewImageSiswa.style.display = 'block'; // Menampilkan gambar
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
            previewImageSiswa.src = '';
            previewImageSiswa.style.display = 'none'; // Sembunyikan gambar ketika tidak ada gambar yang dipilih
        }
    });

    fileInput.addEventListener('click', function() {
        fileInput.value = null;
        previewImageSiswa.src = '';
        previewImageSiswa.style.display = 'none'; // Sembunyikan gambar ketika tombol 'Browse' diklik
    });

</script>

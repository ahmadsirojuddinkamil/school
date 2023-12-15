<form action="{{ route('update.mata.pelajaran', $dataMataPelajaran->uuid) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label for="name" class="form-label">Nama</label>
        <input type="text" class="form-control" id="name" value="{{ $dataMataPelajaran->name }}" disabled>
    </div>

    <div class="mb-3">
        <label for="jam_awal" class="form-label">Jam Awal</label>
        <input type="time" step="any" class="form-control" id="jam_awal" value="{{ $dataMataPelajaran->jam_awal }}" name="jam_awal" required>
    </div>
    @error('jam_awal')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="jam_akhir" class="form-label">Jam Akhir</label>
        <input type="time" step="any" class="form-control" id="jam_akhir" value="{{ $dataMataPelajaran->jam_akhir }}" name="jam_akhir" required>
    </div>
    @error('jam_akhir')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="kelas" class="form-label">Kelas</label>
        <select class="form-select" name="kelas" required>
            @foreach (['10', '11', '12'] as $option)
            <option value="{{ $option }}" {{ $dataMataPelajaran->kelas == $option ? 'selected' : '' }}>
                {{ ucfirst($option) }}
            </option>
            @endforeach
        </select>
    </div>
    @error('kelas')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="name_guru" class="form-label">Guru</label>
        <select class="form-select" name="name_guru">
            <option value="" {{ $dataMataPelajaran->name_guru ? '' : 'selected' }}>Belum Ada</option>
            @foreach ($listNameGuru as $nameGuru)
            <option value="{{ $nameGuru }}" {{ optional($dataMataPelajaran->guru)->name === $nameGuru ? 'selected' : '' }}>
                {{ ucfirst($nameGuru) }}
            </option>
            @endforeach
        </select>
    </div>
    @error('name_guru')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="materi_pdf" class="form-label">Materi Pdf (Max 30MB, Zip or Rar Only)</label>
        <input type="file" class="form-control" id="materi_pdf" value="{{ $dataMataPelajaran->materi_pdf }}" name="materi_pdf" accept=".zip, .rar">
    </div>
    @error('materi_pdf')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="materi_ppt" class="form-label">Materi Ppt (Max 30MB, Zip or Rar Only)</label>
        <input type="file" class="form-control" id="materi_ppt" value="{{ $dataMataPelajaran->materi_ppt }}" name="materi_ppt" accept=".zip, .rar">
    </div>
    @error('materi_ppt')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <label for="video" class="form-label">Video (Link Youtube)</label>
        <input type="url" class="form-control" id="video" value="{{ $dataMataPelajaran->video }}" name="video">
    </div>
    @error('video')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="col-lg-12">
        <div class="form-group">
            <label>Foto</label>
            <input type="file" id="fileInput" class="form-control" name="foto_new" accept=".jpg, .jpeg, .png" onchange="validateFile(this)">
            <input type="hidden" id="hiddenInput" value="{{ $dataMataPelajaran->foto ?? 'assets/dashboard/img/mapel.png' }}" name="foto_old">
            <br><br>
            <img id="previewImageMataPelajaran" src="{{ $dataMataPelajaran->foto ? asset($dataMataPelajaran->foto) : asset('assets/dashboard/img/mapel.png') }}" alt="foto-mata-pelajaran" height="100" width="100">
        </div>
    </div>
    @error('foto_new')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="col-lg-12">
        <button type="button" class="btn btn-submit me-2" data-bs-toggle="modal" data-bs-target="#exampleModal">
            Submit
        </button>

        <a href="/data-mata-pelajaran" class="btn btn-cancel">Cancel</a>

        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Apakah anda yakin?</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        Data <span style="font-weight: bold"></span> akan berubah!
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
    document.getElementById('materi_pdf').addEventListener('change', function() {
        const fileInput = this;
        const maxSizeMB = 30;

        if (fileInput.files.length > 0) {
            const fileSizeMB = fileInput.files[0].size / (1024 * 1024);

            if (fileSizeMB > maxSizeMB) {
                alert('Ukuran file melebihi batas maksimum (30MB).');
                fileInput.value = ''; // Membersihkan input file jika ukuran melebihi batas
            }

            const allowedExtensions = ['zip', 'rar'];
            const fileName = fileInput.files[0].name;
            const fileExtension = fileName.split('.').pop();

            if (!allowedExtensions.includes(fileExtension)) {
                alert('Jenis file tidak valid. Hanya file zip atau rar yang diizinkan.');
                fileInput.value = ''; // Membersihkan input file jika jenis file tidak valid
            }
        }
    });

</script>

<script>
    document.getElementById('materi_ppt').addEventListener('change', function() {
        const fileInput = this;
        const maxSizeMB = 30;

        if (fileInput.files.length > 0) {
            const fileSizeMB = fileInput.files[0].size / (1024 * 1024);

            if (fileSizeMB > maxSizeMB) {
                alert('Ukuran file melebihi batas maksimum (30MB).');
                fileInput.value = ''; // Membersihkan input file jika ukuran melebihi batas
            }

            const allowedExtensions = ['zip', 'rar'];
            const fileName = fileInput.files[0].name;
            const fileExtension = fileName.split('.').pop();

            if (!allowedExtensions.includes(fileExtension)) {
                alert('Jenis file tidak valid. Hanya file zip atau rar yang diizinkan.');
                fileInput.value = ''; // Membersihkan input file jika jenis file tidak valid
            }
        }
    });

</script>

<script>
    function validateFile(input) {
        const allowedExtensions = ['.jpg', '.jpeg', '.png'];
        const maxFileSizeMB = 3;

        const fileInput = input;
        const file = fileInput.files[0];
        const previewImage = document.getElementById('previewImageMataPelajaran');
        const defaultImage = `{{ $dataMataPelajaran->foto ? asset($dataMataPelajaran->foto) : asset('assets/dashboard/img/mapel.png') }}`;

        const fileExtension = '.' + file.name.split('.').pop().toLowerCase();
        if (!allowedExtensions.includes(fileExtension)) {
            alert('Jenis file tidak valid. Hanya file JPG, JPEG, atau PNG yang diizinkan.');
            fileInput.value = ''; // Membersihkan input file jika jenis file tidak valid
            previewImage.src = defaultImage;
            return;
        }

        const fileSizeMB = file.size / (1024 * 1024);
        if (fileSizeMB > maxFileSizeMB) {
            alert('Ukuran file melebihi batas maksimum (3MB).');
            fileInput.value = ''; // Membersihkan input file jika ukuran melebihi batas
            previewImage.src = defaultImage;
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImageMataPelajaran').src = e.target.result;
        };
        reader.readAsDataURL(file);
    }

</script>

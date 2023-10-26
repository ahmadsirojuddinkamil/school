<form action="{{ route('data.guru.update.teaching.hours', $biodataGuru->uuid) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label for="name" class="form-label">Nama</label>
        <input type="text" class="form-control" id="name" value="{{ $biodataGuru->name }}" disabled>
    </div>

    <div class="mb-3">
        <label for="mata_pelajaran" class="form-label">Mata Pelajaran</label>
        <input type="text" class="form-control" id="mata_pelajaran" value="{{ $biodataGuru->mata_pelajaran }}" disabled>
    </div>

    <div class="mb-3">
        <label for="jam_mengajar" class="form-label">Jam Mengajar</label>
        <input type="datetime-local" class="form-control" id="jam_mengajar" value="{{ $biodataGuru->jam_mengajar }}" name="jam_mengajar" required>
    </div>
    @error('jam_mengajar')
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
                        Data <span style="font-weight: bold">{{ $biodataGuru->name }}</span> akan berubah!
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
            previewImageSiswa.src = '{{ asset($biodataGuru->foto) }}';
        }
    });

    fileInput.addEventListener('click', function() {
        fileInput.value = null;
        previewImageSiswa.src = '{{ asset($biodataGuru->foto) }}';
    });

</script>

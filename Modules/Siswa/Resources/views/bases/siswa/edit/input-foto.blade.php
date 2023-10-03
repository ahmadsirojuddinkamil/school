<div class="col-lg-12">
    <div class="form-group">
        <label>Foto</label>
        <input type="file" id="fileInput" name="foto_new" accept=".jpg, .jpeg, .png" onchange="validateFile(this)">
        <input type="hidden" id="hiddenInput" value="{{ $getDataSiswa->foto }}" name="foto_old">
        <br><br>
        <img id="previewImageSiswa"
            src="{{ $getDataSiswa->foto ? asset($getDataSiswa->foto) : asset('assets/dashboard/img/warning.png') }}"
            alt="foto-siswa" height="100" width="100">
    </div>
</div>
@error('foto_new')
    <div class="alert alert-danger">{{ $message }}</div>
@enderror

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

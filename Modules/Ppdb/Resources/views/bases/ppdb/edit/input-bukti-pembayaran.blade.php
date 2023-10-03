<div class="col-lg-12">
    <div class="form-group">
        <label>Bukti Pendaftaran</label>
        <input type="file" id="fileInput" name="bukti_pendaftaran_new">
        <input type="hidden" id="hiddenInput" value="{{ $getDataUserPpdb->bukti_pendaftaran }}"
            name="bukti_pendaftaran_old">
        <br><br>
        <img id="previewImagePpdb"
            src="{{ $getDataUserPpdb->bukti_pendaftaran ? asset($getDataUserPpdb->bukti_pendaftaran) : asset('assets/dashboard/img/warning.png') }}"
            alt="img" height="100" width="100">
    </div>
</div>
@error('bukti_pendaftaran')
    <div class="alert alert-danger">{{ $message }}</div>
@enderror

{{-- preview image edit ppdb --}}
<script>
    const fileInput = document.getElementById('fileInput');
    const hiddenInput = document.getElementById('hiddenInput');
    const previewImagePpdb = document.getElementById('previewImagePpdb');
    let originalImageSrc = '{{ asset($getDataUserPpdb->bukti_pendaftaran) }}';

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

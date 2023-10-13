<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SMA Negeri Indonesia 1 | Ppdb Register</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>

    @include('news::bases.news.css')
    @include('news::bases.news.js')
</head>

<body>
    @include('news::layouts.news.header')

    <div class="container border">
        @include('ppdb::components.ppdb.alert-success')
        @include('ppdb::components.ppdb.alert-error')
        @include('ppdb::components.ppdb.form-register')
    </div>

    <script>
        const inputElement = document.getElementById('uang-pendaftaran');
        const imagePreviewElement = document.getElementById('image-preview');
        const imagePreviewContainer = document.getElementById('image-preview-container');

        inputElement.addEventListener('change', function() {
            const file = inputElement.files[0];

            if (file) {
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                const maxFileSizeMB = 3;
                const maxFileSizeBytes = maxFileSizeMB * 1024 * 1024;

                if (allowedTypes.includes(file.type) && file.size <= maxFileSizeBytes) {
                    const reader = new FileReader();

                    reader.onload = function() {
                        imagePreviewElement.src = reader.result;
                        imagePreviewElement.style.display = 'block';
                    }

                    reader.readAsDataURL(file);
                } else {
                    imagePreviewElement.src = '#';
                    imagePreviewElement.style.display = 'none';
                    inputElement.value = ''; // Reset nilai input file
                    alert('File harus berformat jpg, jpeg, atau png dan ukuran file harus kurang dari 3 MB.');
                }
            } else {
                imagePreviewElement.src = '#';
                imagePreviewElement.style.display = 'none';
            }
        });

        // Untuk menghapus pratinjau gambar saat input file ditekan lagi
        inputElement.addEventListener('click', function() {
            imagePreviewElement.src = '#';
            imagePreviewElement.style.display = 'none';
            inputElement.value = ''; // Reset nilai input file
        });

    </script>

    @include('news::layouts.news.footer')

</body>

</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard | Tambah Guru</title>
    @include('guru::bases.css')
    @include('guru::bases.js')
</head>

<body>
    <div id="global-loader">
        <div class="whirly-loader"> </div>
    </div>

    <div class="main-wrapper">
        @include('dashboard::layouts.header')
        @include('dashboard::layouts.sidebar')

        <div class="page-wrapper">
            <div class="content">
                <div class="page-header">
                    <div class="page-title">
                        <h4>Form Guru Baru</h4>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            @include('guru::components.form_create_new_guru')
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</body>

</html>

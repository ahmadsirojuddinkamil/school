<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard | Data Guru {{ $dataUserAuth[0]->name }}</title>
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
                        <h4>Biodata Guru</h4>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            @if (in_array($dataUserAuth[1], ['super_admin', 'guru']))
                            @include('guru::components.form-edit-biodata-guru')
                            @else
                            @include('guru::components.form-edit-teaching-hours-guru')
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</body>

</html>

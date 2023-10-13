<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard | data absen kelas : {{ $saveClassFromObjectCall }}</title>
    @include('absen::bases.css')
    @include('absen::bases.js')
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
                        <h4>Data Absen Siswa Kelas : {{ $saveClassFromObjectCall }} | Total : {{ $totalAbsen }} Laporan</h4>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="table-top">
                            <div class="search-set">
                                <div class="search-input">
                                    <a class="btn btn-searchset">
                                        <img src="{{ asset('assets/dashboard/img/icons/search-white.svg') }}" alt="img">
                                    </a>
                                </div>
                            </div>

                            @include('absen::components.sweetalert-success')
                            @include('absen::components.sweetalert-error')

                            <div class="wordset">
                                <ul>
                                    @include('absen::components.download-pdf-siswa')
                                </ul>
                            </div>
                        </div>

                        <div class="table-responsiv">
                            <table class="table  datanew">
                                <thead>
                                    <tr>
                                        <td>#</td>
                                        <th>Nama Lengkap</th>
                                        <th>NISN</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($dataAbsen as $absen)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $absen->name }}</td>
                                        <td>{{ $absen->nisn }}</td>
                                        <td class="actions">
                                            <a class="action-link" href="{{ route('data.absen.show', ['save_nisn_from_event' => $absen->nisn]) }}">
                                                <img src="{{ asset('assets/dashboard/img/icons/eye.svg') }}" alt="img">
                                            </a>

                                            <button class="action-button" data-bs-toggle="modal" data-bs-target="#modalDelete{{ $absen->nisn }}">
                                                <img src="{{ asset('assets/dashboard/img/icons/delete.svg') }}" alt="img">
                                            </button>

                                            <div class="modal fade" id="modalDelete{{ $absen->nisn, $absen->name }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Apakah anda
                                                                yakin?</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>

                                                        <div class="modal-body">
                                                            Data absen <span style="font-weight: bold">{{ $absen->name }}</span> akan terhapus!
                                                        </div>

                                                        <div class="modal-footer d-flex justify-content-end">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>

                                                            <form action="{{ route('data.absen.delete') }}" method="POST" class="action-form">
                                                                @csrf
                                                                @method('DELETE')
                                                                <input type="hidden" name="nisn" value="{{ $absen->nisn }}">
                                                                <button type="submit" class="btn btn-submit me-2">Submit</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.getElementById('downloadPdfLink').addEventListener('click', function(event) {
            event.preventDefault();
            document.getElementById('downloadPdfForm').submit();
        });

    </script>

</body>

</html>

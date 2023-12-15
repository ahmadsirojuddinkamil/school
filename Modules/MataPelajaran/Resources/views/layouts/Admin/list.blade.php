<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard | Data Mata Pelajaran</title>

    @include('matapelajaran::bases.css')
    @include('matapelajaran::bases.js')

    <style>
        .link-with-margin {
            margin-right: 15px;
        }

    </style>
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
                        <h4>Data Mata Pelajaran</h4>
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

                            @include('matapelajaran::components.sweetalert-success')
                            @include('matapelajaran::components.sweetalert-error')

                            <div class="wordset">
                                <ul>
                                    <a href="{{ route('create.mata.pelajaran') }}" class="link-with-margin">
                                        <img src="{{ asset('assets/dashboard/img/icons/plus.svg') }}" alt="img">
                                    </a>

                                    <a href="{{ route('download.all.mata.pelajaran', 'pdf') }}" class="link-with-margin">
                                        <img src="{{ asset('assets/dashboard/img/icons/pdf.svg') }}" alt="img">
                                    </a>

                                    <a href="{{ route('download.all.mata.pelajaran', 'excel') }}" class="link-with-margin">
                                        <img src="{{ asset('assets/dashboard/img/icons/excel.svg') }}" alt="img">
                                    </a>
                                </ul>
                            </div>
                        </div>

                        <div class="table-responsiv">
                            <table class="table  datanew">
                                <thead>
                                    <tr>
                                        <td>#</td>
                                        <th>Mapel</th>
                                        <th>Jam Awal</th>
                                        <th>Jam Akhir</th>
                                        <th>Kelas</th>
                                        <th>Guru</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($listMataPelajaran as $mapel)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $mapel->name }}</td>
                                        <td>{{ $mapel->jam_awal }}</td>
                                        <td>{{ $mapel->jam_akhir }}</td>
                                        <td>{{ $mapel->kelas }}</td>
                                        <td>{{ $mapel->guru ? $mapel->guru->name : '' }}</td>
                                        <td class="actions">
                                            <a class="action-link" href="{{ route('show.mata.pelajaran', $mapel->uuid) }}">
                                                <img src="{{ asset('assets/dashboard/img/icons/eye.svg') }}" alt="img">
                                            </a>

                                            <a class="action-link" href="{{ route('edit.mata.pelajaran', $mapel->uuid) }}">
                                                <img src="{{ asset('assets/dashboard/img/icons/edit.svg') }}" alt="img">
                                            </a>

                                            <button class="action-button" data-bs-toggle="modal" data-bs-target="#modalDelete">
                                                <img src="{{ asset('assets/dashboard/img/icons/delete.svg') }}" alt="img">
                                            </button>

                                            <div class="modal fade" id="modalDelete" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Apakah anda yakin?</h5>
                                                        </div>

                                                        <div class="modal-body">
                                                            Data mata pelajaran <span style="font-weight: bold"></span> akan terhapus!
                                                        </div>

                                                        <div class="modal-footer d-flex justify-content-end">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
                                                            <form action="{{ route('delete.mata.pelajaran', $mapel->uuid) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')

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

        document.getElementById('downloadExcelLink').addEventListener('click', function(event) {
            event.preventDefault();
            document.getElementById('downloadExcelForm').submit();
        });

    </script>

</body>

</html>

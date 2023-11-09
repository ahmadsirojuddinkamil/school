<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard | Data Guru</title>

    @include('guru::bases.css')
    @include('guru::bases.js')

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
                        <h4>Data Guru</h4>
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

                            @include('guru::components.sweetalert-success')
                            @include('guru::components.sweetalert-error')

                            <div class="wordset">
                                <ul>
                                    @if (in_array($dataUserAuth[1], ['super_admin']))
                                    @include('guru::components.add_new_guru')
                                    @endif

                                    <a href="{{ route('data.guru.download.list.pdf.zip') }}" class="link-with-margin">
                                        <img src="{{ asset('assets/dashboard/img/icons/pdf.svg') }}" alt="img">
                                    </a>

                                    <a href="{{ route('data.guru.download.list.excel.zip') }}" class="link-with-margin">
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
                                        <th>nama</th>
                                        <th>nuptk</th>
                                        <th>nip</th>
                                        {{-- <th>mata pelajaran</th> --}}
                                        <th>jam mengajar Awal</th>
                                        <th>jam mengajar Akhir</th>
                                        <th>no telpon</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($dataGuru as $guru)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $guru->name }}</td>
                                        <td>{{ $guru->nuptk }}</td>

                                        @if($guru->nip)
                                        <td>{{ $guru->nip }}</td>
                                        @else
                                        <td>belum ada</td>
                                        @endif

                                        {{-- <td>{{ $guru->mata_pelajaran }}</td> --}}
                                        <td>{{ $guru->jam_mengajar_awal }}</td>
                                        <td>{{ $guru->jam_mengajar_akhir }}</td>
                                        <td>{{ $guru->no_telpon }}</td>
                                        <td class="actions">
                                            <a class="action-link" href="{{ route('data.guru.show', $guru->uuid) }}">
                                                <img src="{{ asset('assets/dashboard/img/icons/eye.svg') }}" alt="img">
                                            </a>

                                            <a class="action-link" href="{{ $dataUserAuth[1] == 'super_admin' ? route('data.guru.edit', $guru->uuid) : route('data.guru.edit.teaching.hours', $guru->uuid) }}">
                                                <img src="{{ asset('assets/dashboard/img/icons/edit.svg') }}" alt="img">
                                            </a>

                                            @if($dataUserAuth[1] == 'super_admin')
                                            <button class="action-button" data-bs-toggle="modal" data-bs-target="#modalDelete{{ $guru->uuid }}">
                                                <img src="{{ asset('assets/dashboard/img/icons/delete.svg') }}" alt="img">
                                            </button>

                                            <div class="modal fade" id="modalDelete{{ $guru->uuid }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Apakah anda yakin?</h5>
                                                        </div>

                                                        <div class="modal-body">
                                                            Data guru <span style="font-weight: bold">{{ $guru->name }}</span> akan terhapus!
                                                        </div>

                                                        <div class="modal-footer d-flex justify-content-end">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
                                                            <form action="{{ route('data.guru.delete', $guru->uuid) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-submit me-2">Submit</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
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

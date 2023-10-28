<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard | siswa lulus</title>

    @include('siswa::bases.css')
    @include('siswa::bases.js')
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
                        <h4>Daftar Siswa Lulus</h4>
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

                            @include('siswa::components.sweetalert-success')
                            @include('siswa::components.sweetalert-error')

                            <div class="wordset">
                                <ul>
                                    @include('siswa::components.download-pdf-graduated')
                                    @include('siswa::components.download-excel-graduated')
                                </ul>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table  datanew">
                                <thead>
                                    <tr>
                                        <td>#</td>
                                        <th>Foto</th>
                                        <th>Nama</th>
                                        <th>NISN</th>
                                        <th>Telpon</th>
                                        <th>Jenis Kelamin</th>
                                        <th>Tahun Keluar</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($dataSiswa as $siswa)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>

                                        <td class="productimgname">
                                            <a href="javascript:void(0);" class="product-img">
                                                <img src="{{ asset($siswa->foto) }}" alt="product">
                                            </a>
                                        </td>

                                        <td>{{ $siswa->name }}</td>
                                        <td>{{ $siswa->nisn }}</td>
                                        <td>{{ $siswa->no_telpon }}</td>
                                        <td>{{ $siswa->jenis_kelamin }}</td>
                                        <td>{{ $siswa->tahun_keluar }}</td>
                                        <td class="actions">
                                            <a class="action-link" href="{{ route('siswa.graduated.show', [$siswa->uuid]) }}">
                                                <img src="{{ asset('assets/dashboard/img/icons/eye.svg') }}" alt="img">
                                            </a>

                                            <a class="action-link" href="{{ route('siswa.edit', [$siswa->uuid]) }}">
                                                <img src="{{ asset('assets/dashboard/img/icons/edit.svg') }}" alt="img">
                                            </a>

                                            @if($dataUserAuth[1] == 'super_admin')
                                            <button class="action-button" data-bs-toggle="modal" data-bs-target="#exampleModal{{ $siswa->uuid }}">
                                                <img src="{{ asset('assets/dashboard/img/icons/delete.svg') }}" alt="img">
                                            </button>

                                            <div class="modal fade" id="exampleModal{{ $siswa->uuid }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Apakah anda
                                                                yakin?</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>

                                                        <div class="modal-body">
                                                            Data ppdb akan terupdate!
                                                        </div>

                                                        <div class="modal-footer d-flex justify-content-end">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>

                                                            <form action="{{ route('siswa.delete', [$siswa->uuid]) }}" method="POST" class="action-form">
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

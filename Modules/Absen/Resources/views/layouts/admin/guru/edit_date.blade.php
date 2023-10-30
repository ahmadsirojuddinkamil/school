<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard | edit data absen siswa</title>
    @include('absen::bases.css')
    @include('absen::bases.js')
</head>

<body>
    <div id="global-loader">
        <div class="whirly-loader"> </div>
    </div>

    @include('absen::components.sweetalert-success')
    @include('absen::components.sweetalert-error')

    <div class="main-wrapper">
        @include('dashboard::layouts.header')
        @include('dashboard::layouts.sidebar')

        <div class="page-wrapper">
            <div class="content">
                <div class="page-header">
                    <div class="page-title">
                        <h4>Edit data absen</h4>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <form action="{{ route('data.absen.guru.date.update', ['save_uuid_from_event' => $dataAbsen->uuid]) }}" method="POST">
                                @method('PUT')
                                @csrf

                                <div class="mb-4">
                                    <label class="form-label">Keterangan</label>
                                    <select class="form-select" name="keterangan" required>
                                        @foreach (['hadir', 'sakit', 'acara', 'musibah', 'tidak_hadir'] as $option)
                                        <option value="{{ $option }}" {{ $dataAbsen->keterangan == $option ? 'selected' : '' }}>
                                            {{ ucfirst($option) }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('keterangan')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror

                                <div class="mb-3">
                                    <label class="form-label">Tanggal Absen</label>
                                    <h6>{{ $dataAbsen->updated_at}}</h6>
                                </div>

                                <div class="col-lg-12">
                                    <button type="button" class="btn btn-submit me-2" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                        Submit
                                    </button>

                                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Apakah anda yakin?</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>

                                                <div class="modal-body">
                                                    Data absen guru akan terupdate!
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

                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

</body>

</html>

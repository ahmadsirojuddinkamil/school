<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard | absen</title>
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
                        <h4>Absen</h4>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            @if ($checkAbsenOrNot)
                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="alert-success p-3">Anda sudah melakukan absen!</div>
                            </div>
                            @else
                            <form action="{{ route('page.absen.store') }}" method="POST">
                                @csrf

                                <div class="form-group">
                                    <input type="hidden" value="setuju" name="persetujuan" readonly required>
                                    @if($dataUserAuth[1] == 'siswa')
                                    <input type="hidden" value="{{ $dataUserAuth[0]->siswa->kelas }}" name="status" readonly required>
                                    @elseif($dataUserAuth[1] == 'guru')
                                    <input type="hidden" value="guru" name="status" readonly required>
                                    @endif
                                </div>

                                <div class="col-lg-6 col-sm-6 col-12 mb-4">
                                    <div class="alert-danger p-3">Saya setuju jika melakukan penipuan absen akan
                                        mendapatkan skor dan peringatan!</div>
                                </div>

                                <div class="col-lg-12">
                                    <button type="submit" class="btn btn-submit me-2 not-izin">Absen</button>

                                    <button type="button" class="btn btn-submit btn btn-added me-2" data-bs-toggle="modal" data-bs-target="#exampleModal">Izin?</button>

                                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Izin untuk absen?</h5>
                                                </div>

                                                <div class="modal-body">
                                                    <div class="mb-4">
                                                        <label class="form-label">Pilihan Izin :</label>
                                                        <select class="form-select" name="keterangan">
                                                            <option value="hadir">Tidak ada</option>
                                                            <option value="sakit">Sakit</option>
                                                            <option value="acara">Acara Keluarga</option>
                                                            <option value="musibah">Musibah / Banjir, Kebakaran Dll</option>
                                                        </select>
                                                    </div>
                                                    @error('keterangan')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="modal-footer d-flex justify-content-end">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>

                                                    <button type="submit" class="btn btn-primary yes-izin">Ya!</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <a href="/dashboard" class="btn btn-cancel">Cancel</a>
                                </div>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectElement = document.querySelector('select[name="keterangan"]');
            const submitButton = document.querySelector('.not-izin');
            const izinButton = document.querySelector('.yes-izin');

            izinButton.disabled = true;

            selectElement.addEventListener('change', function() {
                const selectedOption = selectElement.value;

                if (selectedOption === 'sakit' || selectedOption === 'acara' || selectedOption ===
                    'musibah') {
                    submitButton.disabled =
                        true;
                    izinButton.disabled = false;
                } else if (selectedOption === 'hadir') {
                    submitButton.disabled =
                        false;
                    izinButton.disabled = true;
                } else {
                    submitButton.disabled = false;
                    izinButton.disabled = false;
                }
            });
        });

    </script>
</body>

</html>

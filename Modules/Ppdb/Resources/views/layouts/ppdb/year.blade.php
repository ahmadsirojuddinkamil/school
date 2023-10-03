<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Data PPDB</h4>
            </div>

            @if ($openOrClosePpdb)
                <button type="button" class="btn btn-added" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    <img src="{{ asset('assets/dashboard/img/icons/plus.svg') }}" alt="img" class="me-2">
                    Tutup Pendaftaran PPDB
                </button>

                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Penutupan Pendaftaran PPDB</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                                Apakah anda yakin untuk menutup pendaftaran ppdb?
                                <strong>{{ $findOpenOrClosePpdb->tanggal_mulai }}</strong>
                                sampai
                                <strong>{{ $findOpenOrClosePpdb->tanggal_akhir }}</strong>
                            </div>


                            <div class="modal-footer d-flex justify-content-end">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>

                                <form action="{{ route('ppdb.deleteOpenPpdb') }}" method="POST">
                                    @csrf
                                    @method('delete')

                                    <button type="submit" class="btn btn-primary">Ya!</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <button type="button" class="btn btn-added" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    <img src="{{ asset('assets/dashboard/img/icons/plus.svg') }}" alt="img" class="me-2">
                    Buka Pendaftaran PPDB
                </button>

                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Pendaftaran PPDB</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>

                            <form action="{{ route('ppdb.open') }}" method="POST">
                                <div class="modal-body">
                                    @csrf

                                    <div class="mb-3">
                                        <label for="tanggal-mulai-ppdb" class="form-label">Tanggal Mulai PPDB</label>
                                        <input type="date" class="form-control" id="tanggal-mulai-ppdb"
                                            name="tanggal_mulai" required min="{{ $timeBox['todayDate'] }}"
                                            max="{{ $timeBox['maxDate'] }}">
                                    </div>
                                    @error('tanggal_mulai')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror

                                    <div class="mb-3">
                                        <label for="tanggal-akhir-ppdb" class="form-label">Tanggal Berakhir PPDB</label>
                                        <input type="date" class="form-control" id="tanggal-akhir-ppdb"
                                            name="tanggal_akhir" required min="{{ $timeBox['todayDate'] }}"
                                            max="{{ $timeBox['maxDate'] }}">
                                    </div>
                                    @error('tanggal_akhir')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="modal-footer d-flex justify-content-end">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Tidak</button>

                                    <button type="submit" class="btn btn-primary">Ya!</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        @include('ppdb::components.ppdb.sweetalert-success')
        @include('ppdb::components.ppdb.data-year-ppdb')

    </div>
</div>

<script>
    const tanggalMulai = document.getElementById('tanggal-mulai-ppdb');
    const tanggalAkhir = document.getElementById('tanggal-akhir-ppdb');

    tanggalMulai.addEventListener('input', function() {
        const mulaiValue = new Date(tanggalMulai.value);

        const minimumTanggalAkhir = new Date(mulaiValue);
        minimumTanggalAkhir.setDate(mulaiValue.getDate() + 1);
        tanggalAkhir.min = minimumTanggalAkhir.toISOString().split('T')[0];

        if (new Date(tanggalAkhir.value) < minimumTanggalAkhir) {
            tanggalAkhir.value = minimumTanggalAkhir.toISOString().split('T')[0];
        }
    });
</script>

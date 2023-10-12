<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Status Siswa</h4>
            </div>
        </div>

        @include('siswa::components.siswa.sweetalert-success')
        @include('siswa::components.siswa.sweetalert-error')

        <div class="row">
            <div class="col-lg-3 col-sm-6 col-12">
                <a href="{{ route('siswa.active') }}">
                    <div class="dash-count das1">
                        <div class="dash-counts">
                            <h4>Belum Lulus</h4>
                            <h5>total : {{ $getStatusSiswa['belum_lulus'] }} siswa</h5>
                        </div>

                        <div class="dash-imgs">
                            <i data-feather="user-x"></i>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-sm-6 col-12">
                <a href="{{ route('siswa.graduated') }}">
                    <div class="dash-count das1">
                        <div class="dash-counts">
                            <h4>Sudah Lulus</h4>
                            <h5>total : {{ $getStatusSiswa['sudah_lulus'] }} alumni</h5>
                        </div>

                        <div class="dash-imgs">
                            <i data-feather="user-check"></i>
                        </div>
                    </div>
                </a>
            </div>
        </div>

    </div>
</div>

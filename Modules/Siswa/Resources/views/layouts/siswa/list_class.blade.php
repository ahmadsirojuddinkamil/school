<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Daftar Kelas</h4>
            </div>
        </div>

        @include('siswa::components.siswa.sweetalert-success')
        @include('siswa::components.siswa.sweetalert-error')

        <div class="row">
            <div class="col-lg-3 col-sm-6 col-12">
                <a href="{{ route('siswa.active.class', 12) }}">
                    <div class="dash-count das1">
                        <div class="dash-counts">
                            <h4>Kelas 12</h4>
                            <h5>Jumlah Siswa : {{ $getListClassSiswa['12'] }}</h5>
                        </div>

                        <div class="dash-imgs">
                            <i data-feather="users"></i>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-sm-6 col-12">
                <a href="{{ route('siswa.active.class', 11) }}">
                    <div class="dash-count das1">
                        <div class="dash-counts">
                            <h4>Kelas 11</h4>
                            <h5>Jumlah Siswa : {{ $getListClassSiswa['11'] }}</h5>
                        </div>

                        <div class="dash-imgs">
                            <i data-feather="users"></i>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-sm-6 col-12">
                <a href="{{ route('siswa.active.class', 10) }}">
                    <div class="dash-count das1">
                        <div class="dash-counts">
                            <h4>Kelas 10</h4>
                            <h5>Jumlah Siswa : {{ $getListClassSiswa['10'] }}</h5>
                        </div>

                        <div class="dash-imgs">
                            <i data-feather="users"></i>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

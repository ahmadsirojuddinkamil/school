<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Daftar Absen Siswa</h4>
            </div>
        </div>

        @include('absen::components.absen.sweetalert-success')
        @include('absen::components.absen.sweetalert-error')

        <div class="row">
            <div class="col-lg-3 col-sm-6 col-12">
                <a href="{{ route('data.absen.class', 12) }}">
                    <div class="dash-count das1">
                        <div class="dash-counts">
                            <h4>Kelas 12</h4>
                        </div>

                        <div class="dash-imgs">
                            <i data-feather="book-open"></i>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-sm-6 col-12">
                <a href="{{ route('data.absen.class', 11) }}">
                    <div class="dash-count das1">
                        <div class="dash-counts">
                            <h4>Kelas 11</h4>
                        </div>

                        <div class="dash-imgs">
                            <i data-feather="book-open"></i>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-sm-6 col-12">
                <a href="{{ route('data.absen.class', 10) }}">
                    <div class="dash-count das1">
                        <div class="dash-counts">
                            <h4>Kelas 10</h4>
                        </div>

                        <div class="dash-imgs">
                            <i data-feather="book-open"></i>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

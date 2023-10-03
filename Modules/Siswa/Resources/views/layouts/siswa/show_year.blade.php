<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Siswa tahun {{ $saveYearFromRoute }} : {{ $getTotalSiswa }} peserta</h4>
            </div>
        </div>

        <div class="row">
            @foreach ($getAllSiswaClass as $class)
                <div class="col-lg-3 col-sm-6 col-12">
                    <a href="">
                        <div class="dash-count das1">
                            <div class="dash-counts">
                                <h4>Kelas {{ $class['key'] }}</h4>
                                <h5>Jumlah Siswa : {{ $class['value'] }}</h5>
                            </div>

                            {{-- {{ route('siswa.show.year', ['save_year_from_event' => $class['key']]) }} --}}

                            <div class="dash-imgs">
                                <i data-feather="user-check"></i>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

    </div>
</div>

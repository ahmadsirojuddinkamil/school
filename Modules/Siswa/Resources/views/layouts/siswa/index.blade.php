<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Data Siswa</h4>
            </div>
        </div>

        {{-- @dd($classTotals) --}}

        <div class="row">
            @foreach ($classTotals as $class)
                <div class="col-lg-3 col-sm-6 col-12">
                    <a href="{{ route('siswa.class.show', ['save_class_from_event' => $class['key']]) }}">
                        <div class="dash-count das1">
                            <div class="dash-counts">
                                <h4>Kelas {{ $class['key'] }}</h4>
                                <h5>Jumlah Siswa : {{ $class['value'] }}</h5>
                            </div>

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

<div class="row">
    @if ($yearTotals)
        @foreach ($yearTotals as $year)
            <div class="col-lg-3 col-sm-6 col-12">
                <a href="{{ route('ppdb.year.show', ['save_year_from_event' => $year['key']]) }}">
                    <div class="dash-count das1">
                        <div class="dash-counts">
                            <h4>{{ $year['key'] }}</h4>
                            <h5>Data Ppdb: {{ $year['value'] }}</h5>
                        </div>

                        <div class="dash-imgs">
                            <i data-feather="user-check"></i>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    @else
        <h3>Tidak ada data ppdb!</h3>
    @endif
</div>

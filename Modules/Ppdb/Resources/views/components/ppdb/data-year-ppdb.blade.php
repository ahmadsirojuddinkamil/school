<div class="row">
    @if ($listYearPpdb)
    @foreach ($listYearPpdb as $year)
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
    <div class="d-flex justify-content-center">

        <div class=" text-center alert-danger p-3 m-2 w-50">Data ppdb belum ada!</div>
    </div>
    @endif
</div>

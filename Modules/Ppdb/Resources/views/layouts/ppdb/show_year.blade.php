<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>PPDB tahun {{ $getDataPpdb[0]->tahun_daftar }} : {{ $totalDataPpdb }} peserta</h4>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-top">
                    <div class="search-set">
                        <div class="search-input">
                            <a class="btn btn-searchset"><img
                                    src="{{ asset('assets/dashboard/img/icons/search-white.svg') }}" alt="img"></a>
                        </div>
                    </div>

                    @include('ppdb::components.ppdb.sweetalert-success')

                    <div class="wordset">
                        <ul>
                            @include('ppdb::components.ppdb.download-pdf')
                            @include('ppdb::components.ppdb.download-excel')
                        </ul>
                    </div>
                </div>

                @include('ppdb::components.ppdb.data-ppdb-table')
            </div>
        </div>

    </div>
</div>

<script>
    document.getElementById('downloadPdfLink').addEventListener('click', function(event) {
        event.preventDefault();
        document.getElementById('downloadPdfForm').submit();
    });

    document.getElementById('downloadExcelLink').addEventListener('click', function(event) {
        event.preventDefault();
        document.getElementById('downloadExcelForm').submit();
    });
</script>

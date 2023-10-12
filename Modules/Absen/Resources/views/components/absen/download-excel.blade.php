<form id="downloadExcelForm"
    action="{{ route('ppdb.download.excel', ['save_year_from_event' => $getDataPpdb[0]->tahun_daftar]) }}" method="POST">
    @csrf
    <li>
        <a id="downloadExcelLink" data-bs-toggle="tooltip" data-bs-placement="top" title="excel"><img
                src="{{ asset('assets/dashboard/img/icons/excel.svg') }}" alt="img"></a>
    </li>
</form>

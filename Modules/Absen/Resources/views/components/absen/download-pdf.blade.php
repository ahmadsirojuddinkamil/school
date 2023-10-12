<form id="downloadPdfForm"
    action="{{ route('ppdb.download.pdf', ['save_year_from_event' => $getDataPpdb[0]->tahun_daftar]) }}" method="POST">
    @csrf
    <li>
        <a id="downloadPdfLink" data-bs-toggle="tooltip" data-bs-placement="top" title="pdf"><img
                src="{{ asset('assets/dashboard/img/icons/pdf.svg') }}" alt="img"></a>
    </li>
</form>

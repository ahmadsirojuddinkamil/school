@if ($checkSiswaOrNot)
<div>
    <a href="{{ route('ppdb.download.pdf', ['save_uuid_from_event' => $getDataUserPpdb->uuid]) }}" title="pdf">
        <img src="{{ asset('assets/dashboard/img/icons/pdf.svg') }}" alt="img">
    </a>

    <a href="{{ route('ppdb.download.excel', ['save_uuid_from_event' => $getDataUserPpdb->uuid]) }}" title="excel" style="margin-left: 10px">
        <img src="{{ asset('assets/dashboard/img/icons/excel.svg') }}" alt="img">
    </a>
</div>

@else
<button type="button" class="btn btn-added" data-bs-toggle="modal" data-bs-target="#exampleModal">
    <img src="{{ asset('assets/dashboard/img/icons/plus.svg') }}" alt="img" class="me-2">
    Terima PPDB
</button>

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Apakah anda yakin?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                Email notifikasi akan otomatis terkirim ke peserta ppdb!
            </div>

            <div class="modal-footer d-flex justify-content-end">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>

                <form action="{{ route('ppdb.accept', ['save_uuid_from_event' => $getDataUserPpdb->uuid]) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary">Ya!</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

<div class="table-responsive">
    <table class="table  datanew">
        <thead>
            <tr>
                <td>#</td>
                <th>Nama</th>
                <th>NISN</th>
                <th>Asal Sekolah</th>
                <th>Telpon</th>
                <th>Jenis Kelamin</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($dataPpdb as $ppdb)
            <tr>
                <td>{{ $loop->iteration }}</td>

                <td>{{ $ppdb->name }}</td>
                <td>{{ $ppdb->nisn }}</td>
                <td>{{ $ppdb->asal_sekolah }}</td>
                <td>{{ $ppdb->telpon_siswa }}</td>
                <td>{{ $ppdb->jenis_kelamin }}</td>
                <td class="actions">

                    <a class="action-link" href="{{ route('ppdb.user.show', ['save_uuid_from_event' => $ppdb->uuid]) }}">
                        <img src="{{ asset('assets/dashboard/img/icons/eye.svg') }}" alt="img">
                    </a>

                    <a class="action-link" href="{{ route('ppdb.show.edit', ['save_uuid_from_event' => $ppdb->uuid]) }}">
                        <img src="{{ asset('assets/dashboard/img/icons/edit.svg') }}" alt="img">
                    </a>

                    @if($dataUserAuth[1] == 'super_admin')
                    <a class="action-link" type="button" data-bs-toggle="modal" data-bs-target="#exampleModal{{ $ppdb->uuid }}">
                        <img src="{{ asset('assets/dashboard/img/icons/delete.svg') }}" alt="img">
                    </a>

                    <div class="modal fade" id="exampleModal{{ $ppdb->uuid }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Apakah
                                        anda yakin?</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>

                                <div class="modal-body">
                                    Data ppdb akan terhapus!
                                </div>

                                <div class="modal-footer d-flex justify-content-end">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>

                                    <form action="{{ route('ppdb.delete', ['save_uuid_from_event' => $ppdb->uuid]) }}" method="POST" class="action-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-submit me-2">Ya!</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </td>

            </tr>
            @endforeach
        </tbody>
    </table>
</div>

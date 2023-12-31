<div class="table-responsive">
    <table class="table  datanew">
        <thead>
            <tr>
                <td>#</td>
                <th>Nama Lengkap</th>
                <th>NISN</th>
                <th>Asal Sekolah</th>
                <th>Telpon</th>
                <th>Jenis Kelamin</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($getSiswaFromYear as $siswa)
                <tr>
                    <td>{{ $loop->iteration }}</td>

                    <td>{{ $siswa->nama_lengkap }}</td>
                    <td>{{ $siswa->nisn }}</td>
                    <td>{{ $siswa->asal_sekolah }}</td>
                    <td>{{ $siswa->telpon_siswa }}</td>
                    <td>{{ $siswa->jenis_kelamin }}</td>
                    <td class="actions">

                        <a class="action-link"
                            href="{{ route('ppdb.user.show', ['save_uuid_from_event' => $siswa->uuid]) }}">
                            <img src="{{ asset('assets/dashboard/img/icons/eye.svg') }}" alt="img">
                        </a>

                        <a class="action-link"
                            href="{{ route('ppdb.show.edit', ['save_uuid_from_event' => $siswa->uuid]) }}">
                            <img src="{{ asset('assets/dashboard/img/icons/edit.svg') }}" alt="img">
                        </a>

                        <a class="action-link" type="button" data-bs-toggle="modal"
                            data-bs-target="#exampleModal{{ $siswa->uuid }}">
                            <img src="{{ asset('assets/dashboard/img/icons/delete.svg') }}" alt="img">
                        </a>

                        <div class="modal fade" id="exampleModal{{ $siswa->uuid }}" tabindex="-1"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Apakah
                                            anda yakin?</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>

                                    <div class="modal-body">
                                        Data ppdb akan terhapus!
                                    </div>

                                    <div class="modal-footer d-flex justify-content-end">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Tidak</button>

                                        <form
                                            action="{{ route('ppdb.delete', ['save_uuid_from_event' => $siswa->uuid]) }}"
                                            method="POST" class="action-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-submit me-2">Ya!</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>
</div>

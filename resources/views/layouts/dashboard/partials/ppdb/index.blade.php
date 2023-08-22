<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Peserta PPDB</h4>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-top">
                    <div class="search-set">
                        <div class="search-input">
                            <a class="btn btn-searchset">
                                <img src="assets/dashboard/img/icons/search-white.svg" alt="img">
                            </a>
                        </div>
                    </div>

                    @if (session()->has('success'))
                        <div class="alert alert-success d-flex justify-content-center mt-3" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="wordset">
                        <ul>
                            <li>
                                <a data-bs-toggle="tooltip" data-bs-placement="top" title="pdf"><img
                                        src="assets/dashboard/img/icons/pdf.svg" alt="img"></a>
                            </li>
                            <li>
                                <a data-bs-toggle="tooltip" data-bs-placement="top" title="excel"><img
                                        src="assets/dashboard/img/icons/excel.svg" alt="img"></a>
                            </li>
                            <li>
                                <a data-bs-toggle="tooltip" data-bs-placement="top" title="print"><img
                                        src="assets/dashboard/img/icons/printer.svg" alt="img"></a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table  datanew">
                        <thead>
                            <tr>
                                <td>#</td>
                                <th>Foto</th>
                                <th>Nama Lengkap</th>
                                <th>NISN</th>
                                <th>Asal Sekolah</th>
                                <th>Telpon</th>
                                <th>Jenis Kelamin</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($getDataPpdb as $dataPpdb)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>

                                    <td class="productimgname">
                                        <a href="javascript:void(0);" class="product-img">
                                            <img src="assets/dashboard/img/customer/customer1.jpg" alt="product">
                                        </a>
                                    </td>

                                    <td>{{ $dataPpdb->nama_lengkap }}</td>
                                    <td>{{ $dataPpdb->nisn }}</td>
                                    <td>{{ $dataPpdb->asal_sekolah }}</td>
                                    <td>{{ $dataPpdb->telpon_siswa }}</td>
                                    <td>{{ $dataPpdb->jenis_kelamin }}</td>
                                    <td class="actions">
                                        <a class="action-link" href="ppdb-data/{{ $dataPpdb->uuid }}">
                                            <img src="assets/dashboard/img/icons/eye.svg" alt="img">
                                        </a>

                                        <a class="action-link" href="ppdb-data/{{ $dataPpdb->uuid }}/edit">
                                            <img src="assets/dashboard/img/icons/edit.svg" alt="img">
                                        </a>

                                        <form action="ppdb-data/{{ $dataPpdb->uuid }}" method="POST"
                                            class="action-form">
                                            @csrf
                                            @method('DELETE')

                                            <button class="action-button" type="submit">
                                                <img src="assets/dashboard/img/icons/delete.svg" alt="img">
                                            </button>
                                        </form>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

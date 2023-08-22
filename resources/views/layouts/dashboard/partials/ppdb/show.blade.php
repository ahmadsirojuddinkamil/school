<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Peserta PPDB Details</h4>
            </div>

            <button type="button" class="btn btn-added" data-bs-toggle="modal" data-bs-target="#exampleModal">
                <img src="{{ asset('assets/dashboard/img/icons/plus.svg') }}" alt="img" class="me-2">
                Terima PPDB
            </button>

            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Apakah anda yakin?</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            Email notifikasi akan otomatis terkirim ke peserta ppdb!
                        </div>

                        <div class="modal-footer d-flex justify-content-end">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>

                            <form action="/ppdb-data/{{ $getDataUserPpdb->uuid }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary">Ya!</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="productdetails">
                            <ul class="product-bar">
                                <li>
                                    <h4>Nama Lengkap</h4>
                                    <h6>{{ $getDataUserPpdb->nama_lengkap }}</h6>
                                </li>

                                <li>
                                    <h4>Email</h4>
                                    <h6>{{ $getDataUserPpdb->email }}</h6>
                                </li>

                                <li>
                                    <h4>NISN</h4>
                                    <h6>{{ $getDataUserPpdb->nisn }}</h6>
                                </li>

                                <li>
                                    <h4>Asal Sekolah</h4>
                                    <h6>{{ $getDataUserPpdb->asal_sekolah }}</h6>
                                </li>

                                <li>
                                    <h4>Alamat</h4>
                                    <h6>{{ $getDataUserPpdb->alamat }}</h6>
                                </li>

                                <li>
                                    <h4>Telpon Siswa</h4>
                                    <h6>{{ $getDataUserPpdb->telpon_siswa }}</h6>
                                </li>

                                <li>
                                    <h4>Jenis Kelamin</h4>
                                    <h6>{{ $getDataUserPpdb->jenis_kelamin }}</h6>
                                </li>

                                <li>
                                    <h4>Tempat Lahir</h4>
                                    <h6>{{ $getDataUserPpdb->tempat_lahir }}</h6>
                                </li>

                                <li>
                                    <h4>Tanggal Lahir</h4>
                                    <h6>{{ $getDataUserPpdb->tanggal_lahir }}</h6>
                                </li>

                                <li>
                                    <h4>Jurusan</h4>
                                    <h6>{{ $getDataUserPpdb->jurusan }}</h6>
                                </li>

                                <li>
                                    <h4>Nama Ayah</h4>
                                    <h6>{{ $getDataUserPpdb->nama_ayah }}</h6>
                                </li>

                                <li>
                                    <h4>Nama Ibu</h4>
                                    <h6>{{ $getDataUserPpdb->nama_ibu }}</h6>
                                </li>

                                <li>
                                    <h4>Telpon Orang Tua</h4>
                                    <h6>{{ $getDataUserPpdb->telpon_orang_tua }}</h6>
                                </li>

                                <li>
                                    <h4>Tahun Daftar</h4>
                                    <h6>{{ $getDataUserPpdb->tahun_daftar }}</h6>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-lg-4 col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="slider-product-details">
                            <div class="owl-carousel owl-theme product-slide">
                                <div class="slider-product">
                                    <img src="{{ asset($getDataUserPpdb->bukti_pendaftaran) }}" alt="img">
                                    <h4>Bukti Pendaftaran</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

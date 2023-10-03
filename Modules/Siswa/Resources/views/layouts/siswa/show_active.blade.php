<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Biodata Siswa</h4>
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
                                    <h6>{{ $getDataSiswa->nama_lengkap }}</h6>
                                </li>

                                <li>
                                    <h4>Email</h4>
                                    <h6>{{ $getDataSiswa->email }}</h6>
                                </li>

                                <li>
                                    <h4>NISN</h4>
                                    <h6>{{ $getDataSiswa->nisn }}</h6>
                                </li>

                                <li>
                                    <h4>Asal Sekolah</h4>
                                    <h6>{{ $getDataSiswa->asal_sekolah }}</h6>
                                </li>

                                <li>
                                    <h4>Kelas</h4>
                                    <h6>{{ $getDataSiswa->kelas }}</h6>
                                </li>

                                <li>
                                    <h4>Alamat</h4>
                                    <h6>{{ $getDataSiswa->alamat }}</h6>
                                </li>

                                <li>
                                    <h4>Telpon Siswa</h4>
                                    <h6>{{ $getDataSiswa->telpon_siswa }}</h6>
                                </li>

                                <li>
                                    <h4>Jenis Kelamin</h4>
                                    <h6>{{ $getDataSiswa->jenis_kelamin }}</h6>
                                </li>

                                <li>
                                    <h4>Tempat Lahir</h4>
                                    <h6>{{ $getDataSiswa->tempat_lahir }}</h6>
                                </li>

                                <li>
                                    <h4>Tanggal Lahir</h4>
                                    <h6>{{ $getDataSiswa->tanggal_lahir }}</h6>
                                </li>

                                <li>
                                    <h4>Tahun Daftar</h4>
                                    <h6>{{ $getDataSiswa->tahun_daftar }}</h6>
                                </li>

                                <li>
                                    <h4>Jurusan</h4>
                                    <h6>{{ $getDataSiswa->jurusan }}</h6>
                                </li>

                                <li>
                                    <h4>Nama Ayah</h4>
                                    <h6>{{ $getDataSiswa->nama_ayah }}</h6>
                                </li>

                                <li>
                                    <h4>Nama Ibu</h4>
                                    <h6>{{ $getDataSiswa->nama_ibu }}</h6>
                                </li>

                                <li>
                                    <h4>Telpon Orang Tua</h4>
                                    <h6>{{ $getDataSiswa->telpon_orang_tua }}</h6>
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
                                    <img src="{{ $getDataSiswa->foto ? asset($getDataSiswa->foto) : 'https://static.vecteezy.com/system/resources/previews/008/442/086/non_2x/illustration-of-human-icon-user-symbol-icon-modern-design-on-blank-background-free-vector.jpg' }}"
                                        alt="img">
                                    <h4>Foto</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

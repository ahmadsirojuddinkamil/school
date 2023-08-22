<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Edit PPDB</h4>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="row">
                    <form action="/ppdb-data/{{ $getPpdb->uuid }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text" value="{{ $getPpdb->nama_lengkap }}" name="nama_lengkap" required>
                        </div>
                        @error('nama_lengkap')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror

                        <div class="form-group">
                            <label>Email</label>
                            <input type="text" value="{{ $getPpdb->email }}" name="email" required>
                        </div>
                        @error('email')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror

                        <div class="form-group">
                            <label>NISN</label>
                            <input type="text" value="{{ $getPpdb->nisn }}" name="nisn" required>
                        </div>
                        @error('nisn')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror

                        <div class="form-group">
                            <label>Asal Sekolah</label>
                            <input type="text" value="{{ $getPpdb->asal_sekolah }}" name="asal_sekolah" required>
                        </div>
                        @error('asal_sekolah')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror

                        <div class="form-group">
                            <label>Alamat</label>
                            <input type="text" value="{{ $getPpdb->alamat }}" name="alamat" required>
                        </div>
                        @error('alamat')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror

                        <div class="form-group">
                            <label>Telpon Siswa</label>
                            <input type="text" value="{{ $getPpdb->telpon_siswa }}" name="telpon_siswa" required>
                        </div>
                        @error('telpon_siswa')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror

                        <div class="form-group">
                            <label>Jenis Kelamin</label>
                            <input type="text" value="{{ $getPpdb->jenis_kelamin }}" name="jenis_kelamin" required>
                        </div>
                        @error('jenis_kelamin')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror

                        <div class="form-group">
                            <label>Tempat Lahir</label>
                            <input type="text" value="{{ $getPpdb->tempat_lahir }}" name="tempat_lahir" required>
                        </div>
                        @error('tempat_lahir')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror

                        <div class="form-group">
                            <label>Tanggal Lahir</label>
                            <input type="text" value="{{ $getPpdb->tanggal_lahir }}" name="tanggal_lahir" required>
                        </div>
                        @error('tanggal_lahir')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror

                        <div class="form-group">
                            <label>Jurusan</label>
                            <input type="text" value="{{ $getPpdb->jurusan }}" name="jurusan" required>
                        </div>
                        @error('jurusan')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror

                        <div class="form-group">
                            <label>Nama Ayah</label>
                            <input type="text" value="{{ $getPpdb->nama_ayah }}" name="nama_ayah" required>
                        </div>
                        @error('nama_ayah')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror

                        <div class="form-group">
                            <label>Nama Ibu</label>
                            <input type="text" value="{{ $getPpdb->nama_ibu }}" name="nama_ibu" required>
                        </div>
                        @error('nama_ibu')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror

                        <div class="form-group">
                            <label>Telpon Orang Tua</label>
                            <input type="text" value="{{ $getPpdb->telpon_orang_tua }}" name="telpon_orang_tua"
                                required>
                        </div>
                        @error('telpon_orang_tua')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror

                        <div class="form-group">
                            <label>Tahun Daftar</label>
                            <input type="text" value="{{ $getPpdb->tahun_daftar }}" name="tahun_daftar" required>
                        </div>
                        @error('tahun_daftar')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror

                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Bukti Pendaftaran</label>
                                <img src="{{ asset($getPpdb->bukti_pendaftaran) }}" alt="img" height="100"
                                    width="100">
                                <input type="hidden" value="{{ $getPpdb->bukti_pendaftaran }}"
                                    name="bukti_pendaftaran">
                            </div>
                        </div>
                        @error('bukti_pendaftaran')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror

                        <div class="col-lg-12">
                            <button type="button" class="btn btn-submit me-2" data-bs-toggle="modal"
                                data-bs-target="#exampleModal">
                                Submit
                            </button>

                            <a href="/ppdb-data" class="btn btn-cancel">Cancel</a>

                            <div class="modal fade" id="exampleModal" tabindex="-1"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Apakah anda yakin?</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>

                                        <div class="modal-body">
                                            Data ppdb akan terupdate!
                                        </div>

                                        <div class="modal-footer d-flex justify-content-end">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Tidak</button>
                                            <button type="submit" class="btn btn-submit me-2">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>

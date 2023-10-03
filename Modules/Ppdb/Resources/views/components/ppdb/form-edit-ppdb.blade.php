<form action="{{ route('ppdb.update', ['save_uuid_from_event' => $getDataUserPpdb->uuid]) }}" method="POST"
    enctype="multipart/form-data">
    @csrf
    @method('PUT')

    @include('ppdb::bases.ppdb.edit.input-email')
    @include('ppdb::bases.ppdb.edit.input-nisn')
    @include('ppdb::bases.ppdb.edit.input-nama-siswa')
    @include('ppdb::bases.ppdb.edit.input-sekolah')
    @include('ppdb::bases.ppdb.edit.input-alamat')
    @include('ppdb::bases.ppdb.edit.input-telpon-siswa')
    @include('ppdb::bases.ppdb.edit.input-jenis-kelamin')
    @include('ppdb::bases.ppdb.edit.input-tempat-lahir')
    @include('ppdb::bases.ppdb.edit.input-tanggal-lahir')
    @include('ppdb::bases.ppdb.edit.input-tahun-daftar')
    @include('ppdb::bases.ppdb.edit.input-jurusan')
    @include('ppdb::bases.ppdb.edit.input-nama-ayah')
    @include('ppdb::bases.ppdb.edit.input-nama-ibu')
    @include('ppdb::bases.ppdb.edit.input-telpon-orang-tua')
    @include('ppdb::bases.ppdb.edit.input-bukti-pembayaran')
    @include('ppdb::bases.ppdb.edit.btn-kirim-data')
</form>

<form action="{{ route('ppdb.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <h4 class="d-flex justify-content-center mt-3">Form Data PPDB :</h4>

    @include('ppdb::bases.ppdb.register.input-email')
    @include('ppdb::bases.ppdb.register.input-nisn')
    @include('ppdb::bases.ppdb.register.input-nama-siswa')
    @include('ppdb::bases.ppdb.register.input-sekolah')
    @include('ppdb::bases.ppdb.register.input-alamat')
    @include('ppdb::bases.ppdb.register.input-telpon-siswa')
    @include('ppdb::bases.ppdb.register.input-jenis-kelamin')
    @include('ppdb::bases.ppdb.register.input-tempat-lahir')
    @include('ppdb::bases.ppdb.register.input-tanggal-lahir')
    @include('ppdb::bases.ppdb.register.input-jurusan')
    @include('ppdb::bases.ppdb.register.input-nama-ayah')
    @include('ppdb::bases.ppdb.register.input-nama-ibu')
    @include('ppdb::bases.ppdb.register.input-telpon-orang-tua')
    @include('ppdb::bases.ppdb.register.input-bukti-pembayaran')
    @include('ppdb::bases.ppdb.register.btn-kirim-data')

</form>

<form action="{{ route('siswa.update', [$getDataSiswa->uuid]) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    @include('siswa::bases.siswa.edit.input-nama-siswa')
    @include('siswa::bases.siswa.edit.input-email')
    @include('siswa::bases.siswa.edit.input-nisn')
    @include('siswa::bases.siswa.edit.input-sekolah')
    @include('siswa::bases.siswa.edit.input-alamat')
    @include('siswa::bases.siswa.edit.input-telpon-siswa')
    @include('siswa::bases.siswa.edit.input-jenis-kelamin')
    @include('siswa::bases.siswa.edit.input-tempat-lahir')
    @include('siswa::bases.siswa.edit.input-tanggal-lahir')
    @include('siswa::bases.siswa.edit.input-kelas')
    @include('siswa::bases.siswa.edit.input-tahun-daftar')
    @include('siswa::bases.siswa.edit.input-tahun-lulus')
    @include('siswa::bases.siswa.edit.input-jurusan')
    @include('siswa::bases.siswa.edit.input-nama-ayah')
    @include('siswa::bases.siswa.edit.input-nama-ibu')
    @include('siswa::bases.siswa.edit.input-telpon-orang-tua')
    @include('siswa::bases.siswa.edit.input-foto')
    @include('siswa::bases.siswa.edit.btn-kirim-data')
</form>

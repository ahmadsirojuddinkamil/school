<!DOCTYPE html>
<html>

<head>
    <title>Biodata siswa</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            /* Tambahkan garis bawah pada setiap baris */
        }

        th {
            width: 35%;
            /* Atur lebar kolom nama */
        }

    </style>
</head>

<body>
    <h5>Biodata Siswa Kelas : {{ $siswa->kelas }}</h5>
    <table>
        <tbody>
            <tr>
                <th>Nama</th>
                <td>{{ $siswa->name }}</td>
            </tr>

            <tr>
                <th>NISN</th>
                <td>{{ $siswa->nisn }}</td>
            </tr>

            <tr>
                <th>Kelas</th>
                <td>{{ $siswa->kelas }}</td>
            </tr>

            <tr>
                <th>Tempat Lahir</th>
                <td>{{ $siswa->tempat_lahir }}</td>
            </tr>

            <tr>
                <th>Tanggal Lahir</th>
                <td>{{ $siswa->tanggal_lahir }}</td>
            </tr>

            <tr>
                <th>Agama</th>
                <td>{{ $siswa->agama }}</td>
            </tr>

            <tr>
                <th>Jenis Kelamin</th>
                <td>{{ $siswa->jenis_kelamin }}</td>
            </tr>

            <tr>
                <th>Asal Sekolah</th>
                <td>{{ $siswa->asal_sekolah }}</td>
            </tr>

            <tr>
                <th>Nem</th>
                <td>{{ $siswa->nem }}</td>
            </tr>

            <tr>
                <th>Tahun Lulus</th>
                <td>{{ $siswa->tahun_lulus }}</td>
            </tr>

            <tr>
                <th>Alamat</th>
                <td>{{ $siswa->alamat_rumah }}</td>
            </tr>

            <tr>
                <th>Provinsi</th>
                <td>{{ $siswa->provinsi }}</td>
            </tr>

            <tr>
                <th>Kecamatan</th>
                <td>{{ $siswa->kecamatan }}</td>
            </tr>

            <tr>
                <th>Kelurahan</th>
                <td>{{ $siswa->kelurahan }}</td>
            </tr>

            <tr>
                <th>Kode Pos</th>
                <td>{{ $siswa->kode_pos }}</td>
            </tr>

            <tr>
                <th>Email</th>
                <td>{{ $siswa->email }}</td>
            </tr>

            <tr>
                <th>No Telpon</th>
                <td>{{ $siswa->no_telpon }}</td>
            </tr>

            <tr>
                <th>Tahun Daftar</th>
                <td>{{ $siswa->tahun_daftar }}</td>
            </tr>

            <tr>
                <th>Nama Bank</th>
                <td>{{ $siswa->nama_bank }}</td>
            </tr>

            <tr>
                <th>Nama Pemilik Buku Rekening</th>
                <td>{{ $siswa->nama_buku_rekening }}</td>
            </tr>

            <tr>
                <th>No Rekening</th>
                <td>{{ $siswa->no_rekening }}</td>
            </tr>

            <tr>
                <th>Nama Ayah</th>
                <td>{{ $siswa->nama_ayah }}</td>
            </tr>

            <tr>
                <th>Nama Ibu</th>
                <td>{{ $siswa->nama_ibu }}</td>
            </tr>

            <tr>
                <th>Nama Wali</th>
                <td>{{ $siswa->nama_wali }}</td>
            </tr>

            <tr>
                <th>Telpon Orang Tua</th>
                <td>{{ $siswa->telpon_orang_tua }}</td>
            </tr>
        </tbody>
    </table>
</body>

</html>

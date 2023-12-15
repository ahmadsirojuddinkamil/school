<!DOCTYPE html>
<html>

<head>
    <title>Biodata ppdb</title>
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
    <h5>Biodata Peserta Didik Baru Tahun : {{ $ppdb->tahun_daftar }}</h5>
    <table>
        <tbody>
            <tr>
                <th>Nama :</th>
                <td>{{ $ppdb->name }}</td>
            </tr>

            <tr>
                <th>Email :</th>
                <td>{{ $ppdb->email }}</td>
            </tr>

            <tr>
                <th>Nisn :</th>
                <td>{{ $ppdb->nisn }}</td>
            </tr>

            <tr>
                <th>Asal Sekolah :</th>
                <td>{{ $ppdb->asal_sekolah }}</td>
            </tr>

            <tr>
                <th>Alamat :</th>
                <td>{{ $ppdb->alamat }}</td>
            </tr>

            <tr>
                <th>Telpon Siswa :</th>
                <td>{{ $ppdb->telpon_siswa }}</td>
            </tr>

            <tr>
                <th>Jenis Kelamin :</th>
                <td>{{ $ppdb->jenis_kelamin }}</td>
            </tr>

            <tr>
                <th>Tempat Lahir :</th>
                <td>{{ $ppdb->tempat_lahir }}</td>
            </tr>

            <tr>
                <th>Tanggal Lahir :</th>
                <td>{{ $ppdb->tanggal_lahir }}</td>
            </tr>

            <tr>
                <th>Nama Ayah :</th>
                <td>{{ $ppdb->nama_ayah }}</td>
            </tr>

            <tr>
                <th>Nama Ibu :</th>
                <td>{{ $ppdb->nama_ibu }}</td>
            </tr>

            <tr>
                <th>Telpon Orang Tua :</th>
                <td>{{ $ppdb->telpon_orang_tua }}</td>
            </tr>

            <tr>
                <th>Tahun Daftar :</th>
                <td>{{ $ppdb->tahun_daftar }}</td>
            </tr>
        </tbody>
    </table>
</body>

</html>

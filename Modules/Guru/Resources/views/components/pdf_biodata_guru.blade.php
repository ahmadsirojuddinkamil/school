<!DOCTYPE html>
<html>

<head>
    <title>Biodata guru</title>
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
    <h5>Biodata {{ $biodata->name }} guru {{ $biodata->mata_pelajaran }}</h5>
    <table>
        <tbody>
            <tr>
                <th>Nama :</th>
                <td>{{ $biodata->name }}</td>
            </tr>
            <tr>
                <th>NUPTK :</th>
                <td>{{ $biodata->nuptk }}</td>
            </tr>
            <tr>
                <th>NIP :</th>
                <td>{{ $biodata->nip }}</td>
            </tr>
            <tr>
                <th>Tempat Lahir :</th>
                <td>{{ $biodata->tempat_lahir }}</td>
            </tr>
            <tr>
                <th>Tanggal Lahir :</th>
                <td>{{ $biodata->tanggal_lahir }}</td>
            </tr>
            {{-- <tr>
                <th>Mata Pelajaran :</th>
                <td>{{ $biodata->mata_pelajaran }}</td>
            </tr> --}}
            <tr>
                <th>Agama :</th>
                <td>{{ $biodata->agama }}</td>
            </tr>
            <tr>
                <th>Jenis Kelamin :</th>
                <td>{{ $biodata->jenis_kelamin }}</td>
            </tr>
            <tr>
                <th>Status Perkawinan :</th>
                <td>{{ $biodata->status_perkawinan }}</td>
            </tr>
            <tr>
                <th>Jam Mengajar Awal :</th>
                <td>{{ $biodata->jam_mengajar_awal }}</td>
            </tr>
            <tr>
                <th>Jam Mengajar Akhir :</th>
                <td>{{ $biodata->jam_mengajar_akhir }}</td>
            </tr>
            <tr>
                <th>Pendidikan Terakhir :</th>
                <td>{{ $biodata->pendidikan_terakhir }}</td>
            </tr>
            <tr>
                <th>Nama Tempat Pendidikan :</th>
                <td>{{ $biodata->nama_tempat_pendidikan }}</td>
            </tr>
            <tr>
                <th>IPK :</th>
                <td>{{ $biodata->ipk }}</td>
            </tr>
            <tr>
                <th>Tahun Lulus :</th>
                <td>{{ $biodata->tahun_lulus }}</td>
            </tr>
            <tr>
                <th>Alamat Rumah :</th>
                <td>{{ $biodata->alamat_rumah }}</td>
            </tr>
            <tr>
                <th>Provinsi :</th>
                <td>{{ $biodata->provinsi }}</td>
            </tr>
            <tr>
                <th>Kecamatan :</th>
                <td>{{ $biodata->kecamatan }}</td>
            </tr>
            <tr>
                <th>Kelurahan :</th>
                <td>{{ $biodata->kelurahan }}</td>
            </tr>
            <tr>
                <th>Kode Pos :</th>
                <td>{{ $biodata->kode_pos }}</td>
            </tr>
            <tr>
                <th>Email :</th>
                <td>{{ $biodata->email }}</td>
            </tr>
            <tr>
                <th>No Telepon :</th>
                <td>{{ $biodata->no_telpon }}</td>
            </tr>
            <tr>
                <th>Tahun Daftar :</th>
                <td>{{ $biodata->tahun_daftar }}</td>
            </tr>
            <tr>
                <th>Tahun Keluar :</th>
                <td>{{ $biodata->tahun_keluar }}</td>
            </tr>
            <tr>
                <th>Nama Bank :</th>
                <td>{{ $biodata->nama_bank }}</td>
            </tr>
            <tr>
                <th>Nama Buku Rekening :</th>
                <td>{{ $biodata->nama_buku_rekening }}</td>
            </tr>
            <tr>
                <th>No Rekening :</th>
                <td>{{ $biodata->no_rekening }}</td>
            </tr>

        </tbody>
    </table>
</body>

</html>

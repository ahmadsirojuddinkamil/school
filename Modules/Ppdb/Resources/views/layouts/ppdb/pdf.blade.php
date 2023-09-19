<!DOCTYPE html>
<html>

<head>
    <title>Data pdf ppdb</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid black;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
    </style>
</head>

<body>
    <h1>PPDB tahun {{ $getDataPpdb[0]->tahun_daftar }} : {{ $totalDataPpdb }} peserta</h1>
    <table>
        <thead>
            <tr>
                <th scope="col">#</th>
                <th style="text-align: center;">Nama Lengkap</th>
                <th style="text-align: center;">NISN</th>
                <th style="text-align: center;">Asal Sekolah</th>
                <th style="text-align: center;">Telpon</th>
                <th style="text-align: center;">Jenis Kelamin</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($getDataPpdb as $dataPpdb)
                <tr>
                    <td style="text-align: center;">{{ $loop->iteration }}</td>

                    <td>{{ $dataPpdb->nama_lengkap }}</td>
                    <td>{{ $dataPpdb->nisn }}</td>
                    <td>{{ $dataPpdb->asal_sekolah }}</td>
                    <td>{{ $dataPpdb->telpon_siswa }}</td>
                    <td>{{ $dataPpdb->jenis_kelamin }}</td>

                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>

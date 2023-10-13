<!DOCTYPE html>
<html>

<head>
    <title>Data pdf siswa aktif</title>
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
    <h1>Siswa lulus tahun {{ $dataSiswaActive[0]->kelas }} : {{ $totalSiswaActive }} peserta</h1>
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
            @foreach ($dataSiswaActive as $siswa)
                <tr>
                    <td style="text-align: center;">{{ $loop->iteration }}</td>

                    <td>{{ $siswa->nama_lengkap }}</td>
                    <td>{{ $siswa->nisn }}</td>
                    <td>{{ $siswa->asal_sekolah }}</td>
                    <td>{{ $siswa->telpon_siswa }}</td>
                    <td>{{ $siswa->jenis_kelamin }}</td>

                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>

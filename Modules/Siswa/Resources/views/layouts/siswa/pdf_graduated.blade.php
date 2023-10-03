<!DOCTYPE html>
<html>

<head>
    <title>Data pdf siswa lulus</title>
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
    <h1>Siswa lulus tahun {{ $dataSiswaGraduated[0]->tahun_lulus }} : {{ $totalSiswaGraduated }} peserta</h1>
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
            @foreach ($dataSiswaGraduated as $siswaGraduated)
                <tr>
                    <td style="text-align: center;">{{ $loop->iteration }}</td>

                    <td>{{ $siswaGraduated->nama_lengkap }}</td>
                    <td>{{ $siswaGraduated->nisn }}</td>
                    <td>{{ $siswaGraduated->asal_sekolah }}</td>
                    <td>{{ $siswaGraduated->telpon_siswa }}</td>
                    <td>{{ $siswaGraduated->jenis_kelamin }}</td>

                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>

<!DOCTYPE html>
<html>

<head>
    <title>Data absen {{ $name }}</title>
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
    <h3>Absensi {{ $name }} total : {{ $totalAbsen }} hari *tidak hadir tidak dihitung!</h3>

    <table style="margin-bottom: 20px">
        <thead>
            <tr>
                <th style="text-align: center;">Hadir</th>
                <th style="text-align: center;">Sakit</th>
                <th style="text-align: center;">Acara</th>
                <th style="text-align: center;">Musibah</th>
                <th style="text-align: center;">Tidak Hadir</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td style="text-align: center;">{{ $totalHadir }}</td>
                <td style="text-align: center;">{{ $totalSakit }}</td>
                <td style="text-align: center;">{{ $totalAcara }}</td>
                <td style="text-align: center;">{{ $totalMusibah }}</td>
                <td style="text-align: center;">{{ $totalTidakHadir }}</td>
            </tr>
        </tbody>
    </table>

    <table>
        <thead>
            <tr>
                <th style="text-align: center;" scope="col">#</th>
                <th style="text-align: center;">Keterangan</th>
                <th style="text-align: center;">Tanggal</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($listAbsen as $absen)
            <tr>
                <td style="text-align: center;">{{ $loop->iteration }}</td>
                <td style="text-align: center;">{{ $absen->keterangan }}</td>
                <td style="text-align: center;">{{ $absen->created_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>

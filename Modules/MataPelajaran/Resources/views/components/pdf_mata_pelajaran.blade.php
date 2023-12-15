<!DOCTYPE html>
<html>

<head>
    <title>Data Mata Pelajaran </title>
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
    <h5>Data Mata Pelajaran</h5>
    <table>
        <tbody>
            <tr>
                <th>Nama :</th>
                <td>{{ $dataMapel->name }}</td>
            </tr>

            <tr>
                <th>Jam Awal :</th>
                <td>{{ $dataMapel->jam_awal }}</td>
            </tr>

            <tr>
                <th>Jam Akhir :</th>
                <td>{{ $dataMapel->jam_akhir }}</td>
            </tr>

            <tr>
                <th>Kelas :</th>
                <td>{{ $dataMapel->kelas }}</td>
            </tr>

            <tr>
                <th>Pengajar :</th>
                <td>{{ $dataMapel->name_guru }}</td>
            </tr>
        </tbody>
    </table>
</body>

</html>

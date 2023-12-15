<!DOCTYPE html>
<html>

<head>
    <title>Data Absen</title>
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
            width: 25%;
            /* Atur lebar kolom nama */
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        td {
            padding: 8px;
            border: 1px solid #000;
            /* Memberikan border hitam */
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        td {
            padding: 8px;
            border: 1px solid #000;
            /* Memberikan border hitam */
        }

    </style>
</head>

<body>
    <h5>Data Absen : {{ $name }}</h5>
    <table>
        <tbody>
            @foreach ($dataAbsen as $absen)
            <tr>
                <td style="text-align: center">{{ $absen->updated_at }}</td>
                <td style="text-align: center">{{ $absen->keterangan }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>



</body>

</html>

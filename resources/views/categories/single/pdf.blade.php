 <!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Kategori {{ $data->kode }}</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #333;
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
        }

        .category-info {
            margin-bottom: 20px;
        }

        .category-info td {
            padding: 5px;
        }

        table.items {
            width: 100%;
            border-collapse: collapse;
        }

        table.items th,
        table.items td {
            border: 1px solid #ccc;
            padding: 8px;
        }

        table.items th {
            background-color: #eee;
            text-align: left;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .footer {
            position: fixed;
            bottom: -25px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9px;
            color: #777;
            border-top: 1px solid #ccc;
            padding-top: 8px;
        }

        @page {
            margin: 40px 35px 60px 35px;
        }
    </style>
</head>
<body>

    <h2>DATA KATEGORI MASTER ITEM</h2>

    <table class="category-info">
        <tr>
            <td width="100"><strong>Kode Kategori</strong></td>
            <td width="10">:</td>
            <td>{{ $data->kode }}</td>
        </tr>
        <tr>
            <td><strong>Nama Kategori</strong></td>
            <td>:</td>
            <td>{{ $data->name }}</td>
        </tr>
    </table>

    <h3>Daftar Master Item</h3>

    <table class="items">
        <thead>
            <tr>
                <th width="35">No</th>
                <th>Kode</th>
                <th>Nama Item</th>
                <th>Harga Beli</th>
                <th>Supplier</th>
                <th>Jenis</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($data->masterItems as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->kode }}</td>
                    <td>{{ $item->nama }}</td>
                    <td class="text-right">
                        {{ number_format($item->harga_beli, 0, ',', '.') }}
                    </td>
                    <td>{{ $item->supplier }}</td>
                    <td>{{ $item->jenis }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">
                        Belum ada Master Item pada kategori ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada:
        {{ $printed_at->format('d-m-Y H:i:s') }}
    </div>

</body>
</html>
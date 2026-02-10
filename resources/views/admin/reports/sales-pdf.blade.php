<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan</title>

    <style>
        @page {
            margin: 20mm 15mm 20mm 15mm;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #111;
        }

        h2 {
            margin: 0;
            font-size: 16px;
        }

        .muted {
            color: #666;
            font-size: 10px;
        }

        .header {
            margin-bottom: 12px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 8px;
        }

        .summary {
            margin: 10px 0 14px;
        }

        .summary table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }

        .summary td {
            padding: 4px 6px;
        }

        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }

        table.data th,
        table.data td {
            border: 1px solid #ccc;
            padding: 6px;
            font-size: 10.5px;
        }

        table.data th {
            background: #f2f2f2;
            text-align: left;
        }

        .right {
            text-align: right;
        }

        .center {
            text-align: center;
        }

        footer {
            position: fixed;
            bottom: -10mm;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #666;
        }

        .page-number::after {
            content: counter(page);
        }
    </style>
</head>

<body>

    {{-- HEADER --}}
    <div class="header">
        <h2>Laporan Penjualan</h2>
        <div class="muted">
            Dicetak: {{ now()->format('d M Y H:i') }}
        </div>

        @if (!empty($filter))
            <div class="muted" style="margin-top:4px;">
                Filter:
                @php
                    $periodLabel = [
                        'daily' => 'Semua',
                        'monthly' => 'Bulanan',
                        'yearly' => 'Tahunan',
                        'custom' => 'Custom',
                    ];
                @endphp

                {{ $periodLabel[$filter['period'] ?? 'daily'] ?? 'Semua' }}
                
                @if (!empty($filter['start_date']) && !empty($filter['end_date']))
                    | {{ $filter['start_date'] }} – {{ $filter['end_date'] }}
                @endif
                @if (!empty($filter['product']))
                    | Produk: {{ $filter['product'] }}
                @endif
                @if (!empty($filter['status']))
                    | Status: {{ strtoupper($filter['status']) }}
                @endif
            </div>
        @endif
    </div>

    {{-- SUMMARY --}}
    {{-- <div class="summary">
        <table>
            <tr>
                <td><strong>Total Pendapatan</strong></td>
                <td class="right">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>

                <td><strong>Jumlah Transaksi</strong></td>
                <td class="right">{{ $totalTransaction }}</td>

                <td><strong>Rata-rata</strong></td>
                <td class="right">
                    Rp {{ number_format($avgTransaction, 0, ',', '.') }}
                </td>
            </tr>
        </table>
    </div> --}}

    {{-- TABLE --}}
    <table class="data">
        <thead>
            <tr>
                <th style="width:16%;">Kode Transaksi</th>
                <th style="width:22%;">Nama Pembeli</th>
                <th style="width:16%;">Tanggal</th>
                <th style="width:10%;">Status</th>
                <th style="width:24%;">Produk Dibeli</th>
                <th style="width:12%;" class="right">Total</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($transactions as $trx)
                <tr>
                    <td>{{ $trx->code }}</td>
                    <td>{{ $trx->receiver_name }}</td>
                    <td>{{ $trx->created_at->format('d M Y') }}</td>
                    <td>
                        <span class="badge {{ $trx->status }}">
                            {{ strtoupper($trx->status) }}
                        </span>
                    </td>

                    {{-- PRODUK --}}
                    <td>
                        @foreach ($trx->items as $item)
                            {{ $item->product_name }} (x{{ $item->quantity }})<br>
                        @endforeach
                    </td>

                    <td class="right">
                        Rp {{ number_format($trx->total_amount, 0, ',', '.') }}
                    </td>
                </tr>
            @endforeach
        </tbody>

        {{-- TOTAL PENDAPATAN --}}
        <tfoot>
            <tr>
                <td colspan="5" style="text-align:right; font-weight:700; background:#f8fafc;">
                    Total Pendapatan
                </td>
                <td class="right" style="font-weight:700; background:#f8fafc;">
                    Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                </td>
            </tr>
        </tfoot>
    </table>



    {{-- FOOTER --}}
    <footer>
        Laporan Penjualan • Website Daeng Rubik • Halaman
        <span class="page-number"></span>
    </footer>


</body>

</html>

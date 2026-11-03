<?php

namespace App\Exports;

use App\Models\MasterItem;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MasterItemsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return MasterItem::with('categories')->get();
    }

    public function headings(): array
    {
        return [
            'Nomor',
            'Nama Kategori',
            'Nama Item',
            'Nama Supplier',
            'Harga',
            'Laba',
            'Harga Jual',
        ];
    }

    public function map($item): array
    {
        static $number = 0;
        $number++;

        $harga_jual = $item->harga_beli + ($item->harga_beli * $item->laba / 100);

        return [
            $number,
            $item->categories->pluck('name')->implode(', '),
            $item->nama,
            $item->supplier,
            $item->harga_beli,
            $item->laba.'%',
            $harga_jual,
        ];
    }
}

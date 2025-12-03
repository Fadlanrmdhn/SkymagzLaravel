<?php

namespace App\Exports;

use App\Models\Magazine;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MagazineExport implements FromCollection, WithHeadings, WithMapping
{
    private $key = 0;

    public function collection()
    {
        // ambil hanya yang type = majalah
        return Magazine::where('type', 'majalah')->get();
    }

    public function headings(): array
    {
        return ['No', 'Cover', 'Judul', 'Tipe', 'Penulis', 'Penerbit', 'Sinopsis', 'Harga', 'Tanggal Release'];
    }

    public function map($magazine): array
    {
        return [
            ++$this->key,
            asset('storage') . '/' . $magazine->cover,
            $magazine->title,
            $magazine->type,
            $magazine->author,
            $magazine->publisher,
            $magazine->description,
            $magazine->price,
            $magazine->release_date,
        ];
    }
}

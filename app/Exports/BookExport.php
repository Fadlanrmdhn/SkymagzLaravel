<?php

namespace App\Exports;

use App\Models\Magazine;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BookExport implements FromCollection, WithHeadings, WithMapping
{
    private $key = 0;

    public function collection()
    {
        // ambil hanya yang type = buku
        return Magazine::where('type', 'buku')->get();
    }

    public function headings(): array
    {
        return ['No', 'Cover', 'Judul', 'Tipe', 'Penulis', 'Penerbit', 'Sinopsis', 'Harga', 'Tanggal Release'];
    }

    public function map($book): array
    {
        return [
            ++$this->key,
            asset('storage') . '/' . $book->cover,
            $book->title,
            $book->type,
            $book->author,
            $book->publisher,
            $book->description,
            $book->price,
            $book->release_date,
        ];
    }
}

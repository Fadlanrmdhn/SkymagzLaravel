<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OrderExport implements FromCollection, WithHeadings, WithMapping
{
    private $key = 0;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Order::with('user', 'promo', 'orderDetails')->orderByDesc('created_at')->get();
    }

    public function headings(): array{
        return ['No', 'Order ID', 'User', 'Subtotal', 'Promo', 'Status', 'Tanggal'];
    }

    public function map($order): array{
        $subtotal = 0;
        foreach($order->orderDetails as $d) {
            $subtotal += $d->total_price;
        }

        return [
            ++$this->key,
            str_pad($order->id, 6, '0', STR_PAD_LEFT),
            $order->user->name ?? '—',
            'Rp ' . number_format($subtotal, 0, ',', '.'),
            $order->promo->promo_code ?? '-',
            ucfirst($order->status),
            $order->created_at->format('d M Y H:i')
        ];
    }
}

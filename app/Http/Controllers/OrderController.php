<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OrderExport;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    /**
     * Display a listing of orders.
     */
    public function index()
    {
        $orders = Order::with('user', 'promo')->orderByDesc('created_at')->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Display the specified order and its details.
     */
    public function show($id)
    {
        $order = Order::with('orderDetails.magazine', 'user', 'promo')->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update order (status or other editable fields).
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,completed,canceled',
        ]);

        $order = Order::findOrFail($id);
        $order->status = $request->input('status');
        $order->save();

        return redirect()->route('admin.orders.show', $order->id)->with('success', 'Status pesanan diperbarui.');
    }

    /**
     * Soft-delete the order.
     */
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return redirect()->route('admin.orders.index')->with('success', 'Pesanan dihapus.');
    }

    /**
     * Display trashed orders.
     */
    public function trash()
    {
        $orders = Order::onlyTrashed()->with('user', 'promo')->orderByDesc('deleted_at')->paginate(20);

        return view('admin.orders.trash', compact('orders'));
    }

    /**
     * Restore a trashed order.
     */
    public function restore($id)
    {
        $order = Order::withTrashed()->findOrFail($id);
        $order->restore();

        return redirect()->route('admin.orders.trash')->with('success', 'Pesanan dipulihkan.');
    }

    /**
     * Permanently delete a trashed order.
     */
    public function deletePermanent($id)
    {
        $order = Order::withTrashed()->findOrFail($id);
        $order->forceDelete();

        return redirect()->route('admin.orders.trash')->with('success', 'Pesanan dihapus permanen.');
    }

    /**
     * Export orders to Excel.
     */
    public function export()
    {
        $fileName = 'data-Pesanan.xlsx';
        return Excel::download(new OrderExport, $fileName);
    }

    public function datatables()
    {
        $orders = Order::with('user', 'promo', 'orderDetails')->get();
        return DataTables::of($orders)
            ->addIndexColumn()
            ->addColumn('user', function($order){
                return $order->user->name ?? '—';
            })
            ->addColumn('total_amount', function($order){
                $subtotal = 0;
                foreach ($order->orderDetails as $d) {
                    $subtotal += $d->total_price;
                }
                return 'Rp ' . number_format($subtotal, 0, ',', '.');
            })
            ->addColumn('promo_id', function($order){
                return $order->promo->promo_code ?? '-';
            })
            ->addColumn('status', function ($order) {
                if ($order->status == 'pending') {
                    return '<span class="badge bg-warning">pending</span>';
                } elseif ($order->status == 'completed'){
                    return '<span class="badge bg-success">completed</span>';
                } else {
                    return '<span class="badge bg-danger">canceled</span>';
                }
            })
            ->addColumn('order_date', function($order){
                return $order->created_at->format('d M Y H:i');
            })
            ->addColumn('action', function ($order) {
                $btnView =  '<a href="' . route('admin.orders.show', $order->id) . '" class="btn btn-primary">View</a>';
                return '<div class="d-flex justify-content-center align-items-center gap-2">' . $btnView . '</div>';
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }
}

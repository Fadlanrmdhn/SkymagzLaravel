<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OrderExport;

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
}

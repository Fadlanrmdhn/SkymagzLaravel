<?php

namespace App\Http\Controllers;

use App\Models\Magazine;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Promo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class CartController extends Controller
{
    /**
     * Add a magazine/book to the cart (stored in session)
     */
    public function addToCart(Request $request)
    {
        $request->validate([
            'magazine_id' => 'required|exists:magazines,id',
            // quantity is not required for this product type (fixed to 1)
            'quantity' => 'nullable|integer|min:1',
            'action' => 'required|in:cart,buy',
        ]);

        $magazineId = $request->input('magazine_id');
        // This store uses a fixed quantity of 1 from product page (no quantity selector)
        $quantity = 1;
        $action = $request->input('action');

        // Get magazine details
        $magazine = Magazine::find($magazineId);
        if (!$magazine) {
            return redirect()->back()->with('error', 'Buku tidak ditemukan!');
        }

        // Get current cart from session
        $cart = session()->get('cart', []);

        // If magazine already in cart, keep quantity as 1 (no multiple quantities from product page)
        if (isset($cart[$magazineId])) {
            $cart[$magazineId]['quantity'] = 1;
        } else {
            $cart[$magazineId] = [
                'magazine_id' => $magazineId,
                'title' => $magazine->title,
                'price' => $magazine->price,
                'cover' => $magazine->cover,
                'quantity' => $quantity,
            ];
        }

        // Store updated cart in session
        session(['cart' => $cart]);

        // Handle action: either show cart or proceed to checkout
        if ($action === 'buy') {
            return redirect()->route('checkout')->with('success', 'Lanjut ke pembayaran!');
        }

        // Default: redirect to cart page
        return redirect()->route('cart')->with('success', 'Buku berhasil ditambahkan ke keranjang!');
    }

    /**
     * Show cart contents
     */
    public function showCart()
    {
        $cart = session()->get('cart', []);
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        // Pass active promos to cart view so user can apply promo directly from cart
        $promos = Promo::where('activated', true)->get();

        return view('cart', compact('cart', 'total', 'promos'));
    }

    /**
     * Remove item from cart
     */
    public function removeFromCart($magazineId)
    {
        $cart = session()->get('cart', []);
        unset($cart[$magazineId]);
        session(['cart' => $cart]);

        return redirect()->route('cart')->with('success', 'Buku dihapus dari keranjang!');
    }

    /**
     * Clear entire cart
     */
    public function clearCart()
    {
        session()->forget('cart');
        return redirect()->route('cart')->with('success', 'Keranjang dikosongkan!');
    }

    /**
     * Update quantity in cart
     */
    public function updateQuantity(Request $request, $magazineId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = session()->get('cart', []);

        if (isset($cart[$magazineId])) {
            $cart[$magazineId]['quantity'] = $request->input('quantity');
            session(['cart' => $cart]);
        }

        return redirect()->route('cart')->with('success', 'Jumlah diperbarui!');
    }

    /**
     * Show checkout page with cart summary
     */
    public function showCheckout(Request $request)
    {
        // Check if user is logged in
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk checkout!');
        }

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Keranjang kosong! Tambahkan buku terlebih dahulu.');
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        $user = Auth::user();
        $promos = Promo::where('activated', true)->get();

        // If coming from cart with promo query params, prefill selection
        $selectedPromoId = $request->query('promo_id');
        $initialPromoDiscount = 0;
        if ($selectedPromoId) {
            $promo = Promo::find($selectedPromoId);
            if ($promo) {
                if (str_contains(strtolower($promo->type), 'perc')) {
                    $initialPromoDiscount = (int) floor(($total * $promo->discount) / 100);
                } else {
                    $initialPromoDiscount = min((int) $promo->discount, $total);
                }
            }
        }

        return view('checkout', compact('cart', 'total', 'user', 'promos', 'selectedPromoId', 'initialPromoDiscount'));
    }

    /**
     * Process checkout: create order and order details from cart
     */
    public function processCheckout(Request $request)
    {
        // Check if user is logged in
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu!');
        }

        $request->validate([
            'payment_method' => 'required|in:transfer,card,gopay,ovo',
            'promo_id' => 'nullable|exists:promos,id',
            'promo_discount' => 'nullable|numeric|min:0',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Keranjang kosong!');
        }

        // Calculate total
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        // Server-side: compute promo discount from promo_id (ignore client-sent promo_discount to prevent tampering)
        $promoId = $request->input('promo_id');
        $promoAmount = 0;
        if ($promoId) {
            $promo = Promo::where('id', $promoId)->where('activated', true)->first();
            if ($promo) {
                $pDiscount = (int) $promo->discount;
                $ptype = strtolower((string) $promo->type);
                if (stripos($ptype, 'perc') !== false) {
                    $promoAmount = (int) floor(($total * $pDiscount) / 100);
                } else {
                    $promoAmount = min($pDiscount, $total);
                }
            } else {
                // promo not found or not active -> ignore
                $promoId = null;
            }
        }

        $finalTotal = max($total - $promoAmount, 0);

        // Create order (save promo_id if valid)
        $orderData = [
            'user_id' => Auth::id(),
            'order_date' => now(),
            'total_amount' => $finalTotal,
            'status' => 'pending', // pending, paid, shipped, completed, cancelled
            'promo_amount' => $promoAmount,
        ];

        if ($promoId) {
            $orderData['promo_id'] = $promoId;
        }

        $order = Order::create($orderData);

        // Create order details from cart items
        foreach ($cart as $magazine_id => $item) {
            OrderDetail::create([
                'order_id' => $order->id,
                'magazine_id' => $magazine_id,
                'quantity' => $item['quantity'],
                'total_price' => $item['price'] * $item['quantity'],
            ]);
        }

        // Clear cart from session
        session()->forget('cart');

        return redirect()->route('order.confirmation', ['order_id' => $order->id])
            ->with('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran.');
    }

    /**
     * Show order confirmation page
     */
    public function orderConfirmation($order_id)
    {
        $order = Order::with('orderDetails')->findOrFail($order_id);

        // Check if user owns this order
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        return view('order-confirmation', compact('order'));
    }

    /**
     * Download order confirmation as PDF
     */
    public function downloadOrderPdf($order_id)
    {
        $order = Order::with('orderDetails')->findOrFail($order_id);

        // Check if user owns this order
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Generate PDF from view
        $pdf = Pdf::loadView('pdf.order-invoice', compact('order'))
            ->setPaper('a4')
            ->setOption('margin-top', 10)
            ->setOption('margin-bottom', 10)
            ->setOption('margin-left', 10)
            ->setOption('margin-right', 10);

        return $pdf->download('Invoice-' . str_pad($order->id, 6, '0', STR_PAD_LEFT) . '.pdf');
    }
}

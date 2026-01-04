<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class POSController extends Controller
{
    /**
     * Display POS interface
     */
    public function index()
    {
        $products = Product::active()->get();
        
        return view('pos.index', compact('products'));
    }

    /**
     * Process transaction
     */
    public function processTransaction(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|in:cash,qris,transfer',
            'total_amount' => 'required|numeric|min:0'
        ]);

        DB::beginTransaction();
        
        try {
            // Create transaction
            $transaction = Transaction::create([
                'transaction_code' => Transaction::generateCode(),
                'user_id' => auth()->id(),
                'total_amount' => $request->total_amount,
                'payment_method' => $request->payment_method,
                'status' => 'completed'
            ]);

            $totalCalculated = 0;

            // Create transaction details and update stock
            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);
                
                // Check stock availability
                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stok {$product->name} tidak mencukupi");
                }

                $subtotal = $product->price * $item['quantity'];
                $totalCalculated += $subtotal;

                // Create transaction detail
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'subtotal' => $subtotal
                ]);

                // Update product stock
                $product->updateStock($item['quantity'], 'out', 'Penjualan', auth()->id());
            }

            // Verify total amount
            if (abs($totalCalculated - $request->total_amount) > 0.01) {
                throw new \Exception("Total amount tidak sesuai");
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil',
                'transaction_id' => $transaction->id,
                'transaction_code' => $transaction->transaction_code
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Print receipt
     */
    public function printReceipt($transactionId)
    {
        $transaction = Transaction::with(['details.product', 'user'])
            ->findOrFail($transactionId);

        $pdf = Pdf::loadView('pos.receipt', compact('transaction'));
        
        return $pdf->stream('receipt-' . $transaction->transaction_code . '.pdf');
    }

    /**
     * Get product by barcode or search
     */
    public function searchProduct(Request $request)
    {
        $query = $request->get('q');
        
        $products = Product::active()
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('id', $query);
            })
            ->take(10)
            ->get();

        return response()->json($products);
    }

    /**
     * Transaction history
     */
    public function history(Request $request)
    {
        $query = Transaction::with(['user', 'details.product']);
        
        // Filter by date
        if ($request->has('date') && $request->date) {
            $query->whereDate('created_at', $request->date);
        }
        
        // Filter by payment method
        if ($request->has('payment_method') && $request->payment_method) {
            $query->where('payment_method', $request->payment_method);
        }
        
        $transactions = $query->latest()->paginate(15);
        
        return view('pos.history', compact('transactions'));
    }
}
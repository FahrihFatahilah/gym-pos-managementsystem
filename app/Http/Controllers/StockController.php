<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockHistory;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index()
    {
        $products = Product::with('stockHistories')->latest()->paginate(10);
        return view('stocks.index', compact('products'));
    }

    public function history(Request $request)
    {
        $query = StockHistory::with(['product', 'user']);
        
        // Search by product name or SKU
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('product', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }
        
        // Filter by type
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }
        
        $histories = $query->latest()->paginate(15);
        return view('stocks.history', compact('histories'));
    }

    public function updateStock(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:in,out',
            'quantity' => 'required|integer|min:1',
            'reason' => 'required|string|max:255'
        ]);

        $product = Product::findOrFail($request->product_id);
        $product->updateStock($request->quantity, $request->type, $request->reason, auth()->id());

        return redirect()->route('stocks.index')->with('success', 'Stok berhasil diupdate.');
    }
}
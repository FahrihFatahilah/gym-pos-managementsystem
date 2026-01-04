<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Product;
use App\Models\Transaction;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display dashboard with statistics
     */
    public function index()
    {
        // Total member aktif
        $totalActiveMembers = Member::where('status', 'active')->count();
        
        // Total penjualan hari ini
        $todaySales = Transaction::whereDate('created_at', Carbon::today())
            ->where('status', 'completed')
            ->sum('total_amount');
        
        // Produk dengan stok minimum (< 10)
        $lowStockProducts = Product::where('stock', '<', 10)
            ->where('is_active', true)
            ->get();
        
        // Member yang akan expired dalam 7 hari
        $expiringMembers = Member::whereHas('memberships', function($query) {
            $query->where('status', 'active')
                  ->whereBetween('end_date', [Carbon::today(), Carbon::today()->addDays(7)]);
        })->count();
        
        // Detail member yang akan expired
        $expiringMembersList = Member::whereHas('memberships', function($query) {
            $query->where('status', 'active')
                  ->whereBetween('end_date', [Carbon::today(), Carbon::today()->addDays(7)]);
        })->with(['memberships' => function($query) {
            $query->where('status', 'active')
                  ->whereBetween('end_date', [Carbon::today(), Carbon::today()->addDays(7)]);
        }])->take(10)->get();
        
        // Transaksi hari ini
        $todayTransactions = Transaction::whereDate('created_at', Carbon::today())
            ->with(['user', 'details.product'])
            ->latest()
            ->take(5)
            ->get();
        
        // Chart data - penjualan 7 hari terakhir
        $salesChart = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $sales = Transaction::whereDate('created_at', $date)
                ->where('status', 'completed')
                ->sum('total_amount');
            
            $salesChart[] = [
                'date' => $date->format('d/m'),
                'sales' => $sales
            ];
        }
        
        return view('dashboard', compact(
            'totalActiveMembers',
            'todaySales',
            'lowStockProducts',
            'expiringMembers',
            'expiringMembersList',
            'todayTransactions',
            'salesChart'
        ));
    }
}
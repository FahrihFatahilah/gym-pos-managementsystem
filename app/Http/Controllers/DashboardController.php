<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\DailyUser;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display dashboard with statistics
     */
    public function index()
    {
        // Check if user is staff - limit to today's data only
        $isStaff = auth()->user()->role === 'staff';
        $dateFilter = $isStaff ? Carbon::today() : null;
        
        // Total member aktif
        $totalActiveMembers = Member::where('status', 'active')->count();
        
        // Total daily users hari ini
        $todayDailyUsers = DailyUser::whereDate('visit_date', Carbon::today())->count();
        
        // Total penjualan (filtered by date for staff)
        $salesQuery = Transaction::where('status', 'completed');
        if ($isStaff) {
            $salesQuery->whereDate('created_at', Carbon::today());
        }
        $todaySales = $salesQuery->sum('total_amount');
        
        // Total pendapatan daily users
        $dailyRevenueQuery = DailyUser::query();
        if ($isStaff) {
            $dailyRevenueQuery->whereDate('visit_date', Carbon::today());
        }
        $todayDailyRevenue = $dailyRevenueQuery->sum('amount_paid');
        
        // Payment method breakdown (filtered for staff)
        $paymentBreakdown = [];
        $paymentMethods = ['cash', 'qris', 'transfer'];
        
        foreach ($paymentMethods as $method) {
            // POS Sales
            $posQuery = Transaction::where('status', 'completed')->where('payment_method', $method);
            if ($isStaff) {
                $posQuery->whereDate('created_at', Carbon::today());
            }
            $posAmount = $posQuery->sum('total_amount');
            
            // Membership Payments
            $membershipQuery = \App\Models\Payment::where('status', 'completed')->where('payment_method', $method);
            if ($isStaff) {
                $membershipQuery->whereDate('payment_date', Carbon::today());
            }
            $membershipAmount = $membershipQuery->sum('amount');
            
            // Daily Users
            $dailyQuery = DailyUser::where('payment_method', $method);
            if ($isStaff) {
                $dailyQuery->whereDate('visit_date', Carbon::today());
            }
            $dailyAmount = $dailyQuery->sum('amount_paid');
            
            // PT Members
            $ptQuery = \App\Models\PTMember::where('payment_method', $method);
            if ($isStaff) {
                $ptQuery->whereDate('created_at', Carbon::today());
            }
            $ptAmount = $ptQuery->sum('amount_paid');
            
            $paymentBreakdown[$method] = [
                'pos' => $posAmount,
                'membership' => $membershipAmount,
                'daily' => $dailyAmount,
                'pt' => $ptAmount,
                'total' => $posAmount + $membershipAmount + $dailyAmount + $ptAmount
            ];
        }
        
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
        
        // Transaksi (filtered for staff)
        $transactionQuery = Transaction::with(['user', 'details.product'])->latest();
        if ($isStaff) {
            $transactionQuery->whereDate('created_at', Carbon::today());
        }
        $todayTransactions = $transactionQuery->take(5)->get();
        
        // Chart data - penjualan 7 hari terakhir (only today for staff)
        $salesChart = [];
        $chartDays = $isStaff ? 1 : 7;
        
        for ($i = $chartDays - 1; $i >= 0; $i--) {
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
            'todayDailyUsers',
            'todaySales',
            'todayDailyRevenue',
            'paymentBreakdown',
            'lowStockProducts',
            'expiringMembers',
            'expiringMembersList',
            'todayTransactions',
            'salesChart',
            'isStaff'
        ));
    }
}
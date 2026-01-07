<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Member;
use App\Models\Product;
use App\Models\StockHistory;
use App\Exports\SalesExport;
use App\Exports\MembersExport;
use App\Exports\StocksExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function sales(Request $request)
    {
        $startDate = $request->get('start_date') ? Carbon::parse($request->get('start_date')) : Carbon::now()->startOfMonth();
        $endDate = $request->get('end_date') ? Carbon::parse($request->get('end_date')) : Carbon::now()->endOfMonth();
        $staffId = $request->get('staff_id');
        $paymentMethod = $request->get('payment_method');
        
        // POS Transactions
        $transactions = Transaction::with(['user', 'details.product'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->when($staffId, function($query, $staffId) {
                return $query->where('user_id', $staffId);
            })
            ->when($paymentMethod, function($query, $paymentMethod) {
                return $query->where('payment_method', $paymentMethod);
            })
            ->latest()
            ->paginate(15);
            
        $totalPosSales = Transaction::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->when($staffId, function($query, $staffId) {
                return $query->where('user_id', $staffId);
            })
            ->when($paymentMethod, function($query, $paymentMethod) {
                return $query->where('payment_method', $paymentMethod);
            })
            ->sum('total_amount');
            
        // Membership Payments
        $membershipPayments = \App\Models\Payment::with(['member', 'membership'])
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->where('status', 'completed')
            ->when($paymentMethod, function($query, $paymentMethod) {
                return $query->where('payment_method', $paymentMethod);
            })
            ->latest('payment_date')
            ->paginate(15, ['*'], 'membership_page');
            
        $totalMembershipSales = \App\Models\Payment::whereBetween('payment_date', [$startDate, $endDate])
            ->where('status', 'completed')
            ->when($paymentMethod, function($query, $paymentMethod) {
                return $query->where('payment_method', $paymentMethod);
            })
            ->sum('amount');
            
        // Pengunjung Harians
        $dailyUsers = \App\Models\DailyUser::with('personalTrainer')
            ->whereBetween('visit_date', [$startDate, $endDate])
            ->when($paymentMethod, function($query, $paymentMethod) {
                return $query->where('payment_method', $paymentMethod);
            })
            ->latest('visit_date')
            ->paginate(15, ['*'], 'daily_page');
            
        $totalDailyUserSales = \App\Models\DailyUser::whereBetween('visit_date', [$startDate, $endDate])
            ->when($paymentMethod, function($query, $paymentMethod) {
                return $query->where('payment_method', $paymentMethod);
            })
            ->sum('amount_paid');
            
        $totalSales = $totalPosSales + $totalMembershipSales + $totalDailyUserSales;
            
        return view('reports.sales', compact(
            'transactions', 
            'membershipPayments',
            'dailyUsers',
            'totalSales', 
            'totalPosSales',
            'totalMembershipSales',
            'totalDailyUserSales',
            'startDate', 
            'endDate'
        ));
    }

    public function members(Request $request)
    {
        // Get regular members
        $regularMembers = Member::with(['activeMembership', 'payments'])
            ->when($request->status, function($query, $status) {
                return $query->where('status', $status);
            })
            ->get();
            
        // Get PT members
        $ptMembers = \App\Models\PTMember::with(['packet', 'personalTrainer'])
            ->when($request->status, function($query, $status) {
                return $query->where('status', $status);
            })
            ->when($request->payment_method, function($query, $paymentMethod) {
                return $query->where('payment_method', $paymentMethod);
            })
            ->get();
            
        // Get memberships with PT category
        $membershipsPT = \App\Models\Membership::with(['member', 'payments'])
            ->where('category', 'pt')
            ->when($request->status, function($query, $status) {
                return $query->where('status', $status);
            })
            ->when($request->payment_method, function($query, $paymentMethod) {
                return $query->whereHas('payments', function($q) use ($paymentMethod) {
                    $q->where('payment_method', $paymentMethod);
                });
            })
            ->get();
            
        // Combine all collections
        $members = $regularMembers->concat($ptMembers)->concat($membershipsPT)->sortByDesc('created_at');
        
        // Manual pagination
        $perPage = 15;
        $currentPage = request()->get('page', 1);
        $offset = ($currentPage - 1) * $perPage;
        $itemsForCurrentPage = $members->slice($offset, $perPage);
        
        $members = new \Illuminate\Pagination\LengthAwarePaginator(
            $itemsForCurrentPage,
            $members->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );
            
        return view('reports.members', compact('members'));
    }

    public function stocks(Request $request)
    {
        $products = Product::with('stockHistories')
            ->when($request->low_stock, function($query) {
                return $query->where('stock', '<', 10);
            })
            ->latest()
            ->paginate(15);
            
        return view('reports.stocks', compact('products'));
    }

    public function exportSales(Request $request)
    {
        $startDate = $request->get('start_date') ? Carbon::parse($request->get('start_date')) : Carbon::now()->startOfMonth();
        $endDate = $request->get('end_date') ? Carbon::parse($request->get('end_date')) : Carbon::now()->endOfMonth();
        $paymentMethod = $request->get('payment_method');
        
        // POS Transactions
        $transactions = Transaction::with(['user', 'details.product'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->when($paymentMethod, function($query, $paymentMethod) {
                return $query->where('payment_method', $paymentMethod);
            })
            ->get();
            
        // Membership Payments
        $membershipPayments = \App\Models\Payment::with(['member', 'membership'])
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->where('status', 'completed')
            ->when($paymentMethod, function($query, $paymentMethod) {
                return $query->where('payment_method', $paymentMethod);
            })
            ->get();
            
        // Daily Users
        $dailyUsers = \App\Models\DailyUser::with('personalTrainer')
            ->whereBetween('visit_date', [$startDate, $endDate])
            ->when($paymentMethod, function($query, $paymentMethod) {
                return $query->where('payment_method', $paymentMethod);
            })
            ->get();
            
        // Calculate totals
        $totalPosSales = $transactions->sum('total_amount');
        $totalMembershipSales = $membershipPayments->sum('amount');
        $totalDailyUserSales = $dailyUsers->sum('amount_paid');
        $totalSales = $totalPosSales + $totalMembershipSales + $totalDailyUserSales;
            
        if ($request->format === 'excel') {
            return Excel::download(new SalesExport($transactions), 'laporan-penjualan-' . date('Y-m-d') . '.xlsx');
        }
            
        $pdf = Pdf::loadView('reports.sales-pdf', compact(
            'transactions', 
            'membershipPayments',
            'dailyUsers',
            'totalSales',
            'totalPosSales',
            'totalMembershipSales', 
            'totalDailyUserSales',
            'startDate', 
            'endDate'
        ));
        return $pdf->download('laporan-penjualan-' . date('Y-m-d') . '.pdf');
    }

    public function exportStocks(Request $request)
    {
        $products = Product::with('stockHistories')
            ->when($request->low_stock, function($query) {
                return $query->where('stock', '<', 10);
            })
            ->get();
            
        if ($request->format === 'excel') {
            return Excel::download(new StocksExport($products), 'laporan-stok-' . date('Y-m-d') . '.xlsx');
        }
            
        $pdf = Pdf::loadView('reports.stocks-pdf', compact('products'));
        return $pdf->download('laporan-stok-' . date('Y-m-d') . '.pdf');
    }

    public function memberships(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth());
        
        $memberships = \App\Models\Membership::with(['member', 'payments'])
            ->when($request->filled('start_date') && $request->filled('end_date'), function($query) use ($startDate, $endDate) {
                return $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->when($request->status, function($query, $status) {
                return $query->where('status', $status);
            })
            ->latest()
            ->paginate(15);
            
        return view('reports.memberships', compact('memberships', 'startDate', 'endDate'));
    }

    public function exportMemberships(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth());
        
        $memberships = \App\Models\Membership::with(['member', 'payments'])
            ->when($request->filled('start_date') && $request->filled('end_date'), function($query) use ($startDate, $endDate) {
                return $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->when($request->status, function($query, $status) {
                return $query->where('status', $status);
            })
            ->get();
            
        $pdf = Pdf::loadView('reports.memberships-pdf', compact('memberships', 'startDate', 'endDate'));
        return $pdf->download('laporan-membership-' . date('Y-m-d') . '.pdf');
    }
}
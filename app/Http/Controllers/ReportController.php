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
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth());
        
        // POS Transactions
        $transactions = Transaction::with(['user', 'details.product'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->latest()
            ->paginate(15);
            
        $totalPosSales = Transaction::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->sum('total_amount');
            
        // Membership Payments
        $membershipPayments = \App\Models\Payment::with(['member', 'membership'])
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->where('status', 'completed')
            ->latest('payment_date')
            ->paginate(15, ['*'], 'membership_page');
            
        $totalMembershipSales = \App\Models\Payment::whereBetween('payment_date', [$startDate, $endDate])
            ->where('status', 'completed')
            ->sum('amount');
            
        // Pengunjung Harians
        $dailyUsers = \App\Models\DailyUser::with('personalTrainer')
            ->whereBetween('visit_date', [$startDate, $endDate])
            ->latest('visit_date')
            ->paginate(15, ['*'], 'daily_page');
            
        $totalDailyUserSales = \App\Models\DailyUser::whereBetween('visit_date', [$startDate, $endDate])
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
        $members = Member::with(['activeMembership', 'payments'])
            ->when($request->status, function($query, $status) {
                return $query->where('status', $status);
            })
            ->latest()
            ->paginate(15);
            
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
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth());
        
        $transactions = Transaction::with(['user', 'details.product'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->get();
            
        if ($request->format === 'excel') {
            return Excel::download(new SalesExport($transactions), 'laporan-penjualan-' . date('Y-m-d') . '.xlsx');
        }
            
        $pdf = Pdf::loadView('reports.sales-pdf', compact('transactions', 'startDate', 'endDate'));
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
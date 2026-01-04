<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Payment;
use App\Models\Expense;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class AccountingController extends Controller
{
    public function simple(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth());
        
        // Income
        $posIncome = Transaction::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->sum('total_amount');
            
        $membershipIncome = Payment::whereBetween('payment_date', [$startDate, $endDate])
            ->where('status', 'completed')
            ->sum('amount');
            
        $totalIncome = $posIncome + $membershipIncome;
        
        // Expenses
        $expenses = Expense::whereBetween('expense_date', [$startDate, $endDate])
            ->with('user')
            ->latest('expense_date')
            ->get();
            
        $totalExpenses = $expenses->sum('amount');
        $netProfit = $totalIncome - $totalExpenses;
        
        return view('accounting.simple', compact(
            'totalIncome', 
            'posIncome', 
            'membershipIncome', 
            'totalExpenses', 
            'netProfit',
            'expenses',
            'startDate',
            'endDate'
        ));
    }
    
    public function income(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth());
        
        $posTransactions = Transaction::with(['user', 'details.product'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->latest()
            ->paginate(10);
            
        $membershipPayments = Payment::with(['member', 'membership'])
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->where('status', 'completed')
            ->latest('payment_date')
            ->paginate(10, ['*'], 'membership_page');
            
        return view('accounting.income', compact(
            'posTransactions',
            'membershipPayments',
            'startDate',
            'endDate'
        ));
    }
    
    public function expense(Request $request)
    {
        $expenses = Expense::with('user')
            ->when($request->start_date, function($query, $date) {
                return $query->whereDate('expense_date', '>=', $date);
            })
            ->when($request->end_date, function($query, $date) {
                return $query->whereDate('expense_date', '<=', $date);
            })
            ->latest('expense_date')
            ->paginate(15);
            
        return view('accounting.expense', compact('expenses'));
    }
    
    public function storeExpense(Request $request)
    {
        $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|string',
            'category' => 'required|string|max:100',
            'expense_date' => 'required|date',
            'notes' => 'nullable|string'
        ]);
        
        // Convert Indonesian format to number
        $amount = str_replace(['Rp', '.', ' '], '', $request->amount);
        $amount = str_replace(',', '.', $amount);
        
        Expense::create([
            'description' => $request->description,
            'amount' => (float) $amount,
            'category' => $request->category,
            'expense_date' => $request->expense_date,
            'notes' => $request->notes,
            'user_id' => auth()->id()
        ]);
        
        return redirect()->route('accounting.index')
            ->with('success', 'Pengeluaran berhasil ditambahkan.');
    }
    
    public function deleteExpense(Expense $expense)
    {
        $expense->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Pengeluaran berhasil dihapus'
        ]);
    }
    
    public function restoreExpense($id)
    {
        $expense = Expense::withTrashed()->findOrFail($id);
        $expense->restore();
        
        return response()->json([
            'success' => true,
            'message' => 'Pengeluaran berhasil dikembalikan'
        ]);
    }
    
    public function restoreAllExpenses(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth());
        
        Expense::withTrashed()
            ->whereBetween('expense_date', [$startDate, $endDate])
            ->whereNotNull('deleted_at')
            ->restore();
            
        return response()->json([
            'success' => true,
            'message' => 'Semua pengeluaran berhasil dikembalikan'
        ]);
    }
    
    public function profitLoss(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth());
        
        // Income breakdown
        $posIncome = Transaction::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->sum('total_amount');
            
        $membershipIncome = Payment::whereBetween('payment_date', [$startDate, $endDate])
            ->where('status', 'completed')
            ->sum('amount');
            
        // Expense breakdown by category
        $expensesByCategory = Expense::whereBetween('expense_date', [$startDate, $endDate])
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->get();
            
        $totalIncome = $posIncome + $membershipIncome;
        $totalExpenses = $expensesByCategory->sum('total');
        $netProfit = $totalIncome - $totalExpenses;
        
        return view('accounting.profit-loss', compact(
            'posIncome',
            'membershipIncome',
            'totalIncome',
            'expensesByCategory',
            'totalExpenses',
            'netProfit',
            'startDate',
            'endDate'
        ));
    }
}
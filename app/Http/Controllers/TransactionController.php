<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TransactionController extends Controller
{
    /**
     * Display a listing of all transactions
     */
    public function index(Request $request)
    {
        $query = Transaction::with('client')->orderBy('created_at', 'desc');

        // Filter by status if provided
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Search by transaction number, student name, email, phone, or reference
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'LIKE', "%{$search}%")
                  ->orWhere('transaction_number', 'LIKE', "%{$search}%")
                  ->orWhere('student_name', 'LIKE', "%{$search}%")
                  ->orWhere('student_id', 'LIKE', "%{$search}%")
                  ->orWhere('customer_email', 'LIKE', "%{$search}%")
                  ->orWhere('customer_phone', 'LIKE', "%{$search}%")
                  ->orWhere('client_ref', 'LIKE', "%{$search}%")
                  ->orWhere('reference', 'LIKE', "%{$search}%")
                  ->orWhere('reqid', 'LIKE', "%{$search}%");
            });
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from !== '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to !== '') {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $transactions = $query->paginate(20)->withQueryString();

        return Inertia::render('Admin/Pages/Transactions/Index', [
            'transactions' => $transactions,
            'filters' => $request->only(['search', 'status', 'date_from', 'date_to']),
        ]);
    }

    /**
     * Display the specified transaction
     */
    public function show(Transaction $transaction)
    {
        $transaction->load('client');

        return Inertia::render('Admin/Pages/Transactions/Show', [
            'transaction' => $transaction,
        ]);
    }
}


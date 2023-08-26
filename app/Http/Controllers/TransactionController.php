<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function ledger()
    {
        $user = auth()->guard('api')->user();        
        $transactions = Transaction::where('user_id', $user->id)->latest()->take(10)->get();
        
        return view('ledger', compact('transactions', 'user'));
    }

    public function index()
    {
        $user = auth()->guard('api')->user();
        $transactions = Transaction::where('user_id', $user->id)->latest()->take(10)->get();
        
        return response()->json($transactions);
    }

    public function store(Request $request)
    {
        
        $user = Auth::user();
        $transaction = new Transaction([
            'description' => $request->input('description'),
            'amount' => $request->input('amount'),
        ]);

        $user->transactions()->save($transaction);

        return response()->json(['message' => 'Transaction created successfully']);
    }

    public function update(Request $request, Transaction $transaction)
    {
        $transaction->description = $request->input('description');
        $transaction->amount = $request->input('amount');
        $transaction->save();

        return response()->json(['message' => 'Transaction updated successfully']);
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();

        return response()->json(['message' => 'Transaction deleted successfully']);
    }

   
}

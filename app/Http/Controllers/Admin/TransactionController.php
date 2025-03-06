<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CardTransaction;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function wallet(Request $request)
    {
        try {
            //code...
            $data['dashboard_title'] = "Wallet Transactions";
            $query = WalletTransaction::with(['user', 'wallet']);

            // Apply filters
            if ($request->filled('status') && $request->status !== 'all') {
                $query->where('status', $request->status);
            }
            if ($request->filled('type') && $request->type !== 'all') {
                $query->where('type', $request->type);
            }
            if ($request->filled('specific_date')) {
                $query->whereDate('created_at', $request->specific_date);
            }
            if ($request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
            }

            // Perform search
            // if ($request->filled('search')) {
            //     $query->whereHas('user', function ($q) use ($request) {
            //         $q->where('first_name', 'like', '%' . $request->search . '%')
            //           ->orWhere('last_name', 'like', '%' . $request->search . '%')
            //           ->orWhere('email', 'like', '%' . $request->search . '%');
            //     });
            // }

            $data['transactions'] = $query->paginate(10);

            return view('admin.transactions.wallet', $data);
        } catch (\Throwable $th) {
            dd($th);
            //throw $th;
        }
    }

    public function wallet_view($uuid)
    {
        try {
            //code...
            $data['dashboard_title'] = "Wallet Transactions";
            $data['transaction'] = WalletTransaction::with(['user', 'wallet', ])->where('uuid', $uuid)->firstOrFail();
            return view('admin.transactions.view-card-trans', $data);
        } catch (\Throwable $th) {
            dd($th);
            //throw $th;
        }
    }

    public function wallet_download($uuid)
    {
        try {
            $transaction = WalletTransaction::with(['user', 'wallet'])->where('uuid', $uuid)->firstOrFail();
            $pdf = app('dompdf.wrapper');
            $pdf->loadView('exports.wallet_transaction_single', compact('transaction'));
            return $pdf->download('wallet_transaction_' . $uuid . '.pdf');
        } catch (\Throwable $th) {
            dd($th);
            //throw $th;
        }
    }

    public function card(Request $request)
    {
        try {
            //code...
            $data['dashboard_title'] = "Card Transactions";

            $query = CardTransaction::with(['user', 'card']);

            // Filters
            if ($request->filled('status') && $request->status !== 'all') {
                $query->where('status', $request->status);
            }

            if ($request->filled('type') && $request->type !== 'all') {
                $query->where('type', $request->type);
            }

            if ($request->filled('date_added')) {
                $query->whereDate('created_at', $request->date_added);
            }

            // Apply date range filter
            if ($request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
            }

            // Get filtered results
            $data['transactions'] = $query->paginate(10);

            // dd($data);

            return view('admin.transactions.card', $data);
        } catch (\Throwable $th) {
            dd($th);
            //throw $th;
        }
    }
    public function card_transaction($uuid)
    {
        try {
            //code...
            $data['dashboard_title'] = "Card Transactions";
            $data['transaction'] = CardTransaction::where('uuid', $uuid)->with(['user', 'card'])->firstOrFail();
            return view('admin.transactions.view-card-trans', $data);
        } catch (\Throwable $th) {
            dd($th);
            //throw $th;
        }
    }
    public function card_download($uuid)
    {
        try {
            //code...
            $transaction = CardTransaction::with(['user', 'card'])->where('uuid', $uuid)->firstOrFail();
            $pdf = app('dompdf.wrapper');
            $pdf->loadView('exports.card_transactions_single', ['transaction' => $transaction]);
            return $pdf->download('transaction_' . $uuid . '.pdf');
        } catch (\Throwable $th) {
            dd($th);
            //throw $th;
        }
    }
}

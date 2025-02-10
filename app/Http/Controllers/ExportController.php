<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Admin;
use App\Models\CardTransaction;
use App\Models\Team;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Services\ExportService;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade as PDF;

class ExportController extends Controller
{protected $exportService;

    public function __construct(ExportService $exportService)
    {
        $this->exportService = $exportService;
    }

    public function export(Request $request)
    {
       try {
        //code...
        $format = $request->get('format'); // 'excel' or 'pdf'
        $table = $request->get('table'); // e.g., 'users', 'orders'

        // Fetch data dynamically based on the table
        $data = $this->fetchTableData($table);

        // Define a unique filename and the corresponding Blade view
        $filename = ucfirst($table) . '_data';
        $view = 'exports.' . $table; // Create Blade views per table

        
        // Export based on format
        if ($format === 'excel') {
            return $this->exportService->exportToExcel($filename, ['data' => $data], $view);
        } elseif ($format === 'pdf') {
            return $this->exportService->exportToPDF($filename, ['data' => $data], $view);
        }

        return response()->json(['error' => 'Invalid format'], 400);
       } catch (\Throwable $th) {
        dd($th->getMessage());
        //throw $th;
       }
    }

    protected function fetchTableData(string $table)
    {
        switch ($table) {
            case 'users':
                return User::all();
            case 'admins':
                return Admin::all();
            case 'card_transactions':
                return CardTransaction::all();
            case 'wallet_transactions':
                return WalletTransaction::all();
            case 'addresses':
                return Address::all();
            case 'teams':
                return Team::all();
            case 'wallets':
                return Wallet::all();
            default:
                throw new \Exception("Table '{$table}' is not supported.");
        }
    }
}

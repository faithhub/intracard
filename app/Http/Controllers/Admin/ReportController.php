<?php
namespace App\Http\Controllers\Admin;

use App\Helpers\TableColumnsConfig;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index()
    {
        try {
            //code...
            $data['dashboard_title'] = "Report";
            $data['tables'] = [
                [
                    "table" => "admins",
                    "name" => "Admin Table",
                ],
                [
                    "table" => "users",
                    "name" => "User Table",
                ],
                [
                    "table" => "addresses",
                    "name" => "Address Table",
                ],
                [
                    "table" => "card_transactions",
                    "name" => "Card Transaction Table",
                ],
                [
                    "table" => "wallet_transactions",
                    "name" => "Wallet Transaction Table",
                ],
                [
                    "table" => "wallets",
                    "name" => "Wallet Table",
                ],
                [
                    "table" => "teams",
                    "name" => "Team Table",
                ],
            ];

            return view('admin.reports.index', $data);
        } catch (\Throwable $th) {
            dd($th);
            //throw $th;
        }
    }
    public function getTableColumns(Request $request)
    {
        try {
            $table = strtolower($request->input('table'));

            if (empty($table)) {
                return response()->json([
                    'error' => 'The table parameter is required.',
                ], 400);
            }

            $columnsToShow = TableColumnsConfig::getColumnsToShow();

            if (!isset($columnsToShow[$table])) {
                return response()->json([
                    'error' => 'Table configuration not found.',
                ], 400);
            }

            $tableConfig = $columnsToShow[$table];
            return response()->json([
                'columns' => $tableConfig['columns'],
                'relations' => $tableConfig['relations'] ?? [],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch table columns: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function queryTable(Request $request)
    {
        try {
            $table = strtolower($request->input('table'));
            $columnsToShow = TableColumnsConfig::getColumnsToShow();

            if (!isset($columnsToShow[$table])) {
                return response()->json([
                    'error' => 'Table configuration not found.',
                ], 400);
            }

            $tableConfig = $columnsToShow[$table];
            $columns = $tableConfig['columns'];
            $relations = $tableConfig['relations'] ?? [];

            // Define model mapping
            $modelMapping = [
                'admins' => \App\Models\Admin::class,
                'users' => \App\Models\User::class,
                'addresses' => \App\Models\Address::class,
                'card_transactions' => \App\Models\CardTransaction::class,
                'wallet_transactions' => \App\Models\WalletTransaction::class,
                'wallets' => \App\Models\Wallet::class,
                'teams' => \App\Models\Team::class,
            ];

            if (!array_key_exists($table, $modelMapping)) {
                return response()->json([
                    'error' => 'Model not found for table.',
                ], 400);
            }

            $model = $modelMapping[$table];
            $query = $model::query();

            // Apply filters if provided
            if ($request->has('filters')) {
                $filters = $request->input('filters');
                foreach ($filters as $column => $value) {
                    if (!empty($value)) {
                        $query->where($column, 'LIKE', "%{$value}%");
                    }
                }
            }

            // Apply date range filter if provided
            if ($request->has('date_from') && $request->has('date_to')) {
                $query->whereBetween('created_at', [
                    $request->input('date_from'),
                    $request->input('date_to'),
                ]);
            }

            // Eager-load relationships
            if (!empty($relations)) {
                $query->with(array_keys($relations));
            }

            // Fetch results
            $results = $query->get();

            // Define table display names
            $tableNames = [
                'wallets' => 'Wallets Table',
                'users' => 'Users Table',
                'card_transactions' => 'Card Transactions Table',
                'wallet_transactions' => 'Wallet Transactions Table',
                'admins' => 'Administrators Table',
                'addresses' => 'Addresses Table',
                'teams' => 'Teams Table',
            ];

            return response()->json([
                'tableName' => $tableNames[$table] ?? ucfirst(str_replace('_', ' ', $table)),
                'data' => $results,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch table data: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function queryTable2(Request $request)
    {
        $table = strtolower($request->input('table'));
        $filters = $request->input('filters', []);

        $columnsToShow = TableColumnsConfig::getColumnsToShow();

        // Check if the table exists in columnsToShow
        if (!isset($columnsToShow[$table])) {
            return response()->json(['columnsToShow' => $columnsToShow, 'error' => 'Table configuration not found.'], 400);
        }

        $tableConfig = $columnsToShow[$table];
        $columns = $tableConfig['columns'];
        $relations = $tableConfig['relations'];

        // Define model mapping
        $modelMapping = [
            'admins' => \App\Models\Admin::class,
            'users' => \App\Models\User::class,
            'addresses' => \App\Models\Address::class,
            'card_transactions' => \App\Models\CardTransaction::class,
            'wallet_transactions' => \App\Models\WalletTransaction::class,
            'wallets' => \App\Models\Wallet::class,
            'teams' => \App\Models\Team::class,
        ];

        if (!array_key_exists($table, $modelMapping)) {
            return response()->json(['error' => 'Model not found for table.'], 400);
        }

        $model = $modelMapping[$table];
        $query = $model::query();

        // Apply filters
        foreach ($filters as $column => $value) {
            $query->where($column, $value);
        }

        // Eager-load relationships
        if (!empty($relations)) {
            $query->with(array_keys($relations));
        }

        // Fetch results
        $results = $query->get($columns);

        // Append related data
        // foreach ($relations as $relation => $relationColumns) {
        //     foreach ($relationColumns as $relatedColumn) {
        //         $results->map(function ($row) use ($relation, $relatedColumn) {
        //             $row->$relatedColumn = $row->$relation ? $row->$relation->$relatedColumn : null;
        //             return $row;
        //         });
        //     }
        // }
        // Include relationship fields in the response
        foreach ($results as $row) {
            foreach ($relations as $relation => $relationFields) {
                if ($row->$relation) {
                    foreach ($relationFields as $field) {
                        // Parse the alias (e.g., "first_name as user_first_name")
                        $parts = explode(' as ', $field);
                        $originalField = $parts[0];
                        $alias = $parts[1] ?? $originalField;

                        $row->$alias = $row->$relation->$originalField ?? null;
                    }
                }
            }
        }

        return response()->json($results);
    }

    public function exportData(Request $request)
    {
        $table = $request->input('table');
        $filters = $request->input('filters', []); // Default to empty array if no filters
        $format = $request->input('format');
        $columns = $request->input('columns', []); // Columns to export (from the frontend)
        $relations = $request->input('relations', []); // Relationship fields

        // Validate table
        if (!Schema::hasTable($table)) {
            return response()->json(['error' => 'Invalid table'], 400);
        }

        // Start query
        $query = DB::table($table);

        // Apply filters
        foreach ($filters as $column => $value) {
            $query->where($column, $value);
        }

        // Fetch data with relations if specified
        if (!empty($relations)) {
            // Define model mapping
            $modelMapping = [
                'admins' => \App\Models\Admin::class,
                'users' => \App\Models\User::class,
                'addresses' => \App\Models\Address::class,
                'card_transactions' => \App\Models\CardTransaction::class,
                'wallet_transactions' => \App\Models\WalletTransaction::class,
                'wallets' => \App\Models\Wallet::class,
                'teams' => \App\Models\Team::class,
            ];
            if (!array_key_exists($table, $modelMapping)) {
                return response()->json(['error' => 'Model not found for table.'], 400);
            }

            $model = $modelMapping[$table];
            $query = $model::query()->select($columns);

            // Eager-load relations
            $query->with(array_keys($relations));
            $data = $query->get();

            // Flatten relationship fields
            $data = $data->map(function ($item) use ($relations) {
                $item = $item->toArray();
                foreach ($relations as $relation => $fields) {
                    if (isset($item[$relation])) {
                        foreach ($fields as $field) {
                            $item["{$relation}_{$field}"] = $item[$relation][$field] ?? null;
                        }
                        unset($item[$relation]); // Remove nested relation
                    }
                }
                return $item;
            });
        } else {
            $data = $query->get($columns);
        }

        // Export data based on format
        if ($format === 'csv' || $format === 'excel') {
            return Excel::download(new DataExport($data), 'report.' . $format);
        } elseif ($format === 'pdf') {
            $pdf = app('dompdf.wrapper');
            $pdf->loadView('admin.report', ['data' => $data, 'columns' => $columns]);
            return $pdf->download('report.pdf');
        }

        return response()->json(['error' => 'Invalid export format'], 400);
    }

    public function equifax()
    {
        try {
            //code...
            $data['dashboard_title'] = "Equifax Report";
            return view('admin.reports.equifax', $data);
        } catch (\Throwable $th) {
            dd($th);
            //throw $th;
        }
    }
    public function transunion()
    {
        try {
            //code...
            $data['dashboard_title'] = "Transunion Report";
            return view('admin.reports.transunion', $data);
        } catch (\Throwable $th) {
            dd($th);
            //throw $th;
        }
    }
}

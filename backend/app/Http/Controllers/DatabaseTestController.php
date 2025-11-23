<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Exception;

class DatabaseTestController extends Controller
{
    public function testConnection(): JsonResponse
    {
        try {
            $pdo = DB::connection()->getPdo();
            $driver = DB::connection()->getDriverName();
            $dbName = DB::connection()->getDatabaseName();
            
            $tables = [];
            if ($driver === 'pgsql') {
                $result = DB::select('SELECT table_name FROM information_schema.tables WHERE table_schema = ?', ['public']);
                $tables = array_map(fn($table) => $table->table_name, $result);
            } elseif ($driver === 'mysql' || $driver === 'mariadb') {
                $result = DB::select('SHOW TABLES');
                $key = 'Tables_in_' . $dbName;
                $tables = array_map(fn($table) => $table->$key, $result);
            } elseif ($driver === 'sqlite') {
                $result = DB::select("SELECT name FROM sqlite_master WHERE type='table'");
                $tables = array_map(fn($table) => $table->name, $result);
            }
            
            return response()->json([
                'status' => 'success',
                'message' => 'Database connection successful',
                'driver' => $driver,
                'database' => $dbName,
                'connection' => config('database.default'),
                'tables' => $tables,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Database connection failed',
                'error' => $e->getMessage(),
                'connection' => config('database.default'),
            ], 500);
        }
    }
}


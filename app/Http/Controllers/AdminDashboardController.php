<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $allStok = DB::table('barang')->sum('stok');

        // Barang baru maksimal 3 hari dari hari ini
        $barangBaru = DB::table('barang')
            ->where('tanggal_pembelian', '>=', now()->subDays(3))
            ->count();

        $permintaan = DB::table('permintaan')->count();

        $adminName = session('auth_name');
        $adminPhoto = session('auth_photo');

        $barangTerbaru = DB::table('barang')
            ->orderByDesc('tanggal_pembelian')
            ->limit(5)
            ->get();

        $welcome = session()->pull('welcome', false); // ambil lalu hapus

        return view('dashboard.admin', compact(
            'allStok',
            'barangBaru',
            'permintaan',
            'adminName',
            'adminPhoto',
            'barangTerbaru',
            'welcome'
        ));
    }
}

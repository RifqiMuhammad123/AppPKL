<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

  public function updateProfile(Request $request)
{
    // PERBAIKAN: Ganti admin_id menjadi auth_id
    $adminId = session('auth_id'); 
    
    if (!$adminId) {
        return back()->with('error', 'Session expired, silakan login lagi');
    }

    $request->validate([
        'nama_admin' => 'required|string|max:255',
        'password' => 'nullable|min:6|confirmed',
    ]);

    $updateData = [
        'nama_admin' => $request->nama_admin,
    ];

    if (!empty($request->password)) {
        $updateData['password'] = Hash::make($request->password);
        $updateData['password_plain'] = $request->password;
    }

    // Update ke database - pastikan nama tabel benar
    DB::table('admin')->where('id_admin', $adminId)->update($updateData);

    // Ambil data yang sudah diupdate
    $user = DB::table('admin')->where('id_admin', $adminId)->first();

    // Update session dengan data terbaru
    session([
        'auth_name' => $user->nama_admin,
        'auth_photo' => $user->foto,
        'auth_password_plain' => $user->password_plain ?? null,
    ]);

    return back()->with('success', 'Profil berhasil diperbarui.');
}
}
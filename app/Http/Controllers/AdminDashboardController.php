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

        // Barang dengan stok rendah
        $barangStokRendah = DB::table('barang')
            ->whereRaw('stok <= COALESCE(stok_minimum, 10)')
            ->get();

        $adminName = session('auth_name');
        $adminPhoto = session('auth_photo');

        $barangTerbaru = DB::table('barang')
            ->orderByDesc('tanggal_pembelian')
            ->limit(5)
            ->get();

        $welcome = session()->pull('welcome', false);

        return view('dashboard.admin', compact(
            'allStok',
            'barangBaru',
            'permintaan',
            'adminName',
            'adminPhoto',
            'barangTerbaru',
            'barangStokRendah',
            'welcome'
        ));
    }

    public function updateProfile(Request $request)
    {
        $adminId = session('auth_id'); 
        
        if (!$adminId) {
            return back()->with('error', 'Session expired, silakan login lagi');
        }

        $request->validate([
            'nama_admin' => 'required|string|max:255',
            'password' => 'nullable|min:6',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $updateData = [
            'nama_admin' => $request->nama_admin,
        ];

        // Handle upload foto
        if ($request->hasFile('foto')) {
            $oldUser = DB::table('admin')->where('id_admin', $adminId)->first();
            
            if ($oldUser->foto && $oldUser->foto !== 'icon.jpg' && file_exists(public_path('img/' . $oldUser->foto))) {
                unlink(public_path('img/' . $oldUser->foto));
            }
            
            $foto = $request->file('foto');
            $namaFoto = time() . '.' . $foto->getClientOriginalExtension();
            $foto->move(public_path('img'), $namaFoto);
            
            $updateData['foto'] = $namaFoto;
        }

        if (!empty($request->password)) {
            $updateData['password'] = Hash::make($request->password);
            $updateData['password_plain'] = $request->password;
        }

        DB::table('admin')->where('id_admin', $adminId)->update($updateData);

        $user = DB::table('admin')->where('id_admin', $adminId)->first();

        session([
            'auth_name' => $user->nama_admin,
            'auth_photo' => $user->foto,
            'auth_password_plain' => $user->password_plain ?? null,
        ]);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
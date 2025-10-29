<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use TCPDF;

class AdminDashboardController extends Controller
{
    /**
     * Tampilkan dashboard admin
     */
    public function index()
    {
        $allStok = DB::table('barang')->sum('stok');

        $barangBaru = DB::table('barang')
            ->where('tanggal_pembelian', '>=', now()->subDays(3))
            ->count();

        $permintaan = DB::table('permintaan')->count();

        $barangStokRendah = DB::table('barang')
            ->whereRaw('stok <= COALESCE(stok_minimum, 10)')
            ->get();

        $barangTerbaru = DB::table('barang')
            ->orderByDesc('tanggal_pembelian')
            ->limit(5)
            ->get();

        $adminName  = session('auth_name');
        $adminPhoto = session('auth_photo');
        $welcome    = session()->pull('welcome', false);

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

    /**
     * Update profil admin
     */
    public function updateProfile(Request $request)
    {
        $adminId = session('auth_id');

        if (!$adminId) {
            return back()->with('error', 'Sesi login telah berakhir. Silakan login ulang.');
        }

        $request->validate([
            'nama_admin' => 'required|string|max:255',
            'password'   => 'nullable|min:6',
            'foto'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // --- Data yang akan diupdate
        $updateData = [
            'nama_admin' => $request->nama_admin,
        ];

        // --- Handle upload foto baru
        if ($request->hasFile('foto')) {
            $oldUser = DB::table('admin')->where('id_admin', $adminId)->first();

            if (
                $oldUser->foto &&
                $oldUser->foto !== 'icon.jpg' &&
                file_exists(public_path('img/' . $oldUser->foto))
            ) {
                unlink(public_path('img/' . $oldUser->foto));
            }

            $foto = $request->file('foto');
            $namaFoto = time() . '.' . $foto->getClientOriginalExtension();
            $foto->move(public_path('img'), $namaFoto);

            $updateData['foto'] = $namaFoto;
        }

        // --- Update password (jika diisi)
        if (!empty($request->password)) {
            $updateData['password'] = Hash::make($request->password);
            $updateData['password_plain'] = $request->password; // tambahkan baris ini
        }


        // --- Simpan perubahan ke database
        DB::table('admin')->where('id_admin', $adminId)->update($updateData);

        // --- Update session
        $user = DB::table('admin')->where('id_admin', $adminId)->first();
        
        session([
            'auth_name'  => $user->nama_admin,
            'auth_photo' => $user->foto,
            'auth_password_plain' => $user->password_plain, // 🔹 tambahkan ini
        ]);


        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Menampilkan data semua admin
     */
    public function dataAdmin()
    {
        $admins = DB::table('admin')
            ->orderBy('nama_admin', 'asc')
            ->get();

        return view('dashboard.data-admin', compact('admins'));
    }

    /**
     * Download PDF Data Admin
     */
    public function dataAdminPdf()
    {
        $admins = DB::table('admin')
            ->select('nip', 'nama_admin', 'password')
            ->orderBy('nama_admin', 'asc')
            ->get();

        // --- Inisialisasi TCPDF
        $pdf = new TCPDF('L', PDF_UNIT, 'A4', true, 'UTF-8', false);
        $pdf->SetCreator('Laravel');
        $pdf->SetAuthor('Sistem Admin');
        $pdf->SetTitle('Data Admin');
        $pdf->SetMargins(10, 10, 10);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->AddPage();

        // --- Render view HTML ke PDF
        $html = view('dashboard.data-admin-pdf', compact('admins'))->render();
        $pdf->writeHTML($html, true, false, true, false, '');

        $fileName = 'Data_Admin_' . date('Y-m-d_His') . '.pdf';

        // --- Kirim PDF ke browser
        return response($pdf->Output($fileName, 'S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }
}
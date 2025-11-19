<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permintaan;
use App\Models\Guru;
use App\Models\Barang;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB; 
use TCPDF;

class PermintaanController extends Controller
{
    /** 🔹 Halaman form ajukan permintaan (Guru) */
   public function create()
{
    $barang = Barang::all(); // Ambil semua barang
    return view('dashboard.guru-permintaan', compact('barang'));
}

/** 🔹 Simpan permintaan baru (Guru) */
public function store(Request $request)
{
    $request->validate([
        'id_barang' => 'required|exists:barang,id_barang',
        'tanggal'   => 'required|date',
        'jumlah'    => 'required|integer|min:1'
    ]);

    // Ambil data barang berdasarkan id_barang yang dipilih
    $barang = Barang::findOrFail($request->id_barang);

    Permintaan::create([
        'id_guru'     => session('auth_id'),
        'id_barang'   => $request->id_barang,
        'nama_barang' => $barang->nama_barang,
        'merk_barang' => $barang->merk_barang,
        'tanggal'     => $request->tanggal,
        'jumlah'      => $request->jumlah,
        'status'      => 'pending'
    ]);

    return redirect()->route('guru.permintaan.proses')
        ->with('success', 'Permintaan berhasil diajukan!');
}

    /** 🔹 Halaman riwayat permintaan Guru */
    public function proses()
    {
        // hapus permintaan yang lebih dari 7 hari
        $batas = now()->subDays(7);
        Permintaan::where('created_at', '<', $batas)->delete();

        $permintaan = Permintaan::where('id_guru', session('auth_id'))
            ->orderBy('id_permintaan', 'desc')
            ->get();

        return view('dashboard.guru-proses', compact('permintaan'));
    }

    /** 🔹 Halaman Admin: lihat semua permintaan */
    public function adminIndex()
    {
        // hapus permintaan lama juga untuk admin
        $batas = now()->subDays(7);
        Permintaan::where('created_at', '<', $batas)->delete();

        $permintaan = Permintaan::with('guru')
            ->latest()
            ->get();

        return view('dashboard.permintaan-admin', compact('permintaan'));
    }

    /** 🔹 Admin konfirmasi permintaan */
    public function konfirmasi($id)
    {
        $permintaan = Permintaan::where('id_permintaan', $id)->firstOrFail();

        if ($permintaan->status === 'pending') {
            // Ambil barang sesuai nama + merk
            $barang = Barang::where('nama_barang', $permintaan->nama_barang)
                            ->where('merk_barang', $permintaan->merk_barang)
                            ->first();

            if ($barang) {
                // Kurangi stok sesuai jumlah permintaan
                $barang->stok -= $permintaan->jumlah;

                // Biar gak minus
                if ($barang->stok < 0) {
                    $barang->stok = 0;
                }

                $barang->save();
            }

            // Update status permintaan
            $permintaan->status = 'dikonfirmasi'; 
            $permintaan->save();
        }

        return redirect()->route('admin.permintaan.index')
            ->with('success', 'Permintaan berhasil dikonfirmasi & stok diperbarui.');
    }

    /** 🔹 Admin tolak permintaan */
    public function tolak(Request $request, $id)
    {
        $permintaan = Permintaan::where('id_permintaan', $id)->firstOrFail();

        if ($permintaan->status === 'pending') {
            $permintaan->status = 'ditolak'; // ✅ sesuai enum DB
            $permintaan->catatan = $request->catatan ?? '_'; // ✅ simpan ke kolom catatan
            $permintaan->save();
        }

        return redirect()->route('admin.permintaan.index')
            ->with('success', 'Permintaan berhasil ditolak.');
    }

    /** 🔹 Cek jumlah notif permintaan pending (Admin) */
    public function cekNotif()
    {
        $count = Permintaan::where('status', 'pending')->count();
        return response()->json(['pending' => $count]);
    }

    /** 🔹 API untuk Guru: ambil status permintaan realtime */
    public function cekStatusGuru()
    {
        // hapus permintaan yang lebih dari 7 hari
        $batas = now()->subDays(7);
        Permintaan::where('created_at', '<', $batas)->delete();

        $permintaan = Permintaan::where('id_guru', session('auth_id'))
            ->orderBy('id_permintaan','desc')
            ->get();

        return response()->json($permintaan);
    }

    /** 🔹 Guru ajukan permintaan langsung dari Barang */
    public function fromBarang($id)
    {
        $barang = \App\Models\Barang::findOrFail($id);
        return view('dashboard.guru-ajukan-dari-barang', compact('barang'));
    }

    /** 🔹 Simpan permintaan dari Barang */
    public function storeFromBarang(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jumlah'  => 'required|integer|min:1',
        ]);

        $barang = \App\Models\Barang::findOrFail($id);

        Permintaan::create([
            'id_guru'     => session('auth_id'),
            'nama_barang' => $barang->nama_barang,
            'merk_barang' => $barang->merk_barang,
            'tanggal'     => $request->tanggal,
            'jumlah'      => $request->jumlah,
            'status'      => 'pending',
        ]);

        return redirect()->route('guru.permintaan.proses')
            ->with('success', 'Permintaan barang berhasil diajukan!');
    }

    public function history()
{
    $riwayatPermintaan = DB::table('permintaan as p')
        ->join('guru as g', 'p.id_guru', '=', 'g.id_guru')
        ->select(
            'p.*',
            'g.nama_guru'
        )
        ->where('p.status', '!=', 'pending')
        ->orderByDesc('p.tanggal')
        ->get();

       return view('dashboard.permintaan.history', compact('riwayatPermintaan'));

}
    

    public function downloadHistoryPdf()
{
    // Ambil data riwayat permintaan (status bukan pending)
    $riwayatPermintaan = DB::table('permintaan as p')
        ->join('guru as g', 'p.id_guru', '=', 'g.id_guru')
        ->select(
            'p.*',
            'g.nama_guru'
        )
        ->where('p.status', '!=', 'pending')
        ->orderByDesc('p.tanggal')
        ->get();

    // --- Inisialisasi TCPDF
    $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
    $pdf->SetCreator('Laravel');
    $pdf->SetAuthor('Sistem Permintaan');
    $pdf->SetTitle('Riwayat Permintaan');
    $pdf->SetMargins(10, 5, 10);
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    $pdf->AddPage();

    // --- Render view blade ke PDF
    $html = view('dashboard.permintaan.pdf', compact('riwayatPermintaan'))->render();
    $pdf->writeHTML($html, true, false, true, false, '');

    $fileName = 'Riwayat_Permintaan_' . date('Y-m-d_His') . '.pdf';

    // --- Kirim PDF ke browser
    return response($pdf->Output($fileName, 'S'))
        ->header('Content-Type', 'application/pdf')
        ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
}


}
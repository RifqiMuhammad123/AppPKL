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
    $barang = Barang::all();
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
        // ✅ Hapus permintaan yang lebih dari 3 BULAN
        $batas = now()->subMonths(3);
        Permintaan::where('created_at', '<', $batas)->delete();

        $permintaan = Permintaan::where('id_guru', session('auth_id'))
            ->orderBy('id_permintaan', 'desc')
            ->get();

        return view('dashboard.guru-proses', compact('permintaan'));
    }

    /** 🔹 Halaman Admin: lihat semua permintaan */
    public function adminIndex()
    {
        // ✅ Hapus permintaan lama juga untuk admin (3 bulan)
        $batas = now()->subMonths(3);
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
            $barang = Barang::where('nama_barang', $permintaan->nama_barang)
                            ->where('merk_barang', $permintaan->merk_barang)
                            ->first();

            if ($barang) {
                $barang->stok -= $permintaan->jumlah;

                if ($barang->stok < 0) {
                    $barang->stok = 0;
                }

                $barang->save();
            }

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
            $permintaan->status = 'ditolak';
            $permintaan->catatan = $request->catatan ?? '_';
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
        // ✅ Hapus permintaan yang lebih dari 3 bulan
        $batas = now()->subMonths(3);
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

    /** 🔹 Riwayat dengan Filter Bulan, Tahun, dan Status */
    public function history(Request $request)
    {
        // ✅ Ambil parameter filter
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');
        $status = $request->input('status');

        $query = DB::table('permintaan as p')
            ->join('guru as g', 'p.id_guru', '=', 'g.id_guru')
            ->select(
                'p.*',
                'g.nama_guru'
            )
            ->where('p.status', '!=', 'pending');

        // ✅ Filter berdasarkan bulan jika ada
        if ($bulan) {
            $query->whereMonth('p.tanggal', $bulan);
        }

        // ✅ Filter berdasarkan tahun jika ada
        if ($tahun) {
            $query->whereYear('p.tanggal', $tahun);
        }

        // ✅ Filter berdasarkan status jika ada
        if ($status) {
            $query->where('p.status', $status);
        }

        $riwayatPermintaan = $query->orderByDesc('p.tanggal')->get();

        // ✅ Kirim semua parameter ke view
        return view('dashboard.permintaan.history', compact('riwayatPermintaan', 'bulan', 'tahun', 'status'));
    }
    

    /** 🔹 Download PDF dengan Filter */
    public function downloadHistoryPdf(Request $request)
    {
        // ✅ Ambil parameter filter
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');
        $status = $request->input('status');

        $query = DB::table('permintaan as p')
            ->join('guru as g', 'p.id_guru', '=', 'g.id_guru')
            ->select(
                'p.*',
                'g.nama_guru'
            )
            ->where('p.status', '!=', 'pending');

        // ✅ Filter berdasarkan bulan jika ada
        if ($bulan) {
            $query->whereMonth('p.tanggal', $bulan);
        }

        // ✅ Filter berdasarkan tahun jika ada
        if ($tahun) {
            $query->whereYear('p.tanggal', $tahun);
        }

        // ✅ Filter berdasarkan status jika ada
        if ($status) {
            $query->where('p.status', $status);
        }

        $riwayatPermintaan = $query->orderByDesc('p.tanggal')->get();

        // ✅ Debug: cek apakah data ada
        // dd($riwayatPermintaan); // Uncomment untuk test

        // --- Inisialisasi TCPDF
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false); // ✅ L = Landscape biar lebar
        $pdf->SetCreator('Laravel');
        $pdf->SetAuthor('Sistem Permintaan');
        $pdf->SetTitle('Riwayat Permintaan');
        $pdf->SetMargins(10, 10, 10);
        $pdf->SetAutoPageBreak(true, 10);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->AddPage();

        // --- Render view blade ke PDF
        $html = view('dashboard.permintaan.pdf', compact('riwayatPermintaan', 'bulan', 'tahun', 'status'))->render();
        
        // ✅ Debug: cek HTML yang dirender
        // dd($html); // Uncomment untuk test
        
        $pdf->writeHTML($html, true, false, true, false, '');

        // ✅ Nama file dinamis berdasarkan filter
        $fileName = 'Riwayat_Permintaan';
        if ($bulan && $tahun) {
            $fileName .= '' . \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') . '' . $tahun;
        } elseif ($tahun) {
            $fileName .= '_' . $tahun;
        }
        if ($status) {
            $fileName .= '_' . ucfirst($status);
        }
        $fileName .= '_' . date('Y-m-d_His') . '.pdf';

        // --- Output PDF (langsung download)
        $pdf->Output($fileName, 'D');
        exit;
    }
}
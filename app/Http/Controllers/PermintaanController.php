<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permintaan;
use App\Models\Guru;

class PermintaanController extends Controller
{
    /** 🔹 Halaman form ajukan permintaan (Guru) */
    public function create()
    {
        return view('dashboard.guru-permintaan');
    }

    /** 🔹 Simpan permintaan baru (Guru) */
    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:100',
            'merk_barang' => 'required|string|max:100',
            'tanggal'     => 'required|date',
            'jumlah'      => 'required|integer|min:1'
        ]);

        Permintaan::create([
            'id_guru'     => session('auth_id'),
            'nama_barang' => $request->nama_barang,
            'merk_barang' => $request->merk_barang,
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
    $permintaan = Permintaan::where('id_guru', session('auth_id'))
        ->orderBy('id_permintaan', 'desc') // ✅ urut dari terbaru
        ->get();

    return view('dashboard.guru-proses', compact('permintaan'));
}


    /** 🔹 Halaman Admin: lihat semua permintaan */
    public function adminIndex()
    {
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
            $permintaan->status = 'dikonfirmasi'; // ✅ sesuai enum DB
            $permintaan->save();
        }

        return redirect()->route('admin.permintaan.index')
            ->with('success', 'Permintaan berhasil dikonfirmasi.');
    }

    /** 🔹 Admin tolak permintaan */
    public function tolak($id)
    {
        $permintaan = Permintaan::where('id_permintaan', $id)->firstOrFail();

        if ($permintaan->status === 'pending') {
            $permintaan->status = 'ditolak'; // ✅ sesuai enum DB
            $permintaan->save();
        }

        return redirect()->route('admin.permintaan.index')
            ->with('success', 'Permintaan berhasil ditolak.');
    }
    public function cekNotif()
{
    $count = Permintaan::where('status', 'pending')->count();
    return response()->json(['pending' => $count]);
}

public function cekStatusGuru()
{
    $permintaan = Permintaan::where('id_guru', session('auth_id'))
        ->orderBy('id_permintaan','desc') // ✅ terbaru di atas
        ->get();

    return response()->json($permintaan);
}


// app/Http/Controllers/PermintaanController.php

public function fromBarang($id)
{
    $barang = \App\Models\Barang::findOrFail($id);
    return view('dashboard.guru-ajukan-dari-barang', compact('barang'));
}

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



}

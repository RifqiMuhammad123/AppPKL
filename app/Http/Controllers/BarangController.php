<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use TCPDF; // ✅ Tambahan buat TCPDF

class BarangController extends Controller
{
    public function downloadPdf()
    {
        $barang = \App\Models\Barang::all(); // ambil semua barang
        $pdf = Pdf::loadView('dashboard.barang.pdf', compact('barang'));
        return $pdf->download('daftar-barang.pdf');
    }

    // ✅ Method baru pakai TCPDF (bisa diakses lewat route berbeda)
    public function downloadPdfTcpdf()
    {
        $barang = \App\Models\Barang::all();

        // Inisialisasi TCPDF
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator('Laravel App');
        $pdf->SetAuthor('SMK KODING');
        $pdf->SetTitle('Daftar Barang');
        $pdf->SetMargins(15, 30, 15);
        $pdf->SetAutoPageBreak(true, 15);
        $pdf->AddPage();

        // Render view blade ke HTML
        $html = view('dashboard.barang.pdf', compact('barang'))->render();

        // Tulis HTML ke PDF
        $pdf->writeHTML($html, true, false, true, false, '');

        // Output PDF
        $pdf->Output('daftar-barang-tcpdf.pdf', 'D');
        exit;
    }

    public function index()
    {
        $barang = DB::table('barang')
            ->orderByDesc('tanggal_pembelian')
            ->get();
        return view('dashboard.barang.index', compact('barang'));
    }

    public function create()
    {
        return view('dashboard.barang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang'        => 'required|string|max:255',
            'merk_barang'        => 'required|string|max:255',
            'tanggal_pembelian'  => 'required|date',
            'harga_barang'       => 'required|numeric|min:0',
            'stok'               => 'required|integer|min:0',
            'foto'               => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048' // opsional
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            // simpan ke storage/app/public/barang
            $fotoPath = $request->file('foto')->store('barang', 'public');
        }

        DB::table('barang')->insert([
            'nama_barang'        => $request->nama_barang,
            'merk_barang'        => $request->merk_barang,
            'tanggal_pembelian'  => $request->tanggal_pembelian,
            'harga_barang'       => $request->harga_barang,
            'stok'               => $request->stok,
            'foto'               => $fotoPath,
        ]);

        return redirect()
            ->route('admin.barang.index')
            ->with('success', 'Barang berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $barang = DB::table('barang')->where('id_barang', $id)->first();
        if (!$barang) {
            return redirect()->route('barang.index')->with('error', 'Barang tidak ditemukan.');
        }
        return view('dashboard.barang.edit', compact('barang'));
    }

    public function update(Request $request, $id)
    {
    $request->validate([
        'nama_barang'        => 'required|string|max:255',
        'merk_barang'        => 'required|string|max:255',
        'tanggal_pembelian'  => 'required|date',
        'harga_barang'       => 'required|numeric|min:0',
        'stok'               => 'required|integer|min:0',
        'foto'               => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
    ]);

    // Ambil data lama
    $barang = DB::table('barang')->where('id_barang', $id)->first();

    $data = [
        'nama_barang'        => $request->nama_barang,
        'merk_barang'        => $request->merk_barang,
        'tanggal_pembelian'  => $request->tanggal_pembelian,
        'harga_barang'       => $request->harga_barang,
        'stok'               => $request->stok,
    ];

    if ($request->hasFile('foto')) {
        // Hapus foto lama kalau ada
        if ($barang->foto && \Storage::disk('public')->exists($barang->foto)) {
            \Storage::disk('public')->delete($barang->foto);
        }

        // Simpan foto baru
        $data['foto'] = $request->file('foto')->store('barang', 'public');
    }

    DB::table('barang')->where('id_barang', $id)->update($data);

    return redirect()
        ->route('admin.barang.index')
        ->with('success', 'Barang berhasil diperbarui!');
    }


    public function destroy($id)
    {
        DB::table('barang')->where('id_barang', $id)->delete();
        return redirect()
            ->route('admin.barang.index')
            ->with('success', 'Barang berhasil dihapus!');
    }

    public function guruIndex()
    {
        $barang = \App\Models\Barang::all();
        return view('dashboard.guru-barang', compact('barang'));
    }
}

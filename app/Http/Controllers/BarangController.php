<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;


class BarangController extends Controller
{
    public function downloadPdf()
    {
        $barang = \App\Models\Barang::all(); // ambil semua barang
        $pdf = Pdf::loadView('dashboard.barang.pdf', compact('barang'));


        return $pdf->download('daftar-barang.pdf');
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
            'asal_usul'          => 'required|string|max:255',
            'harga_barang'       => 'required|numeric|min:0',
            'stok'               => 'required|integer|min:0'
        ]);

        DB::table('barang')->insert([
            'nama_barang'        => $request->nama_barang,
            'merk_barang'        => $request->merk_barang,
            'tanggal_pembelian'  => $request->tanggal_pembelian,
            'asal_usul'          => $request->asal_usul,
            'harga_barang'       => $request->harga_barang,
            'stok'               => $request->stok,
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
            'asal_usul'          => 'required|string|max:255',
            'harga_barang'       => 'required|numeric|min:0',
            'stok'               => 'required|integer|min:0'
        ]);

        DB::table('barang')->where('id_barang', $id)->update([
            'nama_barang'        => $request->nama_barang,
            'merk_barang'        => $request->merk_barang,
            'tanggal_pembelian'  => $request->tanggal_pembelian,
            'asal_usul'          => $request->asal_usul,
            'harga_barang'       => $request->harga_barang,
            'stok'               => $request->stok,
        ]);

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



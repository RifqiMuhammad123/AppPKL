<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class GuruController extends Controller
{
    public function index()
    {
        $guru = DB::table('guru')->get();
        return view('dashboard.guru.index', compact('guru'));
    }

    public function create()
    {
        return view('dashboard.guru.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nip' => 'required|digits:18|unique:guru,nip',
            'nama_guru' => 'required|string|max:100',
            'password' => 'required|min:5',
        ]);

        DB::table('guru')->insert([
            'nip' => $request->nip,
            'nama_guru' => $request->nama_guru,
            'password' => Hash::make($request->password),
            'password_plain' => $request->password,
        ]);

        return redirect()->route('admin.guru.index')
            ->with('success', 'Data guru berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $guru = DB::table('guru')->where('id_guru', $id)->first();
        return view('dashboard.guru.edit', compact('guru'));
    }

    public function update(Request $request, $id)
{
        $request->validate([
        'nip' => 'required|unique:guru,nip,' . $id . ',id_guru',
        'nama_guru' => 'required',
    ]);

    $data = [
        'nip' => $request->nip,
        'nama_guru' => $request->nama_guru,
    ];

    if ($request->filled('password')) {
        $data['password'] = Hash::make($request->password);
        $data['password_plain'] = $request->password; // <-- tambahin ini
    }

    DB::table('guru')->where('id_guru', $id)->update($data);

    return redirect()->route('admin.guru.index')
        ->with('success', 'Data guru berhasil diperbarui!');
}

    public function destroy($id)
    {
        DB::table('guru')->where('id_guru', $id)->delete();

        return redirect()->route('admin.guru.index')
            ->with('success', 'Data guru berhasil dihapus!');
    }
}

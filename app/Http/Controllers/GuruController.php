<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Barang;

class GuruController extends Controller
{
    /** 
     * Halaman Dashboard Guru (tampil card + daftar barang + total stok)
     */
    public function home()
    {
        $allStok = Barang::sum('stok'); // total stok
        $barang  = Barang::all();       // daftar semua barang

        return view('dashboard.guru-home', compact('allStok', 'barang'));
    }

    /**
     * Halaman daftar guru (CRUD guru)
     */
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
            'foto' => 'icon.jpg', // Add default photo
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
            $data['password_plain'] = $request->password;
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
   
    /**
     * Update Profile Guru (dipanggil dari modal edit profile)
     * FIXED: Session tidak hilang setelah update + Debug mode
     */
   public function updateProfileGuru(Request $request)
{
    $guruId = session('auth_id');

    if (!$guruId) {
        return redirect()->route('login')->with('error', 'Session expired');
    }

    // Validasi
    $request->validate([
        'nama_guru' => 'required|string|max:255',
        'foto' => 'nullable|image|max:2048',
        'password' => 'nullable|min:6',
    ]);

    // Build update data
    $updateData = ['nama_guru' => $request->nama_guru];

    // Handle foto
    if ($request->hasFile('foto')) {
        $oldUser = DB::table('guru')->where('id_guru', $guruId)->first();
        
        if ($oldUser && $oldUser->foto && $oldUser->foto !== 'icon.jpg') {
            @unlink(public_path('img/' . $oldUser->foto));
        }
        
        $foto = $request->file('foto');
        $namaFoto = time() . '_' . $foto->getClientOriginalName();
        $foto->move(public_path('img'), $namaFoto);
        $updateData['foto'] = $namaFoto;
    }

    // Handle password
    if ($request->filled('password')) {
        $updateData['password'] = Hash::make($request->password);
        $updateData['password_plain'] = $request->password;
    }

    // UPDATE DATABASE
    DB::table('guru')->where('id_guru', $guruId)->update($updateData);

    // Get fresh data
    $user = DB::table('guru')->where('id_guru', $guruId)->first();

    // Update session
    session(['auth_name' => $user->nama_guru]);
    session(['auth_photo' => $user->foto ?? 'icon.jpg']);
    if (isset($updateData['password_plain'])) {
        session(['auth_password_plain' => $user->password_plain]);
    }

    return redirect()->route('guru.home')->with('success', 'Profil berhasil diperbarui!');
}
}
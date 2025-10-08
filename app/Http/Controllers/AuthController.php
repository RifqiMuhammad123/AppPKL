<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use App\Models\Guru;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $r)
    {
        $r->validate([
            'role'     => 'required|in:admin,guru',
            'nip'      => 'required|string|max:20',
            'nama'     => 'required|string|max:100',
            'password' => 'required|string|min:6',
        ]);

        if ($r->role === 'admin') {
            if (Admin::where('nip', $r->nip)->exists()) {
                return back()->withErrors(['nip' => 'NIP sudah digunakan di tabel admin.'])->withInput();
            }
            Admin::create([
                'nip'        => $r->nip,
                'nama_admin' => $r->nama,
                'password'   => Hash::make($r->password),
                'foto'       => 'https://i.pravatar.cc/150?img=12'
            ]);
        } else {
            if (Guru::where('nip', $r->nip)->exists()) {
                return back()->withErrors(['nip' => 'NIP sudah digunakan di tabel guru.'])->withInput();
            }
            Guru::create([
                'nip'       => $r->nip,
                'nama_guru' => $r->nama,
                'password'  => Hash::make($r->password),
                'foto'       => 'https://i.pravatar.cc/150?img=12'
            ]);
        }

        return redirect()->route('login')
            ->with('success', 'Registrasi berhasil. Silakan login.');
    }

    public function login(Request $r)
    {
        $r->validate([
            'role'     => 'required|in:admin,guru',
            'nama'     => 'required|string',
            'password' => 'required|string',
        ]);

        if ($r->role === 'admin') {
            $user = Admin::where('nama_admin', $r->nama)->first();
            if (!$user || !Hash::check($r->password, $user->password)) {
                return back()->withErrors(['nama' => 'Nama/Password salah untuk Admin.'])->withInput();
            }

            // regenerate biar session ID baru
            $r->session()->regenerate();

            session([
                'auth_role'  => 'admin',
                'auth_id'    => $user->id_admin,
                'auth_name'  => $user->nama_admin,
                'auth_photo' => $user->foto ?: 'https://i.pravatar.cc/150?img=12',
                'auth_password_plain' => $user->password_plain,
            ]);

            return redirect()->route('admin.dashboard');
        }

        // Guru
        $user = Guru::where('nama_guru', $r->nama)->first();
        if (!$user || !Hash::check($r->password, $user->password)) {
            return back()->withErrors(['nama' => 'Nama/Password salah untuk Guru.'])->withInput();
        }

        $r->session()->regenerate();

        session([
            'auth_role' => 'guru',
            'auth_id'   => $user->id_guru,
            'auth_name' => $user->nama_guru,
            'auth_photo' => $user->foto ?: 'https://i.pravatar.cc/150?img=12',
            'auth_password_plain' => $user->password_plain,
        ]);

        return redirect()->route('guru.home');
    }

    public function logout(Request $r)
    {
        $r->session()->invalidate();
        $r->session()->regenerateToken();
        return redirect()->route('login');
    }
}

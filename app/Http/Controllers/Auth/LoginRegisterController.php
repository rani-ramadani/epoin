<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Siswa;

class LoginRegisterController extends Controller
{
    public function index()
    {
        // Get data dari database
        $users = User::latest()->paginate(10);

        return view('admin.akun.index', compact('users'));
    }

    public function create()
    {
        return view('admin.akun.create');
    }

    public function register()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:250',
            'email' => 'required|email|max:250|unique:users',
            'password' => 'required|min:8|confirmed',
            'usertype' => 'required'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'usertype' => 'admin'
        ]);

        return redirect()->route('akun.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    public function login()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            if ($request->user()->usertype == 'admin') {
                return redirect('admin/dashboard')->withSuccess('You have successfully logged in!');
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->withSuccess('You have logged out successfully!');
    }
    public function edit($id) {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }
    
    public function update(Request $request, $id)
    {
        // Validasi form
        $request->validate([
            'name' => 'required|string|max:250',
            'usertype' => 'required'
        ]);

        // Ambil data user berdasarkan ID
        $user = User::findOrFail($id);

        // Update data akun
        $user->update([
            'name' => $request->name,
            'usertype' => $request->usertype
        ]);

        // Redirect ke halaman edit dengan pesan sukses
        return redirect()->route('akun.edit', $id)->with(['success' => 'Data Berhasil Diubah!']);
    }

    public function updateEmail(Request $request, $id)
    {
        // Validasi form
        $request->validate([
            'email' => 'required|email|max:250|unique:users,email,' . $id
        ]);

        // Ambil data user berdasarkan ID
        $user = User::findOrFail($id);

        // Update email akun
        $user->update([
            'email' => $request->email
        ]);

        // Redirect ke halaman edit dengan pesan sukses
        return redirect()->route('akun.edit', $id)->with(['success' => 'Email Berhasil Diubah!']);
    }

    public function updatePassword(Request $request, $id)
    {
        // Validasi form
        $request->validate([
            'password' => 'required|min:8|confirmed'
        ]);

        // Ambil data user berdasarkan ID
        $user = User::findOrFail($id);

        // Update password akun
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        // Redirect ke halaman edit dengan pesan sukses
        return redirect()->route('akun.edit', $id)->with(['success' => 'Password Berhasil Diubah!']);
    }

    public function destroy($id)
    {
        // Cari ID siswa terkait
        $siswa = DB::table('siswas')->where('id_user', $id)->value('id');

        // Jika siswa ada, hapus data siswa
        if ($siswa) {
            $this->destroySiswa($siswa);
        }

        // Ambil data user berdasarkan ID
        $user = User::findOrFail($id);

        // Hapus data user
        $user->delete();

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('akun.index')->with(['success' => 'Akun Berhasil Dihapus!']);
    }

    public function destroySiswa($id)
    {
        // Ambil data siswa berdasarkan ID
        $siswa = Siswa::findOrFail($id);

        // Hapus gambar siswa
        Storage::delete('public/siswas/' . $siswa->image);

        // Hapus data siswa
        $siswa->delete();
    }
}
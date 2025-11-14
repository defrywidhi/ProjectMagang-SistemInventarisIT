<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Auth;




class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ambil semua role yang ada di database
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'exists:roles,name'], // Pastikan role-nya ada di tabel roles
        ]);

        // 2. Buat User baru
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Enkripsi/Hash password
        ]);

        // 3. "Kasih" dia "gantungan kunci" (Role)
        $user->assignRole($request->role);

        // 4. Redirect
        return redirect()->route('users.index')->with('success', 'User baru berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        // Ambil semua role
        $roles = Role::all();
        // Kirim user yang mau diedit dan daftar roles ke view
        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // 1. Validasi
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            // Validasi email unik, tapi abaikan email user saat ini
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            // Password HANYA divalidasi JIKA diisi (nullable)
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'exists:roles,name'],
        ]);

        // 2. Siapkan data update
        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        // 3. Cek jika password diisi, baru update password
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // 4. Update data user
        $user->update($data);

        // 5. "Sihir" Spatie: Sinkronkan rolenya
        $user->syncRoles($request->role);

        return redirect()->route('users.index')->with('success', 'User berhasil di-update.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // JURUS PENGAMAN: Jangan biarkan user menghapus dirinya sendiri!
        if (Auth::id() == $user->id) {
            return redirect()->route('users.index')->with('error', 'Anda tidak bisa menghapus akun Anda sendiri!');
        }

        // JURUS PENGAMAN 2: Jangan biarkan admin terakhir dihapus
        if ($user->hasRole('admin') && User::role('admin')->count() <= 1) {
            return redirect()->route('users.index')->with('error', 'Tidak bisa menghapus admin terakhir!');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}

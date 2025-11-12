<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Tampilkan profil admin yang sedang login menggunakan view detail user.
     */
    public function profile()
    {
        $user = Auth::user();
        return view('pages.admin.users.show', compact('user'));
    }

    public function index()
    {
        $users = User::orderBy('name')->paginate(15);
        return view('pages.admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = ['admin','mentor','member'];
        return view('pages.admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:120'],
            'email' => ['required','email','max:150','unique:users,email'],
            'password' => ['required','string','min:6'],
            'role' => ['required','in:admin,mentor,member'],
            'avatar' => ['nullable','image','max:300','dimensions:max_width=200,max_height=200'],
            'bio' => ['nullable','string'],
        ]);

        // Handle avatar upload if provided
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $data['avatar_url'] = Storage::url($path);
        }
        unset($data['avatar']);

        User::create($data);
        return redirect()->route('admin.users.index')->with('success', 'User berhasil dibuat');
    }

    public function show(User $user)
    {
        return view('pages.admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = ['admin','mentor','member'];
        return view('pages.admin.users.edit', compact('user','roles'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => ['required','string','max:120'],
            'email' => ['required','email','max:150','unique:users,email,'.$user->id],
            'password' => ['nullable','string','min:6'],
            'role' => ['required','in:admin,mentor,member'],
            'avatar' => ['nullable','image','max:300','dimensions:max_width=200,max_height=200'],
            'bio' => ['nullable','string'],
        ]);

        // Hanya update password jika diisi
        if (empty($data['password'])) {
            unset($data['password']);
        }

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $data['avatar_url'] = Storage::url($path);
        }
        unset($data['avatar']);

        $user->update($data);
        return redirect()->route('admin.users.index')->with('success', 'User diperbarui');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User dihapus');
    }
}
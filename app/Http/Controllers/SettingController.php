<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SettingController extends Controller
{
    /* ───────────────── INDEX  ───────────────── */
    public function index()
    {
        $user = Auth::user();          // ✅ sudah benar
        return view('pages.setting.index', compact('user'));
    }

    /* ───────────────── EDIT FORM ─────────────── */
    public function edit()
    {
        $user = Auth::user();          // ✅ singular, bukan users()
        return view('pages.setting.edit', compact('user'));
    }

    /* ───────────────── UPDATE DATA ───────────── */
    public function update(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $user = Auth::user();          // ✅ instance Eloquent

        $data = [
            'name'  => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);          // ✅ kini berfungsi
        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    /* ────── METODE LAIN (create, store, show, destroy) ────── */
}
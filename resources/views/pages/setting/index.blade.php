@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
    <div class="w-full mx-auto  p-6 bg-white rounded-xl shadow-md">
        <h3 class="text-2xl font-semibold text-gray-800 mb-6">Edit Profil Pengguna</h3>

        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg border border-green-300">
                {{ session('success') }}
            </div>
        @endif
        <div class=" flex flex-col">
            <form method="POST" action="{{ route('user.update') }}">
                @csrf
                <div>

                    <div class="mb-4">
                        <label for="name" class="block text-gray-700 font-medium mb-1">Nama Lengkap</label>
                        <input type="text" name="name" id="name"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring focus:ring-blue-200"
                            value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <small class="text-red-600">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 font-medium mb-1">Alamat Email</label>
                        <input type="email" name="email" id="email"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring focus:ring-blue-200"
                            value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <small class="text-red-600">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div>

                    <div class="mb-4">
                        <label for="password" class="block text-gray-700 font-medium mb-1">Kata Sandi Baru
                            (opsional)</label>
                        <input type="password" name="password" id="password"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring focus:ring-blue-200">
                        @error('password')
                            <small class="text-red-600">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="password_confirmation" class="block text-gray-700 font-medium mb-1">Konfirmasi Kata
                            Sandi</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring focus:ring-blue-200">
                    </div>
                </div>


                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200">
                    Update Profil
                </button>
            </form>
        </div>


    </div>
@endsection

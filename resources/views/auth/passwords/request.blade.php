@extends('layouts.app') {{-- atau ganti dengan layout kamu --}}

@section('content')
<div class="max-w-md mx-auto mt-20 bg-white shadow-lg rounded-lg p-6">
  <h2 class="text-xl font-bold mb-4 text-center">Reset Password</h2>

  @if (session('status'))
      <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
          {{ session('status') }}
      </div>
  @endif

  <form method="POST" action="">
      @csrf

      <div class="mb-4">
          <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
          <input type="email" id="email" name="email" required autofocus
                 class="mt-1 w-full px-3 py-2 border border-gray-300 rounded shadow-sm focus:ring focus:ring-blue-300">
          @error('email')
              <span class="text-sm text-red-600">{{ $message }}</span>
          @enderror
      </div>

      <button type="submit" class="w-full bg-blue-700 hover:bg-blue-800 text-white font-semibold py-2 px-4 rounded">
          Kirim Link Reset Password
      </button>
  </form>
</div>
@endsection

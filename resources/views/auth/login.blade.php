<!doctype html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    @vite('resources/css/app.css')
  </head>
  <body class="font-poppins bg-gray-100">

    <main class="w-full md:max-w-3xl mx-auto mt-20 p-4 bg-white shadow-lg rounded-xl">
      <h1 class="text-3xl font-bold text-gray-800 mb-4 text-center uppercase">Portal sistem rekam medis elektronik Bidan Praktik Mandiri</h1>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 my-10">
        
        <div>
          <img src="{{ asset('images/logo.png') }}" alt="Login" class="w-full h-auto">
        </div>
        
        <div class="flex flex-col justify-center">
          <h2 class="text-2xl font-bold text-gray-800 mb-4">Login</h2>

          {{-- Login Form --}}
          <form method="POST" action="{{ route('login.post') }}" novalidate class="space-y-4">
            @csrf

            <div>
              <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
              <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                     class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
              @error('email')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
              @enderror
            </div>

            <div class="mb-5">
              <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
              <input type="password" name="password" id="password" required
                     class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
              @error('password')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
              @enderror
            </div>

            <div>
              <button type="submit"
                      class="w-full py-2 px-4 bg-blue-700 hover:bg-blue-800 text-white font-semibold rounded-md">
                Masuk
              </button>
            </div>
          </form>
        </div>

      </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
  </body>
</html>

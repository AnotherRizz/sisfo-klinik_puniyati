<button data-drawer-target="sidebar-multi-level-sidebar" data-drawer-toggle="sidebar-multi-level-sidebar"
    aria-controls="sidebar-multi-level-sidebar" type="button"
    class="inline-flex items-center p-2 mt-2 ms-3 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
    <span class="sr-only">Open sidebar</span>
    <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
        <path clip-rule="evenodd" fill-rule="evenodd"
            d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
        </path>
    </svg>
</button>

<aside id="sidebar-multi-level-sidebar"
    class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0"
    aria-label="Sidebar">
    <div class="h-full px-3 py-4 overflow-y-auto bg-gray-800">
        <ul class="space-y-2 font-medium mt-14">
            <li>
                <a href="{{ route('dashboard.index') }}"
                    class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg  {{ request()->routeIs('dashboard.*') ? 'bg-gray-700 text-white' : 'text-white hover:bg-gray-700' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>

                    <span class="ms-3 text-sm">Dashboard</span>
                </a>
            </li>
            <li>
                <button type="button"
                    class="flex items-center w-full p-2 text-base transition duration-75 rounded-lg group cursor-pointer  text-white hover:bg-gray-700"
                    aria-controls="dropdown-example" data-collapse-toggle="dropdown-example">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                    </svg>

                    <span class="flex-1 ms-3 text-sm text-left rtl:text-right whitespace-nowrap">Master Data</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 4 4 4-4" />
                    </svg>
                </button>
                @php
                    $activeMaster =
                        request()->routeIs('pasien.*') ||
                        request()->routeIs('bidan.*') ||
                        request()->routeIs('pelayanan.*') ||
                        request()->routeIs('obat.*');
                @endphp

                <ul id="dropdown-example" class="{{ $activeMaster ? '' : 'hidden' }} py-2 space-y-2">
                    <li>
                        <a href="{{ route('bidan.index') }}"
                            class="flex items-center w-full p-2 text-sm text-gray-900 transition duration-75 rounded-lg pl-11 group {{ request()->routeIs('bidan.*') ? 'bg-gray-700 text-white' : 'text-white hover:bg-gray-700' }}">Data
                            Bidan</a>
                    </li>
                    <li>
                        <a href="{{ route('pasien.index') }}"
                            class="flex items-center w-full p-2 pl-11 text-sm rounded-lg transition duration-75 group
          {{ request()->routeIs('pasien.*') ? 'bg-gray-700 text-white' : 'text-white hover:bg-gray-700' }}">
                            Data Pasien
                        </a>

                    </li>
                    <li>
                        <a href="{{ route('pelayanan.index') }}"
                            class="flex items-center w-full p-2 text-sm text-gray-900 transition duration-75 rounded-lg pl-11 group {{ request()->routeIs('pelayanan.*') ? 'bg-gray-700 text-white' : 'text-white hover:bg-gray-700' }}">Data
                            Pelayanan</a>
                    </li>
                    <li>
                        <a href="{{ route('obat.index') }}"
                            class="flex items-center w-full p-2 text-sm text-gray-900 transition duration-75 rounded-lg pl-11 group {{ request()->routeIs('obat.*') ? 'bg-gray-700 text-white' : 'text-white hover:bg-gray-700' }}">Data
                            Obat</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="{{ route('pendaftaran.index') }}"
                    class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg  {{ request()->routeIs('pendaftaran.*') ? 'bg-gray-700 text-white' : 'text-white hover:bg-gray-700' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                    </svg>


                    <span class="flex-1 ms-3 text-sm whitespace-nowrap">Pendaftaran Pasien</span>

                </a>
            </li>
            <li>
                <a href="{{ route('pemeriksaan.index') }}"
                    class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg  {{ request()->routeIs('pemeriksaan.*') ? 'bg-gray-700 text-white' : 'text-white hover:bg-gray-700' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                    </svg>

                    <span class="flex-1 ms-3 text-sm whitespace-nowrap">Pemeriksaan</span>
              
                </a>
            </li>
            <li>
                <a href="{{ route('pembayaran.index') }}"
                     class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg  {{ request()->routeIs('pembayaran.*') ? 'bg-gray-700 text-white' : 'text-white hover:bg-gray-700' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
                    </svg>

                    <span class="flex-1 ms-3 text-sm whitespace-nowrap">Pembayaran</span>
                </a>
            </li>
            <li>
                <a href="{{ route('laporan.index') }}"
                 class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg  {{ request()->routeIs('laporan.*') ? 'bg-gray-700 text-white' : 'text-white hover:bg-gray-700' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25M9 16.5v.75m3-3v3M15 12v5.25m-4.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                    </svg>

                    <span class="flex-1 ms-3 text-sm whitespace-nowrap">Laporan</span>
                </a>
            </li>
            <span class="block w-full border-t border-gray-600 my-3"></span>
            <li>
                <a href="{{ route('setting.index') }}"
                class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg  {{ request()->routeIs('setting.*') ? 'bg-gray-700 text-white' : 'text-white hover:bg-gray-700' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>

                    <span class="flex-1 ms-3 text-sm whitespace-nowrap">Setting</span>
                </a>
            </li>
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full text-left flex items-center p-2 cursor-pointer rounded-lg text-white hover:bg-gray-700 group">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15" />
                        </svg>
                        <span class="flex-1 ms-3 text-sm whitespace-nowrap">Log Out</span>
                    </button>
                </form>
            </li>


        </ul>
    </div>
</aside>


<nav
    class="fixed top-0 left-0 right-0 z-50 bg-white border-b border-gray-200 px-4 py-3 shadow-md flex justify-between items-center">
    {{-- Tombol Toggle Sidebar (Mobile) --}}
    <button data-drawer-target="sidebar-multi-level-sidebar" data-drawer-toggle="sidebar-multi-level-sidebar"
        aria-controls="sidebar-multi-level-sidebar" type="button"
        class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
        <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
            <path clip-rule="evenodd" fill-rule="evenodd"
                d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
            </path>
        </svg>
        <span class="sr-only">Toggle sidebar</span>
    </button>

    {{-- Logo atau Judul --}}
    <div class="flex items-center">
        <span class="text-lg font-bold text-blue-800">BPM Puniyati, A.Md Keb</span>
        <img src="{{ asset('images/logo.png') }}" class="w-12" alt="">
    </div>

    {{-- Foto Profil --}}
    <div class="flex items-center space-x-4">
        <span class="hidden sm:block text-sm text-gray-700">Halo, {{ Auth::user()->name ?? 'Admin' }}</span>
        <img class="w-8 h-8 rounded-full object-cover border border-gray-300" src="{{ asset('images/doktor.png') }}"
            alt="Foto Profil">
    </div>
</nav>

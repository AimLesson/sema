<nav class="border-gray-200 bg-gradient-to-r from-cyan-300 to-zinc-200">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-2">
        <a href="#" class="flex items-center space-x-3 rtl:space-x-reverse">
            <img src="{{ asset('storage/' . $profile->logo) }}" class="h-21" alt="Logo Organisasi" />
            <div class="text-left">
                <span class="block text-lg md:text-2xl font-bold text-black">{{ $profile->nick_name }}</span>
                <div class="w70 h-0.5 my-1 bg-black"></div> <!-- Underline -->
                <span class="block text-sm md:text-xl font-semibold text-black">{{ $profile->full_name }}</span>
            </div>
        </a>
        <button data-collapse-toggle="navbar-solid-bg" type="button"
            class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200"
            aria-controls="navbar-solid-bg" aria-expanded="false">
            <span class="sr-only">Open main menu</span>
            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 17 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M1 1h15M1 7h15M1 13h15" />
            </svg>
        </button>
        <div class="hidden w-full md:block md:w-auto" id="navbar-solid-bg">
            <ul class="flex flex-col font-medium mt-4 rounded-lg bg-gray-50 md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-transparent p-2">
                <li>
                    <a href="#"
                        class="block border-b py-2 px-3 md:p-0 text-gray-900 rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700">Beranda</a>
                </li>
                <li>
                    <a href="#"
                        class="block border-b py-2 px-3 md:p-0 text-gray-900 rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700">Profil</a>
                </li>
                <li>
                    <a href="#"
                        class="block border-b py-2 px-3 md:p-0 text-gray-900 rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700">Aspirasi</a>
                </li>
                <li>
                    <a href="#"
                        class="block border-b py-2 px-3 md:p-0 text-gray-900 rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700">SUMMIT</a>
                </li>
                <li>
                    <a href="#"
                        class="block border-b py-2 px-3 md:p-0 text-gray-900 rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700">Kontak</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

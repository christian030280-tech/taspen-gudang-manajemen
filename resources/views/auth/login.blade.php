<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="text-center mb-8">
        <div class="flex justify-center mb-4">
            <div class="w-16 h-16 bg-[#1557A6] rounded-2xl flex items-center justify-center shadow-lg transform -rotate-3 transition hover:rotate-0 duration-300">
                <x-lucide-box class="w-8 h-8 text-white" />
            </div>
        </div>
        <h1 class="text-3xl font-extrabold text-[#1557A6] mb-1 tracking-tight">TASPEN</h1>
        <h2 class="text-lg font-semibold text-gray-600">Sistem Informasi Pengelolaan Gudang</h2>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">Email Address</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <x-lucide-mail class="h-5 w-5 text-gray-400" />
                </div>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="pl-10 block w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6] focus:ring-opacity-20 transition-shadow sm:text-sm" placeholder="Masukkan email anda">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">Password</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <x-lucide-lock class="h-5 w-5 text-gray-400" />
                </div>
                <input id="password" type="password" name="password" required autocomplete="current-password" class="pl-10 block w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6] focus:ring-opacity-20 transition-shadow sm:text-sm" placeholder="••••••••">
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between pt-1">
            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-[#1557A6] shadow-sm focus:ring-[#1557A6] focus:ring-opacity-50 transition-colors" name="remember">
                <span class="ms-2 text-sm text-gray-600 font-medium group-hover:text-gray-900 transition-colors">Ingat Saya</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm font-medium text-[#1557A6] hover:text-blue-800 hover:underline transition-colors" href="{{ route('password.request') }}">
                    Lupa Password?
                </a>
            @endif
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-md text-sm font-bold text-white bg-[#1557A6] hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#1557A6] transition-all transform hover:-translate-y-0.5 active:translate-y-0">
                MASUK KE SISTEM
            </button>
        </div>
        
        <div class="mt-8 text-center border-t border-gray-100 pt-6">
            <p class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-4">Internal Use Only</p>
            
            <!-- Default Credentials Hint -->
            <div class="inline-block text-left bg-blue-50/50 rounded-xl p-4 border border-blue-100/50 text-xs text-gray-600 shadow-sm w-full">
                <p class="font-bold text-[#1557A6] mb-2 flex items-center justify-center">
                    <x-lucide-users class="w-4 h-4 mr-1.5" /> Akun Demo
                </p>
                <ul class="space-y-2">
                    <li class="flex justify-between items-center bg-white p-2 rounded border border-gray-100 shadow-sm">
                        <span class="font-semibold text-gray-800">admin@taspen.co.id</span>
                        <span class="text-gray-400">|</span>
                        <span class="font-mono text-gray-500">password</span>
                    </li>
                    <li class="flex justify-between items-center bg-white p-2 rounded border border-gray-100 shadow-sm">
                        <span class="font-semibold text-gray-800">ruri@taspen.local</span>
                        <span class="text-gray-400">|</span>
                        <span class="font-mono text-gray-500">password</span>
                    </li>
                </ul>
            </div>
        </div>
    </form>
</x-guest-layout>

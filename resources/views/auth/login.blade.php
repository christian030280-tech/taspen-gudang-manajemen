<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-taspen-blue mb-2">TASPEN</h1>
        <h2 class="text-xl font-semibold text-gray-700">Sistem Informasi<br>Pengelolaan Gudang</h2>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" class="block mt-1 w-full border-gray-300 focus:border-taspen-blue focus:ring-taspen-blue rounded-md shadow-sm" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" value="Password" />

            <x-text-input id="password" class="block mt-1 w-full border-gray-300 focus:border-taspen-blue focus:ring-taspen-blue rounded-md shadow-sm"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4 flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-taspen-blue shadow-sm focus:ring-taspen-blue" name="remember">
                <span class="ms-2 text-sm text-gray-600">Ingat Saya</span>
            </label>
        </div>

        <div class="mt-6">
            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-taspen-blue border border-transparent rounded-md font-semibold text-white uppercase tracking-widest hover:bg-taspen-dark-blue focus:bg-taspen-dark-blue active:bg-taspen-dark-blue focus:outline-none focus:ring-2 focus:ring-taspen-blue focus:ring-offset-2 transition ease-in-out duration-150">
                MASUK
            </button>
        </div>
        
        <div class="mt-6 text-center text-sm text-gray-500">
            Internal Use Only
        </div>
    </form>
</x-guest-layout>

<x-guest-layout>
    <div class="">
        <div class="w-full">

            <!-- Heading -->
            <div class="text-left">
                <h2 class="text-[10px] md:text-sm font-bold">
                    <span class="text-custom-blue-100">App</span><span class="text-custom-red font-extrabold">Hub</span>
                </h2>
                <h2 class="text-2xl md:text-3xl mt-2 font-extrabold text-gray-900">
                    {{ __("Login") }}
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    {{ __("Welcome to the AppHub admin portal") }}
                </p>
            </div>

            @if (config('app.demo'))
                <div class="mb-4 rounded-md border border-teal-200 bg-teal-50 px-3 py-2 text-sm text-teal-900">
                    <b>Akun demo</b><br>
                    admin@example.com &nbsp;/&nbsp; Admin#12345
                </div>
            @endif

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <!-- Form -->
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" class="text-gray-700 font-semibold" />
                <x-text-input id="email"
                    class="block mt-1 w-full rounded-md border border-gray-300 bg-white text-gray-900 focus:border-blue-500 focus:ring-blue-500"
                    type="email"
                    name="email"
                    :value="old('email')"
                    required
                    autofocus
                    autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 text-sm" />
            </div>

            <!-- Password -->
            <div>
                <x-input-label for="password" :value="__('Password')" class="text-gray-700 font-semibold" />
                <div class="relative">
                    <x-text-input id="password"
                        class="block mt-1 w-full rounded-md border border-gray-300 bg-white text-gray-900 focus:border-blue-500 focus:ring-blue-500"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        data-lpignore="true" />
                    <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 text-sm" />
            </div>

            <!-- Remember Me + Forgot Password -->
            <div class="flex items-center justify-between">
                <label for="remember_me" class="flex items-center">
                    <input id="remember_me" type="checkbox"
                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500"
                        name="remember">
                    <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>

                <!-- @if (Route::has('password.request'))
                    <a class="text-sm text-blue-600 hover:text-blue-800 transition"
                        href="{{ route('password.request') }}">
                        {{ __('Forgot password?') }}
                    </a>
                @endif -->
            </div>

            <!-- Submit Button -->
            <div>
                <x-primary-button class="w-full justify-center py-3 text-lg rounded-md bg-[#102d5d] text-white hover:bg-[#1b3a6f]">
                    {{ __('LOGIN') }}
                </x-primary-button>
            </div>
            {{-- Login SSO dinonaktifkan. Aktifkan kembali bersama route login.azure.
            <div class="border-t border-gray-200 my-4"></div>
            @if (config('services.azure.client_id'))
            <div>
                <x-primary-button type="button" class="w-full justify-center py-3 text-lg rounded-md bg-[#102d5d] text-white hover:bg-[#1b3a6f]" id="azureLoginBtn">
                    {{ __('LOGIN SSO') }}
                </x-primary-button>
            </div>
            @endif
            --}}
        </form>

            <!-- Divider
            <div class="border-t border-gray-200 my-4"></div> -->

            <!-- Register Link -->
            <!-- <div class="text-center">
                @if (Route::has('register'))
                    <p class="text-sm text-gray-600">
                        {{ __("Don't have an account?") }}
                        <a href="{{ route('register') }}" class="font-semibold text-blue-600 hover:text-blue-800 transition">
                            {{ __('Register') }}
                        </a>
                    </p>
                @endif
            </div> -->
        </div>
    </div>
    <script>
    // Tombol SSO dinonaktifkan (lihat komentar di atas)
    document.addEventListener('DOMContentLoaded', function () {
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        togglePassword.addEventListener('click', function () {
            // Toggle tipe input antara "password" dan "text"
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Ubah ikon sesuai status visibility
            if (type === 'text') {
                this.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                </svg>`;
            } else {
                this.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>`;
            }
        });
    });
</script>
</x-guest-layout>

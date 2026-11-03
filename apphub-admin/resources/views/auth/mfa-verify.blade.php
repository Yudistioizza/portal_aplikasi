<x-guest-layout>
    <div class="w-full max-w-md mx-auto mt-8">
        <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900 dark:text-white mb-4">
            {{ __("Verifikasi OTP MFA") }}
        </h2>
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
            {{ __("Masukkan kode OTP dari Google/Microsoft Authenticator Anda.") }}
        </p>

        <!-- Session Error (OTP) -->
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                {{ session('error') }}
            </div>
        @endif

        <!-- Form OTP Verification -->
        <form method="POST" action="{{ route('mfa.verify.post') }}" class="space-y-6">
            @csrf

            <!-- OTP Input -->
            <div>
                <x-input-label for="otp" :value="__('Kode OTP')" class="text-gray-700 dark:text-gray-300 font-semibold" />
                <x-text-input id="otp"
                    class="block mt-1 w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-blue-500"
                    type="text"
                    name="otp"
                    placeholder="Masukkan 6 digit kode OTP"
                    required
                    autofocus />
                <x-input-error :messages="$errors->get('otp')" class="mt-2 text-red-500 text-sm" />
            </div>

            <!-- Submit -->
            <div>
                <x-primary-button class="w-full justify-center py-3 text-lg rounded-lg">
                    {{ __('Verifikasi') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>

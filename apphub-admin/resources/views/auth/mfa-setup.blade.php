<x-guest-layout>
    <div class="w-full max-w-md mx-auto mt-8">
        <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900 dark:text-white mb-4">
            {{ __("Setup MFA (Authenticator)") }}
        </h2>
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
            {{ __("Scan QR Code ini menggunakan Google/Microsoft Authenticator atau input manual kodenya.") }}
        </p>

        <!-- QR Code -->
        <div class="flex justify-center mb-4">
            {!! $QR_Image !!}
        </div>

        <!-- Secret Key -->
        <div class="text-center mb-6">
            <p class="text-gray-700 dark:text-gray-300">{{ __("Kode Manual:") }}</p>
            <p class="font-semibold text-lg text-gray-900 dark:text-white">{{ $user->mfa_secret }}</p>
        </div>

        <!-- Button to verify -->
        <div>
            <a href="{{ route('mfa.verify') }}" class="w-full block text-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg">
                {{ __("Lanjutkan ke Verifikasi OTP") }}
            </a>
        </div>
    </div>
</x-guest-layout>

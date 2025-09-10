<x-module-release-2::auth-layout :title="__('Verify Your Email Address | Module Release 2')">

    <div class="rounded-xl pb-8 pt-6 px-8 shadow-2xl bg-white dark:bg-slate-900 animate-scale-in max-w-xl">
        <div>
            <h1 class="text-xl font-bold text-slate-600 dark:text-white">
                @lang('Verify Your Email Address')
            </h1>

            <p class="text-left mb-6 mt-2 text-gray-600 dark:text-gray-400 text-sm leading-relaxed">
                @lang("Terima kasih telah mendaftar! Sebelum mulai menggunakan akun, silakan verifikasi alamat email Anda dengan mengklik tautan yang baru saja kami kirimkan ke email. Jika Anda tidak menerima email tersebut, klik tombol di bawah untuk mengirim ulang tautan verifikasi.")
            </p>

            {{-- Alert Success --}}
            @if (session('status') == 'verification-link-sent')
                <div class="mb-4 rounded-lg bg-green-100 text-green-800 px-4 py-3 text-sm text-center">
                    @lang("Tautan verifikasi baru telah dikirim ke alamat email yang Anda daftarkan.")
                </div>
            @endif

            <form method="POST" action="{{ route(module_release_2_meta('kebab').'.verification.send') }}" class="mt-8">
                @csrf
                <x-link 
                    tag="button" 
                    type="submit" 
                    intent="primary" 
                    class="w-full" 
                    size="lg"
                >
                    <x-slot:iconBefore>
                        <span class="icon-[mdi--email-send] text-lg"></span>
                    </x-slot:iconBefore>
                    @lang('Resend Verification Email')
                </x-link>
            </form>
        </div>
    </div>

</x-module-release-2::auth-layout>

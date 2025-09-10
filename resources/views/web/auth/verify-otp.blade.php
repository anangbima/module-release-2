<x-desa-module-template::auth-layout :title="__('Verify OTP | Desa Module Template')">

    <div class="rounded-xl pb-8 pt-6 px-6 sm:px-8 shadow-2xl bg-white dark:bg-slate-900 animate-scale-in max-w-xl w-full mx-auto">
        <div>
            <h1 class="text-xl font-semibold text-slate-600 dark:text-gray-400">
                @lang('Verify OTP')
            </h1>

            <p class="text-sm mb-6 mt-4 text-gray-600 dark:text-gray-500">
                @lang('Masukkan kode OTP yang telah kami kirimkan ke alamat email Anda untuk melanjutkan proses login.')
            </p>

            {{-- Success Message --}}
            @if (session('status'))
                <div class="mb-4 p-3 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-900 dark:text-green-300 animate-fade-in text-center">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Error --}}
            @error('otp')
                <div class="mb-4 p-3 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-900 dark:text-red-300 animate-fade-in text-center">
                    {{ $message }}
                </div>
            @enderror

            {{-- OTP Form --}}
            <form id="otp-form" action="{{ route(desa_module_template_meta('kebab').'.verify-otp') }}" method="POST" class="space-y-4 mt-6">
                @csrf

                {{-- OTP Input Boxes --}}
                <div class="grid grid-cols-6 gap-2 sm:gap-3 justify-center">
                    @for ($i = 0; $i < 6; $i++)
                        <input 
                            type="text" 
                            maxlength="1" 
                            class="otp-box w-10 h-12 sm:w-14 sm:h-14 text-center text-xl sm:text-2xl font-bold form-input"
                        >
                    @endfor
                </div>
                
                {{-- Hidden input untuk backend --}}
                <input type="hidden" name="otp" id="otp-value">

                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 text-center">
                    @lang('Kode OTP berlaku terbatas, pastikan sesuai dengan yang dikirimkan.')
                </p>

                <x-link tag="button" type="submit" intent="primary" class="w-full mt-4" size="lg">
                    <x-slot:iconBefore>
                        <span class="icon-[material-symbols--login] text-lg"></span>
                    </x-slot:iconBefore>
                    @lang('Continue Login')
                </x-link>
            </form>

            {{-- Resend --}}
            <form action="{{ route(desa_module_template_meta('kebab').'.verify-otp.resend') }}" method="POST" class="mt-4 text-end">
                @csrf
                <x-link type="submit" intent="ghost" size="sm">
                    @lang('Resend OTP')
                </x-link>
            </form>
        </div>
    </div>

    {{-- Script untuk auto move dan gabung OTP --}}
    <script>
        const inputs = document.querySelectorAll('.otp-box');
        const hiddenInput = document.getElementById('otp-value');

        inputs.forEach((input, index) => {
            input.addEventListener('input', () => {
                input.value = input.value.replace(/[^0-9]/g, '');
                if (input.value && index < inputs.length - 1) inputs[index + 1].focus();
                updateHiddenInput();
            });

            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !input.value && index > 0) {
                    inputs[index - 1].focus();
                }
            });
        });

        function updateHiddenInput() {
            hiddenInput.value = Array.from(inputs).map(i => i.value).join('');
        }
    </script>

</x-desa-module-template::auth-layout>

<x-module-release-2::auth-layout :title="__('Forgot Password | Module Release 2')">

    <div class="rounded-xl pb-8 pt-6 px-8 shadow-2xl bg-white dark:bg-slate-900 animate-scale-in max-w-xl">
        <div>
            <h1 class="text-xl font-semibold text-slate-600 dark:text-gray-400">
                @lang('Forgot Password')
            </h1>

            <p class="text-left text-sm mb-6 mt-4 text-gray-600 dark:text-gray-500">
                @lang('Masukkan alamat email yang terdaftar. Kami akan mengirimkan tautan untuk mengatur ulang kata sandi')
            </p>

            @if (session('status'))
                <div class="mb-4 rounded-lg bg-green-100 text-green-800 px-4 py-3 text-sm text-center">
                    {{ session('status') }}
                </div>
            @endif

            <form action="{{ route(module_release_2_meta('kebab').'.password.email') }}" method="POST" class="space-y-4">
                @csrf
                @php
                    $emailIcon = '<span class="icon-[mage--email] text-lg"></span>';
                @endphp
                
                <x-form.input-text 
                    type="email" 
                    name="email" 
                    id="email" 
                    label="Alamat Email" 
                    :iconBefore="$emailIcon" 
                    placeholder="email@contoh.com" 
                    :required="true" 
                />

                <x-link 
                    tag="button" 
                    type="submit" 
                    intent="primary" 
                    class="w-full mt-4" 
                    size="lg"
                >
                    <x-slot:iconBefore>
                        <span class="icon-[akar-icons--send] text-lg"></span>
                    </x-slot:iconBefore>
                    @lang('Send Reset Link')
                </x-link>
            </form>
        </div>
    </div>

</x-module-release-2::auth-layout>

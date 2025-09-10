<x-desa-module-template::auth-layout :title="__('Login | Desa Module Template')">

    <div class="rounded-xl pb-8 pt-6 px-8 shadow-2xl bg-white dark:bg-slate-900 animate-scale-in max-w-xl">
        <div>
            <h1 class="text-xl font-semibold text-slate-600 dark:text-gray-400">
                @lang('Login')
            </h1>

            <p class="text-left text-sm mb-6 mt-4 text-gray-600 dark:text-gray-400">
                @lang('Silakan masuk dengan menggunakan alamat email dan kata sandi yang sudah terdaftar untuk mengakses akun Anda.')
            </p>

            <form action="{{ route(desa_module_template_meta('kebab').'.login') }}" method="POST" class="space-y-4">
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

                <x-form.input-text 
                    type="password" 
                    name="password" 
                    id="password" 
                    label="Password" 
                    helpText="Password minimal harus 8 karakter." 
                    :required="true" 
                />

                {{-- Remember Me --}}
                <x-form.input-checkbox 
                    name="remember" 
                    value="1" 
                    label="Remember Me" 
                    :isChecked="old('remember', false)" 
                />

                <x-link 
                    tag="button" 
                    type="submit" 
                    intent="primary" 
                    class="w-full mt-4" 
                    size="lg"
                >
                    <x-slot:iconBefore>
                        <span class="icon-[material-symbols--login] text-lg"></span>
                    </x-slot:iconBefore>
                    @lang('Login')
                </x-link>
                <div class="flex items-start justify-between">
                    <x-link href="{{ route(desa_module_template_meta('kebab').'.password.request') }}" intent="ghost">
                        @lang('Forgot Password?')
                    </x-link>
                    <x-link href="{{ route(desa_module_template_meta('kebab').'.register') }}" intent="ghost">
                        @lang('Register')
                    </x-link>
                </div>
            </form>
        </div>
    </div>

</x-desa-module-template::auth-layout>

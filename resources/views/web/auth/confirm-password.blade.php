<x-desa-module-template::auth-layout :title="__('Password Confirmation | Desa Module Template')">

   <div class="rounded-xl pb-8 pt-6 px-8 shadow-2xl bg-white dark:bg-slate-900 animate-scale-in max-w-xl">
        <div>
            <h1 class="text-xl font-semibold text-slate-600 dark:text-gray-400">
                @lang('Password Confirmation')
            </h1>

            <p class="text-left text-sm mb-6 mt-4 text-gray-600 dark:text-gray-500">
                @lang('Demi keamanan akun Anda, silakan masukkan kembali kata sandi sebelum melanjutkan.')
            </p>

            <form action="{{ route(desa_module_template_meta('kebab').'.password.confirm') }}" method="POST">
                @csrf

                <x-form.input-text 
                    type="password" 
                    name="password" 
                    id="password" 
                    label="Password" 
                    helpText="Password minimal harus 8 karakter." 
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
                        <span class="icon-[material-symbols--login] text-lg"></span>
                    </x-slot:iconBefore>
                    @lang('Confirm')
                </x-link>
            </form>
        </div>
   </div>

</x-desa-module-template::auth-layout>
<x-desa-module-template::auth-layout :title="__('Reset Password Success | Desa Module Template')">

    <div class="rounded-xl pb-10 pt-8 px-8 shadow-2xl bg-white dark:bg-slate-900 animate-scale-in max-w-md text-center">
        
        {{-- Icon Success --}}
        <div class="flex justify-center mb-6">
            <div class="bg-green-100 dark:bg-green-900/40 text-green-600 dark:text-green-400 p-4 rounded-full">
                <span class="icon-[mdi--check-circle] text-6xl"></span>
            </div>
        </div>

        {{-- Title --}}
        <h1 class="text-2xl font-bold text-slate-700 dark:text-white mb-2">
            @lang('Password Berhasil Direset')
        </h1>

        {{-- Description --}}
        <p class="text-gray-600 dark:text-gray-400 mb-8">
            @lang('Kata sandi Anda telah berhasil diubah. Silakan gunakan password baru Anda untuk login.')
        </p>

        {{-- Action Button --}}
        <x-link 
            :href="route(desa_module_template_meta('kebab').'.login')" 
            intent="primary" 
            size="lg" 
            class="w-full"
        >
            <x-slot:iconBefore>
                <span class="icon-[mdi--login] text-lg"></span>
            </x-slot:iconBefore>
            @lang('Kembali ke Login')
        </x-link>

    </div>
    
</x-desa-module-template::auth-layout>

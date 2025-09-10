<x-desa-module-template::auth-layout :title="__('Regiter | Desa Module Template')">

    <div class="rounded-xl pb-8 pt-6 px-8 shadow-2xl bg-white dark:bg-slate-900 animate-scale-in max-w-4xl">
        <div>
            <h1 class="text-xl font-semibold text-slate-600 dark:text-gray-400">
                @lang('Register')
            </h1>

            <p class="text-left text-sm mb-6 mt-2 text-gray-600 dark:text-gray-500">
                @lang('Buat akun baru untuk mulai menggunakan layanan dan fitur yang tersedia.')
            </p>

            <form action="{{ route(desa_module_template_meta('kebab').'.register') }}" method="POST" class="space-y-4">
                @csrf
                @php
                    $emailIcon = '<span class="icon-[mage--email] text-lg"></span>';
                    $nameIcon = '<span class="icon-[bx--user] text-lg"></span>';
                @endphp

                <x-form.input-text 
                    type="text" 
                    name="name" 
                    id="name" 
                    label="Name" 
                    :iconBefore="$nameIcon" 
                    :required="true" 
                />

                <x-form.input-text 
                    type="email" 
                    name="email" 
                    id="email" 
                    label="Alamat Email" 
                    :iconBefore="$emailIcon" 
                    placeholder="email@contoh.com" 
                    :required="true" 
                />

                <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                    <div>
                        <x-form.input-text 
                            type="password" 
                            name="password" 
                            id="password" 
                            label="Password" 
                            helpText="Password minimal harus 8 karakter." 
                            :required="true" 
                        />
                    </div>
                    <div>
                        <x-form.input-text 
                            type="password" 
                            name="password_confirmation" 
                            id="password_confirmation" 
                            label="Password Confirmation" 
                            helpText="Password Confirmation harus sama dengan password." 
                            :required="true" 
                        />
                    </div>
                </div>

                <x-form.input-select-region 
                    bag="users-table" 
                />

                <x-link tag="button" type="submit" intent="primary" class="w-full mt-2  " size="lg">
                    <x-slot:iconBefore>
                        <span class="icon-[material-symbols--login] text-lg"></span>
                    </x-slot:iconBefore>
                    @lang('Register')
                </x-link>

                <div class="flex items-start justify-end">
                    <x-link href="{{ route(desa_module_template_meta('kebab').'.login') }}" intent="ghost">
                        @lang('Login')
                    </x-link>
                </div>
            </form>
        </div>
    </div>

</x-desa-module-template::auth-layout>

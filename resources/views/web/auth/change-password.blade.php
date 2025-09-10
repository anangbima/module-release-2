<x-desa-module-template::auth-layout :title="__('Change Password | Desa Module Template')">

    <div class="rounded-xl pb-8 pt-6 px-8 shadow-2xl bg-white dark:bg-slate-900 animate-scale-in max-w-3xl">
        <div>
            <h1 class="text-xl font-semibold text-slate-600 dark:text-gray-400">
                @lang('Change Password')
            </h1>

            <p class="text-left text-sm mb-6 mt-4 text-gray-600 dark:text-gray-500">
                @lang('Untuk menjaga keamanan akun, silakan masukkan kata sandi lama Anda kemudian atur kata sandi baru.')
            </p>
        </div>

        {{-- Form --}}
        <form action="{{ route(desa_module_template_meta('kebab').'.password.store') }}" method="POST" class="space-y-4">
            @csrf

            <x-form.input-text 
                type="password"
                name="current_password"
                id="current_password"
                label="Current Password"
                placeholder="Masukkan password lama"
                :required="true"
                autocomplete="current-password"
            />

            <div class="flex flex-col gap-4 lg:flex-row">
                <div class="w-full lg:w-1/2">
                    <x-form.input-text 
                        type="password"
                        name="password"
                        id="password"
                        label="New Password"
                        placeholder="Masukkan password baru"
                        :required="true"
                        autocomplete="new-password"
                    />
                </div>
                <div class="w-full lg:w-1/2">
                    <x-form.input-text 
                        type="password"
                        name="password_confirmation"
                        id="password_confirmation"
                        label="Confirm Password"
                        placeholder="Ulangi password baru"
                        :required="true"
                        autocomplete="new-password"
                    />
                </div>
            </div>
            
            <x-link tag="button" type="submit" intent="primary" class="w-full mt-4" size="lg">
                <x-slot:iconBefore>
                    <span class="icon-[material-symbols--login] text-lg"></span>
                </x-slot:iconBefore>
                @lang('Update Password')
            </x-link>
        </form>
    </div>

</x-desa-module-template::auth-layout>

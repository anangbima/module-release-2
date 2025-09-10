<x-module-release-2::auth-layout :title="__('Reset Password | Module Release 2')">

    <div class="rounded-xl pb-8 pt-6 px-8 shadow-2xl bg-white dark:bg-slate-900 animate-scale-in max-w-3xl">
        <div>
            <h1 class="text-xl font-semibold text-slate-600 dark:text-gray-400">
                @lang('Reset Password')
            </h1>

            <p class="text-left text-sm mb-6 mt-4 text-gray-600 dark:text-gray-500">
                @lang('Atur ulang kata sandi Anda dengan memasukkan email, password baru, dan konfirmasi password.')
            </p>

            @if (session('status'))
                <div class="mb-4 rounded-lg bg-green-100 text-green-800 px-4 py-3 text-sm text-center">
                    {{ session('status') }}
                </div>
            @endif

            <form action="{{ route(module_release_2_meta('kebab').'.password.update') }}" method="POST" class="space-y-4">
                @csrf
                @php
                    $emailIcon = '<span class="icon-[mage--email] text-lg"></span>';
                @endphp

                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <x-form.input-text 
                    type="email" 
                    name="email" 
                    id="email" 
                    label="Alamat Email" 
                    :iconBefore="$emailIcon" 
                    placeholder="email@contoh.com" 
                    :required="true" 
                />

                <div class="flex flex-col gap-4 lg:flex-row">
                    <div class="w-full lg:w-1/2">
                        <x-form.input-text 
                            type="password" 
                            name="password" 
                            id="password" 
                            label="Password" 
                            helpText="Password minimal harus 8 karakter." 
                            :required="true" 
                        />
                    </div>
                    <div class="w-full lg:w-1/2">
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

                <x-link 
                    tag="button" 
                    type="submit" 
                    intent="primary" 
                    class="w-full mt-4" 
                    size="lg"
                >
                    <x-slot:iconBefore>
                        <span class="icon-[material-symbols--lock-reset-outline-rounded] text-lg"></span>
                    </x-slot:iconBefore>
                    @lang('Reset Password')
                </x-link>

            </form>
        </div>
    </div>

</x-module-release-2::auth-layout>

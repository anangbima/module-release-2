<x-dynamic-component
    :component="'desa-module-template::' . $role . '-layout'"
    :title="__($title)"
    :role="$role"
    :module="__(desa_module_template_meta('label'))"
    :desa="config('app.name')"
    :breadcrumbs="$breadcrumbs"
>
    {{-- Tambahkan ini setelah @endif error block --}}
    @if ($errors->any() && $errors->has('image'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                window.dispatchEvent(new CustomEvent('open-modal', { detail: { name: 'modal-picture' } }));
            });
        </script>
    @endif

    <div class="flex justify-center">
        <div class="w-[1200px] p-8">
            <div class="flex justify-between items-center py-20 border-b border-gray-200 dark:border-gray-700">
                {{-- Picture --}}
                <div class="flex gap-6 items-center">
                    <div class="w-24 h-24">
                        <img 
                            class="rounded-full w-full h-full object-cover" 
                            src="{{ $user->profile_image_url ?? asset('assets/default-profile.jpg') }}" 
                            alt="{{ $user->name }}"
                        >
                    </div>

                    <div class="">
                        <div class="font-semibold">
                            {{ $user->name }}
                        </div>
                        <div class=" text-gray-500 text-sm leading-flex">
                            {{ $user->email }}
                        </div>
                        <div class="text-gray-500 text-xs leading-flex">
                            {{ ucwords(strtolower($user->villageName ?? ' - ')) }},
                            {{ ucwords(strtolower($user->districtName ?? ' - ')) }},
                            {{ ucwords(strtolower($user->cityName ?? ' - ')) }},
                            {{ ucwords(strtolower($user->provinceName ?? ' - ')) }}
                        </div>
                    </div>
                </div>

                <div class="flex gap-2">
                    <x-link 
                        size="sm" 
                        class="rounded-lg"
                        intent="secondary"
                        href="#"
                        @click="$dispatch('open-modal', { name: 'modal-edit-picture' })"
                    >
                        Edit Picture
                    </x-link>

                    <x-link 
                        size="sm" 
                        class="rounded-lg"
                        intent="danger"
                        href="#"
                        @click="$dispatch('open-modal', { name: 'modal-delete-picture' })"
                    >
                        Delete Picture
                    </x-link>
                </div>
            </div>

            {{-- Personal Information --}}
            <div class="pt-12 pb-20 border-b border-gray-200 dark:border-gray-700">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-lg font-semibold">Personal Information</h1>

                    <x-link 
                        size="sm" 
                        class="rounded-lg"
                        intent="secondary"
                        href="#"
                        @click="$dispatch('open-modal', { name: 'modal-edit-personal' })"
                    >
                        Edit
                    </x-link>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    {{-- Name --}}
                    <div>
                        <p class="text-sm text-gray-500">Full Name</p>
                        <p class="font-medium">{{ $user->name }}</p>
                    </div>

                    {{-- Email --}}
                    <div>
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="font-medium">{{ $user->email }}</p>
                    </div>

                    {{-- Address --}}
                    <div class="sm:col-span-2">
                        <p class="text-sm text-gray-500">Address</p>
                        <p class="font-medium">
                            {{ $user->villageName ?? '-' }},
                            {{ $user->districtName ?? '-' }},
                            {{ $user->cityName ?? '-' }},
                            {{ $user->provinceName ?? '-' }}
                        </p>
                    </div>

                    {{-- Role --}}
                    <div>
                        <p class="text-sm text-gray-500">Role</p>
                        <p class="font-medium">
                            {{ $user->roles->pluck('name')->join(', ') ?? '-' }}
                        </p>
                    </div>

                    {{-- Status --}}
                    <div>
                        <p class="text-sm text-gray-500">Status</p>
                        <p>
                            @if($user->status == 'active')
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700">
                                    Active
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-700">
                                    Inactive
                                </span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            {{-- Security --}}
            <div class="pt-12 pb-20 border-b border-gray-200 dark:border-gray-700">
                <div class="flex justify-between items-center mb-6">
                    <div class="w-full lg:w-3/4">
                        <h1 class="text-lg font-semibold">Security</h1>

                        <div class="text-sm mt-3">
                            Lorem ipsum dolor, sit amet consectetur adipisicing elit. Molestias hic dicta aliquam? Ea, provident! Harum, numquam. Explicabo ad eligendi iusto!
                        </div>
                    </div>
                    
                    <div class="w-full lg:w-1/4 flex justify-end gap-2"">
                        <x-link 
                            size="sm" 
                            class="rounded-lg"
                            intent="secondary"
                            href="#"
                            href="{{ route(desa_module_template_meta('kebab').'.password.change') }}"
                        >
                            Change Password
                        </x-link>

                         <x-link 
                            size="sm" 
                            class="rounded-lg"
                            intent="secondary"
                            href="#"
                            href="{{ route(desa_module_template_meta('kebab').'.password.confirm') }}"
                        >
                            Confirmation Password
                        </x-link>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Update Picture Modal --}}
    <x-modal 
        name="modal-edit-picture" 
        title="Update Picture"  
        maxWidth="2xl"
    >
        <form 
            action="{{ route(desa_module_template_meta('kebab').'.'.$role.'.profile-image.update', auth(desa_module_template_meta('snake').'_web')->user()->id) }}" 
            method="POST" 
            enctype="multipart/form-data" 
        >
            @csrf
            @method('PUT')

            <div>
                <x-form.input-image 
                    :name="'image'" 
                    :label="'Foto Profile'" 
                    :helpText="'Menampilkan gambar dari server.'" 
                    :existingPreview="$user->profile_image_url ?? asset('assets/default-profile.jpg') " 
                />
            </div>

            <div class="flex justify-end mt-4">
                <x-link 
                    class="cursor-pointer ui-btn-solid"
                    type="submit"
                    size="md" 
                    intent="primary"
                >
                    Update Picture
                </x-link>
            </div>
            
        </form>
    </x-modal>

    {{-- Delete ptofile picture Modal --}}
    <x-modal 
        name="modal-delete-picture" 
        title="Delete Profile Picture!" 
        :static="true" 
        maxWidth="md"
    >
        <form action="{{ route(desa_module_template_meta('kebab').'.'.$role.'.profile-image.destroy') }}" method="POST">
            @csrf
            @method('DELETE')

            <div class="flex flex-col items-center px-6 pb-6 text-center mb-6">
                <span class="icon-[mage--exclamation-triangle] size-16 text-danger mb-6"></span>

                <p class="text-center text-sm text-slate-600 dark:text-slate-300">
                    Apakah Anda yakin ingin menghapus foto profil? Tindakan ini tidak dapat diurungkan.
                </p>
            </div>

            <div class="flex w-full justify-end gap-3 ">
                <x-link 
                    @click="$dispatch('close-modal')" 
                    intent="secondary"
                    size="md"
                    href="#"  
                >
                    Cancel
                </x-link>
                <x-link 
                    class="cursor-pointer"
                    type="submit"
                    size="md" 
                    intent="danger"
                >
                    Confirm
                </x-link>
            </div>
        </form>
    </x-modal>

    {{-- Update Personal information Modal --}}
    <x-modal 
        name="modal-edit-personal" 
        title="Update Personal Infromation"  
        maxWidth="2xl"
    >
        <form 
            action="{{ route(desa_module_template_meta('kebab').'.'.$role.'.profile.update', $user->slug) }}" 
            method="POST" 
            enctype="multipart/form-data" 
        >
            @csrf
            @method('PUT')

            @php
                $emailIcon = '<span class="icon-[mage--email] text-lg"></span>';
                $nameIcon = '<span class="icon-[bx--user] text-lg"></span>';
            @endphp

            <x-form.input-text 
                type="text" 
                name="name" 
                id="name" 
                value="{{ old('name', $user->name) }}"
                label="Name" 
                :iconBefore="$nameIcon" 
                :required="true" 
            />

            <x-form.input-text 
                type="email" 
                name="email" 
                id="email" 
                value="{{ old('email', $user->email) }}"
                label="Alamat Email" 
                :iconBefore="$emailIcon" 
                placeholder="email@contoh.com" 
                :required="true" 
                readonly
            />

            <x-form.input-select-region 
                bag="users-table"
                :currentProvince="$user->province_code" 
                :currentCity="$user->city_code" 
                :currentDistrict="$user->district_code"
                :currentVillage="$user->village_code"
            />

            <div class="flex justify-end gap-3 mt-8">
                <x-link 
                    intent="secondary"
                    size="md"
                    href="#" 
                    @click="$dispatch('close-modal')"
                >
                    Cancel
                </x-link>

                <x-link 
                    class="cursor-pointer ui-btn-solid"
                    type="submit"
                    size="md" 
                    intent="primary"
                >
                    Update Profile
                </x-link>
            </div>            
        </form>
    </x-modal>

    {{-- Update password --}}
</x-dynamic-component>

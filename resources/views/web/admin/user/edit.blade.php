<x-desa-module-template::admin-layout
    :title="__('Edit User: '.$user->name)"
    :role="'Admin'"
    :module="__(desa_module_template_meta('label'))"
    :desa="config('app.name')"
    :breadcrumbs="$breadcrumbs"
>   
    <div class="flex justify-center">
        <div class="w-full max-w-4xl px-4 sm:px-6 md:px-8 py-8">
            {{-- Form section --}}
            <form 
                action="{{ route(desa_module_template_meta('kebab').'.admin.users.update', $user->slug) }}" 
                method="POST" 
                class="space-y-6"
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
                    label="Name" 
                    :iconBefore="$nameIcon" 
                    :value="$user->name"
                    :required="true" 
                />

                <x-form.input-text 
                    type="email" 
                    name="email" 
                    id="email" 
                    label="Alamat Email" 
                    :iconBefore="$emailIcon" 
                    placeholder="email@contoh.com" 
                    :value="$user->email"
                    :required="true" 
                />

                <x-form.input-select 
                    name="role" 
                    label="Roles" 
                    :options="$availableRoles->map(fn($p) => [
                        'value' => $p->id, 
                        'label' => $p->name
                    ])->toArray()" 
                    :selected="[$user->role]" 
                    placeholder="Search and select Roles..." 
                    :required="true"
                />

                <x-form.input-select-region 
                    bag="users-table" 
                    :currentProvince="$user->province_code ?? null"
                    :currentCity="$user->city_code ?? null"
                    :currentDistrict="$user->district_code ?? null"
                    :currentVillage="$user->village_code ?? null"
                />

                {{-- Action Buttons --}}
                <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 pt-6">
                    <x-link 
                        intent="secondary"
                        size="md"
                        href="{{ route(desa_module_template_meta('kebab').'.admin.users.index') }}" 
                        class="w-full sm:w-auto text-center"
                    >
                        Cancel
                    </x-link>

                    <x-link
                        type="submit"
                        size="md" 
                        intent="primary"
                        class="w-full sm:w-auto cursor-pointer ui-btn-solid"
                    >
                        Update User
                    </x-link>
                </div>
            </form>
        </div>
    </div>

</x-desa-module-template::admin-layout>

<x-module-release-2::admin-layout
    :title="__('Add New Role')"
    :role="'Admin'"
    :module="__(module_release_2_meta('label'))"
    :desa="config('app.name')"
    :breadcrumbs="$breadcrumbs"
>
    <div class="flex justify-center">
        <div class="w-full max-w-2xl px-4 sm:px-6 md:px-8 py-8">
            <form 
                action="{{ route(module_release_2_meta('kebab').'.admin.roles.store') }}" 
                method="POST" 
                class="space-y-6"
            >
                @csrf

                {{-- Role Name --}}
                <x-form.input-text 
                    type="text" 
                    name="name" 
                    id="name" 
                    label="Role Name" 
                    :required="true" 
                />

                {{-- Permission --}}
                <x-form.input-select 
                    name="permissions" 
                    label="Permissions" 
                    :options="$availablePermissions->map(fn($p) => [
                        'value' => $p->id, 
                        'label' => $p->name
                    ])->toArray()" 
                    :selected="$selectedPermissions ?? []" 
                    placeholder="Search and select permissions..." 
                    :multiple="true" 
                    :required="true"
                />


                {{-- Action Buttons --}}
                <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 pt-6">
                    <x-link 
                        intent="secondary"
                        size="md"
                        href="{{ route(module_release_2_meta('kebab').'.admin.roles.index') }}" 
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
                        Create Role
                    </x-link>
                </div>
            </form>
        </div>
    </div>

</x-module-release-2::admin-layout>

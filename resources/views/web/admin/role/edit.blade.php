<x-desa-module-template::admin-layout
    :title="__('Edit Role')"
    :role="'Admin'"
    :module="__(desa_module_template_meta('label'))"
    :desa="config('app.name')"
    :breadcrumbs="$breadcrumbs"
>
    <div class="flex justify-center">
        <div class="w-full max-w-2xl px-4 sm:px-6 md:px-8 py-8">
            <form 
                action="{{ route(desa_module_template_meta('kebab').'.admin.roles.update', $role->id) }}" 
                method="POST" 
                class="space-y-6"
            >
                @csrf
                @method('PUT')

                {{-- Role Name --}}
                <x-form.input-text 
                    type="text" 
                    name="name" 
                    id="name" 
                    label="Role Name" 
                    :value="$role->name"
                    :required="true" 
                />

                {{-- Permissions --}}
                <x-form.input-select 
                    name="permissions" 
                    label="Permissions" 
                    :options="$availablePermissions->map(fn($p) => [
                        'value' => $p->id, 
                        'label' => $p->name
                    ])->toArray()" 
                    :selected="$role->permissions->pluck('id')->toArray() ?? []" 
                    placeholder="Search and select permissions..." 
                    :multiple="true" 
                    :required="true"
                />

                {{-- Action Buttons --}}
                <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 pt-6">
                    <x-link 
                        intent="secondary"
                        size="md"
                        href="{{ route(desa_module_template_meta('kebab').'.admin.roles.index') }}" 
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
                        Update Role
                    </x-link>
                </div>
            </form>
        </div>
    </div>

    
</x-desa-module-template::admin-layout>

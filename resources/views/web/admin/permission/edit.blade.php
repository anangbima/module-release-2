<x-module-release-2::admin-layout
    :title="__('Edit Permission: '.$permission->name)"
    :role="'Admin'"
    :module="__(module_release_2_meta('label'))"
    :desa="config('app.name')"
    :breadcrumbs="$breadcrumbs"
>

    <div class="flex justify-center">
        <div class="w-full max-w-2xl px-4 sm:px-6 md:px-8 py-8">
            <form 
                action="{{ route(module_release_2_meta('kebab').'.admin.permissions.update', $permission->id) }}" 
                method="POST"
                class="space-y-6"
            >
                @csrf
                @method('PUT') {{-- method spoofing untuk update --}}

                {{-- Permission Name --}}
                <x-form.input-text 
                    type="text" 
                    name="name" 
                    id="name" 
                    label="Name" 
                    :value="$permission->name"
                    :required="true" 
                />

                {{-- Action Buttons --}}
                <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 pt-6">
                    <x-link 
                        intent="secondary"
                        size="md"
                        href="{{ route(module_release_2_meta('kebab').'.admin.permissions.index') }}" 
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
                        Update Permission
                    </x-link>
                </div>
            </form>
        </div>
    </div>

</x-module-release-2::admin-layout>

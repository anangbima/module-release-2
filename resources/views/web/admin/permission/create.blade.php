<x-desa-module-template::admin-layout
    :title="__($title)"
    :role="'Admin'"
    :module="__(desa_module_template_meta('label'))"
    :desa="config('app.name')"
    :breadcrumbs="$breadcrumbs"
>

    <div class="flex justify-center">
        <div class="w-full max-w-2xl px-4 sm:px-6 md:px-8 py-8">
            <form 
                action="{{ route(desa_module_template_meta('kebab').'.admin.permissions.store') }}" 
                method="POST"
                class="space-y-6"
            >
                @csrf

                {{-- Role Name --}}
                <x-form.input-text 
                    type="text" 
                    name="name" 
                    id="name" 
                    label="Name" 
                    :required="true" 
                />

                {{-- Action Buttons --}}
                <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 pt-6">
                    <x-link 
                        intent="secondary"
                        size="md"
                        href="{{ route(desa_module_template_meta('kebab').'.admin.permissions.index') }}" 
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
                        Create Permission
                    </x-link>
                </div>
            </form>
        </div>
    </div>


</x-desa-module-template::admin-layout>

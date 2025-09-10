<x-desa-module-template::admin-layout
    :title="__($title)"
    :role="'Admin'"
    :module="__(desa_module_template_meta('label'))"
    :desa="config('app.name')"
    :breadcrumbs="$breadcrumbs"
>

<br>
    @if(session('success'))
        <div class="alert alert-success mb-4">
            {{ session('success') }}
        </div>
    @endif
<br>

    <form action="{{ route(desa_module_template_meta('kebab').'.admin.settings.update') }}" method="POST">
        @csrf
        @method('PUT')

        @php
            $grouped = $settings->groupBy('group');
        @endphp
        
        @foreach ($grouped as $groupName => $groupSettings)
            {{-- Section per group --}}
            <div class="mb-8 border-b border-gray-200 dark:border-gray-700 pb-6 flex gap-8">
                <div class="w-full lg:w-1/3">
                    <h1 class="text-lg font-bold text-gray-700 dark:text-gray-200">
                        {{ ucfirst($groupName) }}
                    </h1>
                    <div class="text-xs text-gray-600 mt-1">
                        {{-- {{ $descriptions[$groupName] ?? 'Pengaturan untuk ' . ucfirst($groupName) }} --}}
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Ratione, quam in. Dolorum officia voluptates ipsum quas quisquam animi necessitatibus nobis, possimus nemo repudiandae ullam eos?
                    </div>
                </div>
                <div class="w-full lg:w-2/3">
                    <div class="lg:px-20 lg:py-6 p-0">
                        @foreach ($groupSettings as $setting)
                            @php
                                $inputType = match($setting->type) {
                                    'boolean' => 'checkbox',
                                    'integer' => 'number',
                                    default => 'text',
                                };
                            @endphp
                            
                            <div class="mb-4">
                                <x-form.input-text 
                                    type="{{ $inputType }}"
                                    name="{{ $setting->key }}"
                                    id="{{ $setting->key }}"
                                    label="{{ ucfirst(str_replace('_',' ', $setting->key)) }}"
                                    value="{{ old($setting->key, $setting->value) }}"
                                    :required="true"
                                />
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach

        {{-- Action buttons --}}
        <div class="mt-8 flex justify-end gap-2">
            <x-link 
                :intent="'ghost'" 
                href="{{ route(desa_module_template_meta('kebab').'.admin.settings.index') }}"
            >
                Cancel
            </x-link>
            <x-link 
                class="cursor-pointer ui-btn-solid"
                type="submit"
                size="md" 
                intent="primary" 
            >
                Cancel
            </x-link>
        </div>
    </form>

</x-desa-module-template::admin-layout>

<x-desa-module-template::admin-layout
    :title="__($title)"
    :role="'Admin'"
    :module="__(desa_module_template_meta('label'))"
    :desa="config('app.name')"
    :breadcrumbs="$breadcrumbs"
>
    {{-- Table Section --}}
    <x-desa-module-template::log-activity-table 
        :logs="$logs"
    />

</x-desa-module-template::admin-layout>
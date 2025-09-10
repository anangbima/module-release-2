<x-module-release-2::admin-layout
    :title="__($title)"
    :role="'Admin'"
    :module="__(module_release_2_meta('label'))"
    :desa="config('app.name')"
    :breadcrumbs="$breadcrumbs"
>
    {{-- Table Section --}}
    <x-module-release-2::log-activity-table 
        :logs="$logs"
    />

</x-module-release-2::admin-layout>
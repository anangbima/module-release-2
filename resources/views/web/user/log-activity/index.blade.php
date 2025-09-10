<x-module-release-2::user-layout
    :title="__($title)"
    :role="'User'"
    :module="__(module_release_2_meta('label'))"
    :desa="config('app.name')"
    :breadcrumbs="$breadcrumbs"
>
    {{-- Description --}}
    <div class="text-sm p-4 bg-gray-100 rounded-lg text-gray-600 dark:text-gray-400 mb-6">
        Log Activities record all important actions within the system, 
        including which user performed them, when they occurred, and a brief description of the activity. 
        This information helps to monitor system usage and conduct audits when necessary. 
        <span><a class="text-blue-500 hover:text-blue-700" href="#">Learn more</a></span>
    </div>

    {{-- Table section --}}
    <x-module-release-2::log-activity-table 
        :logs="$logs"
    />

</x-module-release-2::user-layout>
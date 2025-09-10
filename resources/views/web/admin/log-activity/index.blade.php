<x-module-release-2::admin-layout
    :title="__($title)"
    :role="'Admin'"
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

    {{-- Button Export --}}
    <div class="mb-2 flex justify-end">
        <div>
            <x-link 
                size="sm" 
                class="rounded-lg"
                intent="secondary"
                href="#"
                @click="$dispatch('open-modal', { name: 'modal-export' })"
            >
                <x-slot:iconBefore>
                    <span class="icon-[solar--export-outline] text-lg"></span>
                </x-slot:iconBefore>
                Export
            </x-link>
        </div>
    </div>

    {{-- Table Section --}}
    <x-module-release-2::log-activity-table 
        :logs="$logs"
    />

    {{-- Export Modal --}}
    <x-modal 
        name="modal-export" 
        title="Export Data Log" 
        maxWidth="xl"
    >
        <div class="pb-4 space-y-4">

            <p class="text-sm text-gray-600 dark:text-gray-400">
                Pilih format file yang ingin digunakan untuk mengekspor data pengguna. 
            </p>

            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                <a 
                    href="{{ route(module_release_2_meta('kebab').'.admin.logs.export', ['type' => 'xlsx']) }}" 
                    class="bg-white dark:bg-slate-900 flex items-center justify-center border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800 transition"
                >
                    <span class="icon-[mdi--microsoft-excel] text-green-600 mr-2"></span>
                    XLSX
                </a>

                <a 
                    href="{{ route(module_release_2_meta('kebab').'.admin.logs.export', ['type' => 'csv']) }}" 
                    class="bg-white dark:bg-slate-900 flex items-center justify-center border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800 transition"
                >
                    <span class="icon-[mdi--file-delimited] text-blue-600 mr-2"></span>
                    CSV
                </a>

                <a 
                    href="{{ route(module_release_2_meta('kebab').'.admin.logs.export', ['type' => 'pdf']) }}" 
                    class="bg-white dark:bg-slate-900 flex items-center justify-center border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800 transition"
                >
                    <span class="icon-[mdi--file-pdf-box] text-red-600 mr-2"></span>
                    PDF
                </a>

                <a 
                    href="{{ route(module_release_2_meta('kebab').'.admin.logs.export', ['type' => 'docx']) }}" 
                    class="bg-white dark:bg-slate-900 flex items-center justify-center border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800 transition"
                >
                    <span class="icon-[mdi--microsoft-word] text-blue-700 mr-2"></span>
                    DOCX
                </a>

                <a 
                    href="{{ route(module_release_2_meta('kebab').'.admin.logs.export', ['type' => 'json']) }}" 
                    class="bg-white dark:bg-slate-900 flex items-center justify-center border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800 transition"
                >
                    <span class="icon-[mdi--code-json] text-yellow-600 mr-2"></span>
                    JSON
                </a>
            </div>
        </div>

        <x-slot name="footer">
            <button @click="$dispatch('close-modal')" class="rounded-md bg-gray-200 px-3 py-1 text-gray-700 text-sm hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600 transition cursor-pointer">
                Close
            </button>
        </x-slot>
    </x-modal>

</x-module-release-2::admin-layout>
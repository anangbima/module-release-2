<x-module-release-2::admin-layout
    :title="__($title)"
    :role="'Admin'"
    :module="__(module_release_2_meta('label'))"
    :desa="config('app.name')"
    :breadcrumbs="$breadcrumbs"
>

    <div class="flex justify-center">
        <div class="w-full max-w-3xl px-4 sm:px-6 md:px-8 py-8 space-y-8">

            {{-- API Client Info --}}
            <div>
                <h3 class="mb-6 text-lg font-semibold text-gray-800 dark:text-gray-200">
                    API Client Information
                </h3>

                <div class="space-y-4">
                    {{-- ID --}}
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">ID</p>
                        <p class="text-base font-semibold text-gray-900 dark:text-gray-100">
                            {{ $apiClient->id }}
                        </p>
                    </div>

                    {{-- Name --}}
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Name</p>
                        <p class="text-base font-semibold text-gray-900 dark:text-gray-100">
                            {{ $apiClient->name }}
                        </p>
                    </div>

                    {{-- API Key --}}
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">API Key</p>
                        <p class="text-base font-semibold text-gray-900 dark:text-gray-100 break-all">
                            {{ $apiClient->api_key }}
                        </p>
                    </div>

                    {{-- Secret Key --}}
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Secret Key</p>
                        <p class="text-base font-semibold text-gray-900 dark:text-gray-100 break-all">
                            {{ $apiClient->secret_key }}
                        </p>
                    </div>

                    {{-- Created At --}}
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Created At</p>
                        <p class="text-base font-semibold text-gray-900 dark:text-gray-100">
                            {{ $apiClient->created_at ? $apiClient->created_at->format('d M Y H:i') : '-' }}
                        </p>
                    </div>

                    {{-- Updated At --}}
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Last Updated</p>
                        <p class="text-base font-semibold text-gray-900 dark:text-gray-100">
                            {{ $apiClient->updated_at ? $apiClient->updated_at->format('d M Y H:i') : '-' }}
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>

</x-module-release-2::admin-layout>

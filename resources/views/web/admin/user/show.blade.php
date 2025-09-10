<x-module-release-2::admin-layout
    :title="__($title)"
    :role="'Admin'"
    :module="__(module_release_2_meta('label'))"
    :desa="config('app.name')"
    :breadcrumbs="$breadcrumbs"
>
    <div class="flex justify-center">
        <div class="w-full max-w-4xl px-4 sm:px-6 md:px-8 py-8 space-y-8">

            {{-- Account Information --}}
            <section class="space-y-4">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Account Information</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">ID</p>
                        <p class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ $user->id }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Slug</p>
                        <p class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ $user->slug }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Name</p>
                        <p class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ $user->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Email</p>
                        <p class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ $user->email }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Role</p>
                        <p class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ $user->role }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Status</p>
                        <span class="inline-block px-2 py-1 text-xs font-medium rounded {{ $user->status == 'active' ? 'bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-200' }}">
                            {{ ucfirst($user->status) }}
                        </span>
                    </div>
                </div>
            </section>

            {{-- Address Information --}}
            <section class="space-y-4">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Address Information</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Province</p>
                        <p class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ $user->province_name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">City</p>
                        <p class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ $user->city_name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">District</p>
                        <p class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ $user->district_name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Village</p>
                        <p class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ $user->village_name ?? '-' }}</p>
                    </div>
                </div>
            </section>

            {{-- Timestamps --}}
            <section class="space-y-4">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Timestamps</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Created At</p>
                        <p class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ format_date($user->created_at, 'd M Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Updated At</p>
                        <p class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ format_date($user->updated_at, 'd M Y H:i') }}</p>
                    </div>
                </div>
            </section>

        </div>
    </div>

</x-module-release-2::admin-layout>
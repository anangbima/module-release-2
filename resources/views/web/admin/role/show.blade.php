<x-desa-module-template::admin-layout
    :title="$title"
    :role="'Admin'"
    :module="__(desa_module_template_meta('label'))"
    :desa="config('app.name')"
    :breadcrumbs="$breadcrumbs"
>
    <div class="flex justify-center">
        <div class="w-full max-w-3xl px-4 sm:px-6 md:px-8 py-8 space-y-8">

            {{-- Role Info --}}
            <div class="">
                <h3 class="mb-6 text-lg font-semibold text-gray-800 dark:text-gray-200">
                    Role Information
                </h3>

                <div class="space-y-4">
                    {{-- Role Name --}}
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Role Name</p>
                        <p class="text-base font-semibold text-gray-900 dark:text-gray-100">
                            {{ $role->name }}
                        </p>
                    </div>

                    {{-- Assigned Users --}}
                    <div class="mb-4">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Assigned Users</p>
                        <div class="inline-flex items-center px-3 py-1 rounded-full bg-blue-100 dark:bg-blue-800 text-blue-800 dark:text-blue-200 font-semibold text-sm mt-2">
                            <span class="icon-[uil--user] size-4 mr-2"></span>
                            {{ $role->users->count() ?? 0 }} user{{ $role->users->count() > 1 ? 's' : '' }}
                        </div>
                    </div>

                    {{-- Created / Updated --}}
                    <div class="flex gap-6 mt-6 text-sm text-gray-500 dark:text-gray-400">
                        <span>Created: {{ $role->created_at ? $role->created_at->format('d M Y H:i') : '-' }}</span>
                        <span>Last Updated: {{ $role->updated_at ? $role->updated_at->format('d M Y H:i') : '-' }}</span>
                    </div>
                </div>
            </div>

            {{-- Permissions --}}
            <div class="mt-12">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-6">
                    Assigned Permissions
                </h3>

                @if($role->permissions->isNotEmpty())
                    <ul class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2">
                        @foreach($role->permissions as $permission)
                            <li class="px-3 py-2 bg-gray-50 dark:bg-gray-700 rounded-lg text-sm text-gray-700 dark:text-gray-300">
                                {{ $permission->name }}
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg flex justify-center items-center min-h-[400px]">
                        <p class="text-sm text-gray-500 dark:text-gray-400">No permissions assigned.</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-desa-module-template::admin-layout>

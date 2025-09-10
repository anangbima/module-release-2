<x-module-release-2::admin-layout
    :title="__($title)"
    :role="'Admin'"
    :module="__(module_release_2_meta('label'))"
    :desa="config('app.name')"
    :breadcrumbs="$breadcrumbs"
>

    <div class="flex justify-center">
        <div class="w-full max-w-3xl px-4 sm:px-6 md:px-8 py-8 space-y-8">

            {{-- Permission Info --}}
            <div class="">
                <h3 class="mb-6 text-lg font-semibold text-gray-800 dark:text-gray-200">
                    Permission Information
                </h3>

                <div class="space-y-4">
                    {{-- Permission Name --}}
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Permission Name</p>
                        <p class="text-base font-semibold text-gray-900 dark:text-gray-100">
                            {{ $permission->name }}
                        </p>
                    </div>

                    {{-- Slug / Identifier --}}
                    @if($permission->slug ?? false)
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Slug / Identifier</p>
                        <p class="text-base font-semibold text-gray-900 dark:text-gray-100">
                            {{ $permission->slug }}
                        </p>
                    </div>
                    @endif

                    {{-- Description --}}
                    @if($permission->description ?? false)
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Description</p>
                        <p class="text-base text-gray-700 dark:text-gray-300">
                            {{ $permission->description }}
                        </p>
                    </div>
                    @endif

                    {{-- Assigned Roles --}}
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Assigned Roles</p>
                        @if($permission->roles->isNotEmpty())
                            <ul class="flex flex-wrap gap-2 mt-2">
                                @foreach($permission->roles as $role)
                                    <span class="px-3 py-1 rounded-full bg-blue-100 dark:bg-blue-800 text-blue-800 dark:text-blue-200 text-sm font-medium">
                                        {{ $role->name }}
                                    </span>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">No roles assigned.</p>
                        @endif
                    </div>

                    {{-- Created / Updated --}}
                    <div class="flex gap-6 mt-6 text-sm text-gray-500 dark:text-gray-400">
                        <span>Created: {{ $permission->created_at ? $permission->created_at->format('d M Y H:i') : '-' }}</span>
                        <span>Last Updated: {{ $permission->updated_at ? $permission->updated_at->format('d M Y H:i') : '-' }}</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
    
</x-module-release-2::admin-layout>
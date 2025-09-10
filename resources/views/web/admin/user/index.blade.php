<x-module-release-2::admin-layout
    :title="__($title)"
    :role="'Admin'"
    :module="__(module_release_2_meta('label'))"
    :desa="config('app.name')"
    :breadcrumbs="$breadcrumbs"
>   
    {{-- Summary Section --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        {{-- Total user --}}
        <div class="flex gap-6 items-center">
            <!-- Box total -->
            <div class="min-w-16 min-h-16 px-3 py-2 flex items-center justify-center rounded-lg bg-blue-50 dark:bg-blue-900/40">
                <span class="text-xl font-bold text-blue-600 dark:text-blue-400">
                    <span class=" icon-[solar--user-linear]"></span>
                </span>
            </div>

            <!-- Label -->
            <div>
                <p class="text-sm text-gray-700 dark:text-gray-300">
                    Pengguna
                </p>
                <div class="text-2xl font-bold">
                    {{ $users->count() }}
                </div>
            </div>
        </div>

        {{-- Total active --}}
        <div class="flex gap-6 items-center">
            <!-- Box total -->
            <div class="min-w-16 min-h-16 px-3 py-2 flex items-center justify-center rounded-lg bg-green-50 dark:bg-green-900/40">
                <span class="text-xl font-bold text-green-600 dark:text-green-400">
                    <span class=" icon-[solar--user-check-line-duotone]"></span>
                </span>
            </div>

            <!-- Label -->
            <div>
                <p class="text-sm text-gray-700 dark:text-gray-300">
                    Active
                </p>
                <div class="text-2xl font-bold">
                    {{ $totalActive }}
                </div>
            </div>
        </div>

        {{-- Total Inactive --}}
        <div class="flex gap-6 items-center">
            <!-- Box total -->
            <div class="min-w-16 min-h-16 px-3 py-2 flex items-center justify-center rounded-lg bg-red-50 dark:bg-red-900/40">
                <span class="text-xl font-bold text-red-600 dark:text-red-400">
                    <span class=" icon-[solar--user-block-line-duotone]"></span>
                </span>
            </div>

            <!-- Label -->
            <div>
                <p class="text-sm text-gray-700 dark:text-gray-300">
                    Inactive
                </p>
                <div class="text-2xl font-bold">
                    {{ $totalInactive }}
                </div>
            </div>
        </div>

        {{-- Redirect to Role --}}
        <a href="{{ route(module_release_2_meta('kebab').'.admin.roles.index') }}" 
        class="flex gap-2 items-center p-4 rounded-lg bg-gray-50 dark:bg-gray-900/40 hover:bg-gray-100 dark:hover:bg-gray-800/60 transition">
            <!-- Icon box -->
            <div class="min-w-12 min-h-12 flex items-center justify-center rounded-lg">
                <span class="text-xl font-bold text-gray-600 dark:text-gray-300">
                    <span class="icon-[solar--shield-user-outline]"></span>
                </span>
            </div>

            <!-- Label -->
            <div>
                <p class="text-sm text-gray-700 dark:text-gray-300">
                    Role Management
                </p>
                <div class="text-md font-semibold text-gray-700 dark:text-gray-300">
                    Kelola Role & Permission
                </div>
            </div>
        </a>
    </div>

    {{-- Button Add, Export and Import --}}
    <div class="mb-2 flex justify-between items-center">
        <div>
            <x-link 
                intent="primary"
                class="ui-btn-solid rounded-lg" 
                size="md"
                href="{{ route(module_release_2_meta('kebab').'.admin.users.create') }}"
            >
                <x-slot:iconBefore>
                    <span class="icon-[pepicons-pop--plus] text-lg"></span>
                </x-slot:iconBefore>
                New User
            </x-link>
        </div>

        {{-- Button import/export --}}
        <div class="flex justify-start items-center gap-3">
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
    
            <div>
                <x-link 
                    size="sm" 
                    class="rounded-lg"
                    intent="secondary"
                    href="#"
                    @click="$dispatch('open-modal', { name: 'modal-import' })"
                >
                    <x-slot:iconBefore>
                        <span class="icon-[solar--import-outline] text-lg "></span>
                    </x-slot:iconBefore>
                    Import
                </x-link>
            </div>
        </div>
    </div>

    {{-- Table Section --}}
    <div>
        <x-table-default
            title="All Users"
            :headers="[
                'Name', 
                'Roles',
                'Region',
                'Status',
                'Last Activity',
            ]"
        >
            @foreach($users as $user)
                <tr class="h-18">
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <div class="flex items-center gap-5">
                            <img
                                src="{{ $user->profile_image_url ?? asset('assets/default-profile.jpg') }}"
                                alt="{{ $user->name }}"
                                class="w-10 h-10 rounded-full object-cover ring-1 ring-gray-200 dark:ring-gray-800"
                            >
                            <div class="flex flex-col">
                                <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">
                                    {{ $user->name }}
                                </span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $user->email }}
                                </span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td class="max-w-xs truncate">
                        <span
                            title="{{ $user->villageName ?? '-' }}, {{ $user->districtName ?? '-' }}, {{ $user->cityName ?? '-' }}, {{ $user->provinceName ?? '-' }}">
                            {{ ucwords(strtolower($user->villageName ?? ' - ')) }},
                            {{ ucwords(strtolower($user->districtName ?? ' - ')) }},
                            {{ ucwords(strtolower($user->cityName ?? ' - ')) }},
                            {{ ucwords(strtolower($user->provinceName ?? ' - ')) }}
                        </span>
                    </td>
                    <td>
                        @if ($user->status === 'active')
                            <x-badge 
                                text="Active" 
                                color="success-outline" 
                                size="sm"
                            />
                        @else
                            <x-badge  
                                text="Inactive"  
                                color="danger-outline"
                                size="sm" 
                            />
                        @endif
                    </td>
                    <td>
                        {{ $user->lastActivity?->logged_at?->diffForHumans() ?? '-' }}
                    </td>
                    <td x-data="{ isOpen: false }" class="relative">
                        <!-- Trigger button -->
                        <div 
                            class="flex items-center justify-center cursor-pointer" 
                            @click="isOpen = !isOpen"
                        >
                            <span class="text-2xl icon-[pepicons-pencil--dots-y]"></span>
                        </div>

                        <!-- Dropdown -->
                        <div 
                            x-show="isOpen" 
                            @click.away="isOpen = false" 
                            x-transition:enter="transition ease-out duration-200" 
                            x-transition:enter-start="opacity-0 scale-95" 
                            x-transition:enter-end="opacity-100 scale-100" 
                            x-transition:leave="transition ease-in duration-150" 
                            x-transition:leave-start="opacity-100 scale-100" 
                            x-transition:leave-end="opacity-0 scale-95" 
                            class="absolute right-2 z-50 mt-2 w-48 origin-top-right rounded-lg bg-white dark:bg-gray-900/30 backdrop-blur-xl shadow-xl ring-1 ring-gray-900/5 overflow-hidden"
                             x-cloak
                        >
                            <div class="p-2">
                                <a 
                                    href="{{ route(module_release_2_meta('kebab').'.admin.users.show', $user->slug) }}"
                                    class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded"
                                >
                                    <span class="icon-[proicons--eye] size-5"></span>
                                    <span class="ml-2 text-xs font-medium">View</span>
                                </a>
                                <a 
                                    href="{{ route(module_release_2_meta('kebab').'.admin.users.edit', $user->slug) }}"
                                    class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded"
                                >
                                    <span class="icon-[lucide--edit] size-4.5"></span>
                                    <span class="ml-2 text-xs font-medium">Edit</span>
                                </a>
                                <a 
                                    @click="$dispatch('open-modal', { name: 'modal-status', selectedId: '{{ $user->slug }}', selectedName: '{{ $user->name }}', selectedStatus: '{{ $user->status }}' })"
                                    href="#"
                                    class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded"
                                >
                                    <span class="icon-[mdi--lock-outline] size-5"></span>
                                    <span class="ml-2 text-xs font-medium">Update Status</span>
                                </a>

                                <div class="border-t border-gray-200 dark:border-gray-700 pt-1 mt-1">
                                    <a 
                                        @click="$dispatch('open-modal', { name: 'modal-delete', selectedId: '{{ $user->slug }}', selectedName: '{{ $user->name }}' })"
                                        href="#"
                                        class="flex items-center w-full px-4 py-2 text-sm text-red-700 dark:text-red-300 hover:bg-red-100 dark:hover:bg-red-700 rounded"
                                    >
                                        <span class="icon-[uil--trash-alt] size-5"></span>
                                        <span class="ml-2 text-xs font-medium">Delete</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </x-table-default>
    </div>

    {{-- Export Modal --}}
    <x-modal 
        name="modal-export" 
        title="Export Data User" 
        maxWidth="xl"
    >
        <div class="pb-4 space-y-4">

            <p class="text-sm text-gray-600 dark:text-gray-400">
                Pilih format file yang ingin digunakan untuk mengekspor data pengguna. 
            </p>

            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                <a 
                    href="{{ route(module_release_2_meta('kebab').'.admin.users.export', ['type' => 'xlsx']) }}" 
                    class="bg-white dark:bg-slate-900 flex items-center justify-center border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800 transition"
                >
                    <span class="icon-[mdi--microsoft-excel] text-green-600 mr-2"></span>
                    XLSX
                </a>

                <a 
                    href="{{ route(module_release_2_meta('kebab').'.admin.users.export', ['type' => 'csv']) }}" 
                    class="bg-white dark:bg-slate-900 flex items-center justify-center border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800 transition"
                >
                    <span class="icon-[mdi--file-delimited] text-blue-600 mr-2"></span>
                    CSV
                </a>

                <a 
                    href="{{ route(module_release_2_meta('kebab').'.admin.users.export', ['type' => 'pdf']) }}" 
                    class="bg-white dark:bg-slate-900 flex items-center justify-center border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800 transition"
                >
                    <span class="icon-[mdi--file-pdf-box] text-red-600 mr-2"></span>
                    PDF
                </a>

                <a 
                    href="{{ route(module_release_2_meta('kebab').'.admin.users.export', ['type' => 'docx']) }}" 
                    class="bg-white dark:bg-slate-900 flex items-center justify-center border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800 transition"
                >
                    <span class="icon-[mdi--microsoft-word] text-blue-700 mr-2"></span>
                    DOCX
                </a>

                <a 
                    href="{{ route(module_release_2_meta('kebab').'.admin.users.export', ['type' => 'json']) }}" 
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

    {{-- Import Modal --}}
    <x-modal name="modal-import" title="Import Data User" maxWidth="2xl">
        <form 
            action="{{ route(module_release_2_meta('kebab').'.admin.users.import') }}" 
            method="POST" 
            enctype="multipart/form-data" 
        >
            <div class="pb-6 space-y-4">
                @csrf

               <div>
                    <x-form.input-document 
                        name="file" 
                        label="Upload file users (CSV, XLSX, JSON, PDF)" 
                        required 
                    />
                </div>
            </div>
            
            <div class="flex justify-end">
                <x-link 
                    tag="button" 
                    type="submit" 
                    size="md"
                    class="cursor-pointer ui-btn-solid"
                    intent="primary"  
                >
                    <x-slot:iconBefore>
                        <span class="icon-[lets-icons--import-light] text-lg"></span>
                    </x-slot:iconBefore>
                    @lang('Import')
                </x-link>
            </div>
        </form>
    </x-modal>

    {{-- Update Status Modal --}}
    <x-modal 
        name="modal-status" 
        title="Update Status Confirmation" 
        :static="true" 
        maxWidth="md"
    >
        <div 
            x-data="{ 
                selectedId: null,
                selectedName: null,
                selectedStatus: null,
            }"
            x-on:open-modal.window="
                if ($event.detail.name === 'modal-status') {
                    selectedId = $event.detail.selectedId
                    selectedName = $event.detail.selectedName
                    selectedStatus = $event.detail.selectedStatus
                }
            "
        >
            {{-- Body --}}
            <div class="flex flex-col items-center text-center px-6 pb-6">
                <span class="icon-[mage--exclamation-triangle] size-16 text-warning mb-6"></span>

                <p class="text-gray-700 text-sm leading-relaxed max-w-md">
                    Changing the status will control whether this API client can access the system.
                </p>

                <p class="text-gray-700 text-sm leading-relaxed max-w-md mt-4">
                    <template x-if="selectedStatus == 'active'">
                        <span>
                            Are you sure you want to 
                            <span class="font-bold text-danger">deactivate</span> API client 
                            <span class="font-bold" x-text="selectedName"></span>?
                        </span>
                    </template>
                    <template x-if="selectedStatus == 'inactive'">
                        <span>
                            Are you sure you want to 
                            <span class="font-bold text-success">activate</span> API client 
                            <span class="font-bold" x-text="selectedName"></span>?
                        </span>
                    </template>
                </p>
            </div>
                        
            {{-- Footer --}}
            <div class="w-full flex justify-end gap-3 mt-4">
                <x-link 
                    intent="secondary"
                    size="md"
                    href="#" 
                    @click="$dispatch('close-modal')"
                >
                    Cancel
                </x-link>

                <form 
                    x-bind:action="`{{ route(module_release_2_meta('kebab').'.admin.users.status.update', ['user' => ':id']) }}`.replace(':id', selectedId)" 
                    method="POST"
                >
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="status" :value="selectedStatus == 'active' ? 0 : 1">

                    <template x-if="selectedStatus == 'active'">
                        <x-link 
                            type="submit"
                            size="md"
                            class="cursor-pointer"
                            intent="danger"
                        >
                            Yes, Deactivate
                        </x-link>
                    </template>

                    <template x-if="selectedStatus == 'inactive'">
                        <x-link 
                            type="submit"
                            size="md"
                            class="cursor-pointer"
                            intent="success"
                        >
                            Yes, Activate
                        </x-link>
                    </template>
                </form>
            </div>
        </div>
    </x-modal>

    {{-- Delete Modal --}}
    <x-modal 
        name="modal-delete" 
        title="Delete User Confirmation" 
        :static="true" 
        maxWidth="md"
    >
        <div 
            x-data="{ 
                selectedId: null,
                selectedName: null,
            }"
            x-on:open-modal.window="
                if ($event.detail.name === 'modal-delete') {
                    selectedId = $event.detail.selectedId
                    selectedName = $event.detail.selectedName
                }
            "
        >
            <div class="flex flex-col items-center text-center px-6 pb-6">
                <span class="icon-[mage--exclamation-triangle] size-16 text-danger mb-6"></span>

                <p class="text-gray-700 text-sm leading-relaxed max-w-md">
                    Are you sure you want to delete user 
                    <span class="font-bold text-danger" x-text="selectedName"></span>? 
                    Deleting a user may affect other related records in the system.
                </p>
            </div>

            {{-- Footer --}}
            <div class="w-full flex justify-end gap-3 mt-4">
                <x-link 
                    intent="secondary"
                    size="md"
                    href="#" 
                    @click="$dispatch('close-modal')"
                >
                    Cancel
                </x-link>

                <form 
                    x-bind:action="`{{ route(module_release_2_meta('kebab').'.admin.users.destroy', ['user' => ':id']) }}`.replace(':id', selectedId)" 
                    method="POST"
                >
                    @csrf
                    @method('DELETE')
                    
                    <x-link 
                        class="cursor-pointer"
                        type="submit"
                        size="md" 
                        intent="danger"
                    >
                        Yes, Delete
                    </x-link>
                </form>
            </div>
        </div>
    </x-modal>

</x-module-release-2::admin-layout>
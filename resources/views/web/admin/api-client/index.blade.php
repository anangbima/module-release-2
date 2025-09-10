<x-module-release-2::admin-layout
    :title="__($title)"
    :role="'Admin'"
    :module="__(module_release_2_meta('label'))"
    :desa="config('app.name')"
    :breadcrumbs="$breadcrumbs"
>
    {{-- Description --}}
    <div class="text-sm p-4 bg-gray-100 rounded-lg text-gray-600 dark:text-gray-400 mb-6">
        API Clients are used to generate tokens that allow external applications to access data 
        from this system. Each client has its own credentials and permissions. 
        Create a new client if you need to integrate this system with another application. <span><a class="text-blue-500 hover:text-blue-700" href="#">Lean more</a>  </span>
    </div>

    {{-- Button Add --}}
    <div class="mb-4">
        <x-link 
            intent="primary"
            class="ui-btn-solid rounded-lg" 
            size="md" 
            href="{{ route(module_release_2_meta('kebab').'.admin.api-clients.create') }}"
        >
            <x-slot:iconBefore>
                <span class="icon-[pepicons-pop--plus] text-lg"></span>
            </x-slot:iconBefore>
            New API Client
        </x-link>
    </div>

    {{-- Table Section --}}
    <div>
        <x-table-default
            title="All Clients"
            :headers="[
                'Name', 
                'Api Key',
                'Secret Key',
                'Status',
                'Created At',
            ]"
        >
            @foreach($apiClients as $apiClient)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $apiClient->name }}</td>
                    <td x-data="{ show: false }" class="font-mono">
                        <div class="flex items-center gap-2 w-full justify-between">
                            <span x-show="!show">••••••••••</span>
                            <span x-show="show">{{ $apiClient->api_key }}</span>

                            <button 
                                type="button" 
                                @click="show = !show" 
                                class="text-gray-300 hover:text-gray-700 cursor-pointer"
                            >
                                <span x-show="!show" class="icon-[mdi--eye-outline] size-5"></span>
                                <span x-show="show" class="icon-[mdi--eye-off-outline] size-5"></span>
                            </button>
                        </div>
                    </td>
                    <td x-data="{ show: false }" class="font-mono">
                        <div class="flex items-center gap-2 w-full justify-between">
                            <span x-show="!show">••••••••••</span>
                            <span x-show="show">{{ $apiClient->secret_key }}</span>

                            <button 
                                type="button" 
                                @click="show = !show" 
                                class="text-gray-300 hover:text-gray-700 cursor-pointer"
                            >
                                <span x-show="!show" class="icon-[mdi--eye-outline] size-5"></span>
                                <span x-show="show" class="icon-[mdi--eye-off-outline] size-5"></span>
                            </button>
                        </div>
                    </td>
                    <td>
                        @if ($apiClient->is_active === 1)
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
                    <td>{{ $apiClient->created_at->format('d M Y H:i') }}</td>
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
                                    href="{{ route(module_release_2_meta('kebab').'.admin.api-clients.show', $apiClient->slug) }}"
                                    class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded"
                                >
                                    <span class="icon-[proicons--eye] size-5"></span>
                                    <span class="ml-2 text-xs font-medium">View</span>
                                </a>
                                <a 
                                    @click="$dispatch('open-modal', { name: 'modal-status', selectedId: '{{ $apiClient->slug }}', selectedName: '{{ $apiClient->name }}', selectedStatus: '{{ $apiClient->is_active }}' })"
                                    href="#"
                                    class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded"
                                >
                                    <span class="icon-[mdi--lock-outline] size-5"></span>
                                    <span class="ml-2 text-xs font-medium">Update Status</span>
                                </a>

                                <div class="border-t border-gray-200 dark:border-gray-700 pt-1 mt-1">
                                    <a 
                                        @click="$dispatch('open-modal', { name: 'modal-delete', selectedId: '{{ $apiClient->slug }}', selectedName: '{{ $apiClient->name }}' })"
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

    {{-- Delete API Client Modal --}}
    <x-modal 
        name="modal-delete" 
        title="Delete API Confirmation" 
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
            {{-- Body --}}
            <div class="flex flex-col items-center text-center px-6 pb-6">
                <span class="icon-[mage--exclamation-triangle] size-16 text-danger mb-6"></span>

                <p class="text-gray-700 text-sm leading-relaxed max-w-md">
                    This action cannot be undone and may affect applications that depend on this client.
                </p>

                <p class="text-gray-700 text-sm leading-relaxed max-w-md mt-4">
                    Are you sure you want to delete API client for 
                    <span class="font-bold text-danger" x-text="selectedName"></span>?
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
                    x-bind:action="`{{ route(module_release_2_meta('kebab').'.admin.api-clients.destroy', ['api_client' => ':id']) }}`.replace(':id', selectedId)" 
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
                    <template x-if="selectedStatus == 1">
                        <span>
                            Are you sure you want to 
                            <span class="font-bold text-danger">deactivate</span> API client 
                            <span class="font-bold" x-text="selectedName"></span>?
                        </span>
                    </template>
                    <template x-if="selectedStatus == 0">
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
                    x-bind:action="`{{ route(module_release_2_meta('kebab').'.admin.api-clients.status.update', ['api_client' => ':id']) }}`.replace(':id', selectedId)" 
                    method="POST"
                >
                    @csrf
                    @method('PUT')

                    {{-- toggle status: 1 -> 0, 0 -> 1 --}}
                    <input type="hidden" name="status" :value="selectedStatus == 1 ? 0 : 1">

                    <template x-if="selectedStatus == 1">
                        <x-link 
                            type="submit"
                            size="md"
                            class="cursor-pointer"
                            intent="danger"
                        >
                            Yes, Deactivate
                        </x-link>
                    </template>

                    <template x-if="selectedStatus == 0">
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



</x-module-release-2::admin-layout>
<x-desa-module-template::admin-layout
    :title="__($title)"
    :role="'Admin'"
    :module="__(desa_module_template_meta('label'))"
    :desa="config('app.name')"
    :breadcrumbs="$breadcrumbs"
>
    {{-- Description --}}
    <div class="text-sm p-4 bg-gray-100 rounded-lg text-gray-600 dark:text-gray-400 mb-6">
        Permissions control what actions users can perform within the system. 
        Modifying permissions is a <span class="font-bold">critical and sensitive task</span> 
        that may directly impact system functionality and user responsibilities. 
        Please review carefully before making any changes. 
        <span>
            <a class="text-blue-500 hover:text-blue-700" href="#">
                Learn more
            </a>
        </span>
    </div>

    {{-- Button Add --}}
    <div class="mb-2">
        <x-link 
            intent="primary"
            class="ui-btn-solid rounded-lg" 
            size="md"
            href="{{ route(desa_module_template_meta('kebab').'.admin.permissions.create') }}"
        >
            <x-slot:iconBefore>
                <span class="icon-[pepicons-pop--plus] text-lg"></span>
            </x-slot:iconBefore>
            New Permissions
        </x-link>
    </div>

    {{-- Table Section --}}
    <div>
        <x-table-default
            title="All Permissions"
            :headers="[
                'Name', 
                'Created At',
            ]"
        >
            @foreach($permissions as $permission)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ ucfirst($permission->name) }}</td>
                    <td>{{ $permission->created_at->format('d M Y H:i') }}</td>
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
                                    href="{{ route(desa_module_template_meta('kebab').'.admin.permissions.show', $permission->id) }}"
                                    class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded"
                                >
                                    <span class="icon-[proicons--eye] size-5"></span>
                                    <span class="ml-2 text-xs font-medium">View</span>
                                </a>
                                <a 
                                    href="{{ route(desa_module_template_meta('kebab').'.admin.permissions.edit', $permission->id) }}"
                                    class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded"
                                >
                                    <span class="icon-[lucide--edit] size-4.5"></span>
                                    <span class="ml-2 text-xs font-medium">Edit </span>
                                </a>

                                <div class="border-t border-gray-200 dark:border-gray-700 pt-1 mt-1">
                                    <a 
                                        @click="$dispatch('open-modal', { name: 'modal-delete', selectedId: '{{ $permission->id }}', selectedName: '{{ $permission->name }}' })"
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

    {{-- Delete Permission Modal --}}
    <x-modal 
        name="modal-delete" 
        title="Delete Permission Confirmation" 
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
                    Permissions are critical data. Deleting a permission may affect other related records in the system.
                </p>

                <p class="text-gray-700 text-sm leading-relaxed max-w-md mt-4">
                    Are you sure you want to delete 
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
                    x-bind:action="`{{ route(desa_module_template_meta('kebab').'.admin.permissions.destroy', ['permission' => ':id']) }}`.replace(':id', selectedId)" 
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

</x-desa-module-template::admin-layout>

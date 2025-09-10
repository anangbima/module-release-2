<x-dynamic-component
    :component="'desa-module-template::' . $role . '-layout'"
    :title="__($title)"
    :role="$role"
    :module="__(desa_module_template_meta('label'))"
    :desa="config('app.name')"
    :breadcrumbs="$breadcrumbs"
>
    {{-- Button Mark all as Read --}}
    <div class="flex justify-between items-center mb-6">
        {{-- Filter --}}
        <div class="flex gap-2 items-center mb-4 mt-1">
            <x-link 
                href="{{ route(desa_module_template_meta('kebab').'.'.$role.'.notifications.index', ['filter' => 'all']) }}"
                intent="primary" 
                class="{{ $filter === 'all' ? 'ui-btn-solid' : '' }}" 
                size="md" 
                :active="$filter === 'all'"
            >
                All ({{ $totalAll }})
            </x-link>

            <x-link 
                href="{{ route(desa_module_template_meta('kebab').'.'.$role.'.notifications.index', ['filter' => 'read']) }}"
                intent="primary"
                class="{{ $filter === 'read' ? 'ui-btn-solid' : '' }}" 
                size="md"
                :active="$filter === 'read'"
            >
                Read ({{ $totalRead }})
            </x-link>

            <x-link 
                href="{{ route(desa_module_template_meta('kebab').'.'.$role.'.notifications.index', ['filter' => 'unread']) }}"
                intent="primary"
                class="{{ $filter === 'unread' ? 'ui-btn-solid' : '' }}" 
                size="md"
                :active="$filter === 'unread'"
            >
                Unread ({{ $totalUnread }})
            </x-link>
        </div>

        {{-- Action --}}
        <div class="flex gap-2 items-center">
            {{-- Mark all as read --}}
            <form method="POST" action="{{ route('desa-module-template.'.$role.'.notifications.mark-all-as-read') }}">
                @csrf
                <x-link 
                    class="cursor-pointer"
                    as="button"
                    type="submit"
                    size="sm"
                    intent="secondary"
                >
                    <x-slot:iconBefore>
                        <span class="icon-[mdi--check-all] text-lg text-green-600"></span>
                    </x-slot:iconBefore>
                    Mark All as Read
                </x-link>
            </form>

            {{-- Clear all --}}
            <x-link 
                @click="$dispatch('open-modal', { name: 'modal-delete' })"
                intent="danger"
                size="sm" 
                class="w-full justify-start items-center rounded-lg cursor-pointer"
            >
                <x-slot:iconBefore>
                    <span class="icon-[mynaui--trash] text-lg text-red-600"></span>
                </x-slot:iconBefore>
                Clear All
            </x-link>
        </div>
    </div>

    {{-- List Notification Grouped --}}
    @forelse ($notifications as $section => $items)
        <div class="mb-8 pb-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-sm font-semibold mb-4">
                {{ $section }}
            </h2>

            @forelse ($items as $notification)
                <a 
                    href="{{ route('desa-module-template.'.$role.'.notifications.show', $notification->id) }}" 
                    class="p-4 mb-4 rounded-lg border flex justify-between items-center gap-4 group
                        {{ $notification->read_at 
                            ? 'bg-white dark:bg-transparent hover:bg-gray-50 border-transparent' 
                            : 'bg-gray-100 dark:bg-gray-900 hover:bg-white border-0 border-r-4 border-danger' }}"
                >
                    {{-- Left: Icon + Content --}}
                    <div class="flex gap-4">
                        {{-- Icon --}}
                        <div>
                            @if ($notification->data['type'] == 'success')    
                                <div class="w-8 h-8 bg-green-200 text-green-600 flex justify-center items-center rounded-full">
                                    <span class="icon-[pajamas--check] leading-none"></span>
                                </div>
                            @elseif ($notification->data['type'] == 'warning')
                                <div class="w-8 h-8 bg-yellow-100 text-yellow-600 flex justify-center items-center rounded-full">
                                    <span class="text-lg icon-[ci--warning] leading-none"></span>
                                </div>
                            @elseif ($notification->data['type'] == 'error')
                                <div class="w-8 h-8 bg-red-100 text-red-600 flex justify-center items-center rounded-full">
                                    <span class="text-lg icon-[charm--cross] leading-none"></span>
                                </div>
                            @elseif ($notification->data['type'] == 'info')
                                <div class="w-8 h-8 bg-blue-100 text-blue-600 flex justify-center items-center rounded-full">
                                    <span class="text-lg icon-[humbleicons--info] leading-none"></span>
                                </div>
                            @elseif ($notification->data['type'] == 'announcement')
                                <div class="w-8 h-8 bg-violet-100 text-violet-600 flex justify-center items-center rounded-full">
                                    <span class="text-lg icon-[streamline-plump--announcement-megaphone] leading-none"></span>
                                </div>
                            @else
                                <div class="w-8 h-8 bg-gray-200 text-gray-700 flex justify-center items-center rounded-full">
                                    <span class="text-lg icon-[la--dot-circle] leading-none"></span>
                                </div>
                            @endif
                        </div>

                        {{-- Content --}}
                        <div>
                            <div class="flex gap-2">
                                <span class="font-bold">{{ $notification->data['title'] }}</span>
                                <span class="text-gray-400">
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>
                            </div>

                            <div class="mt-1 text-sm text-gray-700 dark:text-gray-300">
                                @php
                                    $msg = $notification->data['message'];
                                    $shortMsg = strlen($msg) > 250 ? substr($msg, 0, 250) . '...' : $msg;
                                @endphp
                                {{ $shortMsg }}
                            </div>
                        </div>
                    </div>

                    {{-- Right: Action Buttons --}}
                    <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition">
                        <div>
                            @if (!$notification->read_at) 
                                <form method="POST" action="{{ route('desa-module-template.'.$role.'.notifications.mark-as-read', $notification->id) }}">
                                    @csrf
        
                                    <button 
                                        type="submit"
                                        class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-500  transition cursor-pointer"
                                        title="Mark as Read"
                                    >
                                        <span class="icon-[mdi--check] text-lg"></span>
                                    </button>
                                </form>
                            @endif
                        </div>

                        <div>
                            <form method="POST" action="{{ route('desa-module-template.'.$role.'.notifications.destroy', $notification->id) }}">
                                @csrf
                                @method('DELETE')
    
                                <button 
                                    type="submit"
                                    class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-red-50 text-red-500 transition cursor-pointer"
                                    title="Delete Notification"
                                >
                                    <span class="icon-[mynaui--trash] text-lg"></span>
                                </button>
                            </form>
                        </div>
                    </div>
                </a>
            @empty
                <div class="text-center text-gray-400 py-6">
                    No notifications in this section.
                </div>
            @endforelse
        </div>
    @empty
        <div class="flex flex-col justify-center items-center min-h-[600px] text-center text-gray-400 px-4">
            <span class="text-6xl mb-4"><span class="icon-[basil--notification-off-outline]"></span></span>
            <h3 class="text-xl font-semibold mb-2">You're all caught up!</h3>
            <p class="text-sm max-w-xs">
                There are no notifications at the moment. Any updates or changes will appear here.
            </p>
        </div>
    @endforelse

    {{-- Clear all modal --}}
    <x-modal 
        name="modal-delete" 
        title="Clear All Notifications" 
        :static="true" 
        maxWidth="md"
    >
        <div>
            {{-- Body --}}
            <div class="flex flex-col items-center text-center px-6 pb-6">
                <span class="icon-[mage--exclamation-triangle] size-16 text-danger mb-6"></span>

                <p class="text-gray-700 text-sm leading-relaxed max-w-md mt-4">
                    Are you sure you want to clear all notifications? 
                    This action cannot be undone.
                    <span class="font-bold text-danger" x-text="selectedName"></span>
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

                <form method="POST" action="{{ route('desa-module-template.'.$role.'.notifications.clear-all') }}">
                    @csrf
                    
                    <x-link 
                        class="cursor-pointer"
                        type="submit"
                        size="md" 
                        intent="danger"
                    >
                        Clear All
                    </x-link>
                </form>
            </div>
        </div>
    </x-modal>
    
</x-dynamic-component>

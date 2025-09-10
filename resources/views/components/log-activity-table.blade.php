<div class="">
    <x-table-default
        title="All my log activity"
        :headers="[
            'User',
            'Action',
            'Description',
            'Logged At',
        ]"
    >
        @foreach($logs as $log)
            <tr class="h-18">
                <td>{{ $loop->iteration }}</td>
                <td>
                    <div class="flex items-center gap-5">
                        <img
                            src="{{ $log->user->profile_image_url ?? asset('assets/default-profile.jpg') }}"
                            alt="{{ $log->user->name }}"
                            class="w-10 h-10 rounded-full object-cover ring-1 ring-gray-200 dark:ring-gray-800"
                        >
                        <div class="flex flex-col">
                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">
                                {{ $log->user->name }}
                            </span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $log->user->email }}
                            </span>
                        </div>
                    </div>
                </td>
                <td>
                    @php
                        $color = module_release_2_action_color($log->action);
                    @endphp

                    <x-badge 
                        text="{{ module_release_2_format_action($log->action) }}" 
                        size="sm" 
                        color="{{ $color }}"  />
                </td>
                <td class="max-w-xs truncate" title="{{ $log->description }}">
                    {{ $log->description }}
                </td>
                <td>{{ $log->logged_at->diffForHumans() }}</td>
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
                                href="{{ route(module_release_2_meta('kebab').'.'.$role.'.logs.show', $log->id) }}"
                                class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded"
                            >
                                <span class="icon-[proicons--eye] size-5"></span>
                                <span class="ml-2 text-xs font-medium">View</span>
                            </a>
                        </div>
                    </div>
                </td>
            </tr>
        @endforeach
    </x-table-default>
</div>
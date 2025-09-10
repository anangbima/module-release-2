<x-dynamic-component
    :component="'desa-module-template::' . $role . '-layout'"
    :title="__($title)"
    :role="$role"
    :module="__(desa_module_template_meta('label'))"
    :desa="config('app.name')"
    :breadcrumbs="$breadcrumbs"
>
    <div class="w-full">
        <div class="flex items-center justify-between">
            <div class="flex gap-4 items-center">
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

                <div>
                    <div class="font-bold">
                        {{ $notification->data['title'] }}
                    </div>
                    <div class="text-gray-400">
                        {{ $notification->created_at->diffForHumans() }}
                    </div>
                </div>
            </div>

            <div>
                <form method="POST" action="{{ route('desa-module-template.'.$role.'.notifications.destroy', $notification->id) }}">
                    @csrf
                    @method('DELETE')

                    <x-link
                        class="cursor-pointer"
                        type="submit"
                        size="md" 
                        intent="danger"
                    >
                        <x-slot:iconBefore>
                            <span class="icon-[mynaui--trash] text-lg"></span>
                        </x-slot:iconBefore>
                        Delete
                    </x-link>
                </form>
            </div>
        </div>

        <div class="mt-4 bg-gray-50 dark:bg-gray-900 p-4 rounded-lg leading-relaxed min-h-[400px]">
            <div class="mb-4">
                {{ $notification->data['message'] }}
            </div>

            <div class="flex justify-center ">
            </div>
        </div>

        <div class="mt-4 flex items-center justify-between">
            <div>
                <x-link
                    :intent="'ghost-border'" 
                    href="{{ route(desa_module_template_meta('kebab').'.'.$role.'.notifications.index') }}"
                >
                    Back
                </x-link>
            </div>
                
            <div>
                @if (!empty($notification->data['action_url']))
                    <x-link
                        :intent="'primary'" 
                        href="{{ url($notification->data['action_url']) }}"
                    >
                        See More
                        <x-slot:iconAfter>
                            <span class="icon-[mingcute--arrows-right-line]"></span>
                        </x-slot:iconAfter>
                    </x-link>
                @endif
            </div>
        </div>

    </div>    
</x-dynamic-component>
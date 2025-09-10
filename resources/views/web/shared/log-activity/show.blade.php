<x-dynamic-component
    :component="'desa-module-template::' . $role . '-layout'"
    :title="__($title)"
    :role="$role"
    :module="__(desa_module_template_meta('label'))"
    :desa="config('app.name')"
    :breadcrumbs="$breadcrumbs"
>
    <div class="flex justify-center">
        <div class="w-full space-y-8 pb-8 ">
            {{-- Profil & Action --}}
            <div class="flex justify-between items-center">
                {{-- Profile --}}
                <div class="flex items-center gap-4 mb-4">
                    <img
                        src="{{ $log->user->profile_image_url ?? asset('assets/default-profile.jpg') }}"
                        alt="{{ $log->user->name }}"
                        class="w-10 h-10 rounded-full object-cover ring-1 ring-gray-200 dark:ring-gray-800"
                    >
                    <div>
                        <p class="text-sm font-semibold text-gray-700 dark:text-gray-200">
                            {{ $log->user->name }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            {{ $log->user->email }}
                        </p>
                    </div>
                </div>
                
                {{-- Action --}}
                <div>
                    <x-badge 
                        text="{{ desa_module_template_format_action($log->action) }}" 
                        size="sm" 
                        color="{{ desa_module_template_action_color($log->action) }}" 
                    />
                </div>
            </div>

            {{-- Information Device --}}
            <div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-8">
                    {{-- Ip Address --}}
                    <div class="mb-4">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">IP Address</p>
                        <p class="text-sm font-semibold text-gray-700 dark:text-gray-200">{{ $log->ip_address ?? '-' }}</p>
                    </div>

                    {{-- Logged At --}}
                    <div class="mb-4">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Logged At</p>
                        <p class="text-sm font-semibold text-gray-700 dark:text-gray-200">{{ $log->logged_at->format('d M Y, H:i:s') }} ({{ $log->logged_at->diffForHumans() }})</p>
                    </div>

                    {{-- User Agent --}}
                    <div class="mb-4">
                        <p class="mb-2 text-xs text-gray-500 dark:text-gray-400">User Agent</p>
                        <p class="text-sm font-semibold text-gray-700 dark:text-gray-200 break-words">{{ $log->user_agent ?? '-' }}</p>
                    </div>
                </div>
            </div>

            {{-- Description --}}
            <div class="mb-4">
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Description</p>
                <div class="bg-gray-100 p-4 rounded-lg">
                    <p class="text-sm text-gray-700 dark:text-gray-200">{{ $log->description ?? '-' }}</p>
                </div>
            </div>

            {{-- Before After --}}
            <div class="flex-row lg:flex gap-4 mb-4">
                {{-- Before --}}
                <div class="w-full lg:w-1/2">
                    <p class="text-xs text-gray-500 dark:text-gray-400">Data Before</p>
                    <pre class="text-xs bg-gray-100 dark:bg-gray-800 p-4 rounded-lg mt-2 whitespace-pre-wrap break-all">
{{ json_encode(json_decode($log->data_before, true), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) ?? '-' }}
</pre>
                </div>
                {{-- After --}}
                <div class="w-full lg:w-1/2">
                    <p class="text-xs text-gray-500 dark:text-gray-400">Data After</p>
                    <pre class="text-xs bg-gray-100 dark:bg-gray-800 p-4 rounded-lg mt-2 whitespace-pre-wrap break-all">
{{ json_encode(json_decode($log->data_after, true), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) ?? '-' }}
</pre>
                </div>
            </div>
        </div>
    </div>

</x-dynamic-component>
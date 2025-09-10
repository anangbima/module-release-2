<x-desa-module-template::admin-layout
    :title="__($title)"
    :role="'Admin'"
    :module="__(desa_module_template_meta('label'))"
    :desa="config('app.name')"
    :breadcrumbs="$breadcrumbs"
>
    {{-- Card --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-8 mt-2">
        {{-- Total users card --}}
        <a href="{{ route(desa_module_template_meta('kebab').'.admin.users.index') }}"
        class="block transition hover:text-info">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-500">Total Users</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 hover:text-info">
                        {{ $totalUsers }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-info-dark dark:bg-info-light rounded-lg flex items-center justify-center">
                    <span class="icon-[heroicons--user-group] text-white text-2xl"></span>
                </div>
            </div>
        </a>

        {{-- Total orders --}}
        <a href="#"
        class="block transition hover:text-violet">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-500">Total Orders</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 hover:text-violet">
                        8,765
                    </p>
                    <p class="text-sm text-violet">+8% from last month</p>
                </div>
                <div class="w-12 h-12 bg-violet dark:bg-violet-light rounded-lg flex items-center justify-center">
                    <i data-lucide="shopping-cart" class="w-6 h-6 text-white"></i>
                </div>
            </div>
        </a>

        {{-- Total Revenue --}}
        <a href="#"
        class="block transition hover:text-danger">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-500">Revenue</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 hover:text-danger">
                        $54,321
                    </p>
                    <p class="text-sm text-danger">-2% from last month</p>
                </div>
                <div class="w-12 h-12 bg-danger dark:bg-danger-light rounded-lg flex items-center justify-center">
                    <i data-lucide="dollar-sign" class="w-6 h-6 text-white"></i>
                </div>
            </div>
        </a>

        {{-- Total product --}}
        <a href="#"
        class="block transition hover:text-success">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-500">Products</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 hover:text-success">
                        2,468
                    </p>
                    <p class="text-sm text-success">+15% from last month</p>
                </div>
                <div class="w-12 h-12 bg-success dark:bg-success-light rounded-lg flex items-center justify-center">
                    <i data-lucide="package" class="w-6 h-6 text-white"></i>
                </div>
            </div>
        </a>
    </div>

    {{-- Chart & Recent Activities --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mt-4">
        {{-- Left: Chart --}}
        <div class="lg:col-span-2">
            <h2 class="font-semibold mb-4">Statistics</h2>
            {{-- <canvas id="statsChart" class="w-full h-64"></canvas> --}}
        </div>

        {{-- Right: Recent Activities --}}
        <div class="lg:col-span-1">
            <div class="flex justify-between items-center">
                <h2 class="font-semibold">Recent Activity</h2>
                <a class="text-xs" href="{{ route(desa_module_template_meta('kebab').'.admin.logs.index') }}">
                    View All
                </a>
            </div>

            <div class="mt-4">
                @forelse ($recentActivity as $log)    
                    <div class="py-3 border-b border-gray-100 dark:border-gray-800 last:border-0">
                        <div class="flex justify-between items-start gap-4">
                            {{-- Left: user + activity --}}
                            <div class="flex gap-3">
                                <div class="w-8 h-8 aspect-square">
                                    <img
                                        src="{{ $log->user->profile_image_url ?? asset('assets/default-profile.jpg') }}"
                                        alt="{{ $log->user->name }}"
                                        class="w-full h-full rounded-full object-cover ring-1 ring-gray-200 dark:ring-gray-800"
                                    >
                                </div>

                                <div class="text-sm leading-relaxed">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="font-semibold text-gray-800 dark:text-gray-200">
                                            {{ $log->user->name }}
                                        </span>

                                        @php
                                            $color = desa_module_template_action_color($log->action);
                                        @endphp

                                        <x-badge 
                                            text="{{ desa_module_template_format_action($log->action) }}" 
                                            size="xs" 
                                            color="{{ $color }}"  
                                        />
                                    </div>

                                    <div class="text-gray-600 dark:text-gray-400 text-xs mt-1">
                                        {{ $log->description }}
                                    </div>
                                </div>
                            </div>

                            <div class="shrink-0">
                                <span class="text-xs text-gray-500 whitespace-nowrap">
                                    {{ $log->logged_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="min-h-[200px] flex items-center justify-center text-gray-500 text-sm">
                        There is no recent activity yet
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Inject Chart.js --}}
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                const ctx = document.getElementById('statsChart').getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                        datasets: [{
                            label: 'Users',
                            data: [120, 190, 300, 500, 200, 300, 450],
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59, 130, 246, 0.2)',
                            fill: true,
                            tension: 0.4
                        }, {
                            label: 'Orders',
                            data: [80, 150, 260, 400, 180, 240, 390],
                            borderColor: '#8b5cf6',
                            backgroundColor: 'rgba(139, 92, 246, 0.2)',
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        animation: false,
                        plugins: {
                            legend: {
                                labels: { color: '#6b7280' }
                            }
                        },
                        scales: {
                            x: {
                                ticks: { color: '#6b7280' },
                                grid: { color: '#e5e7eb' }
                            },
                            y: {
                                ticks: { color: '#6b7280' },
                                grid: { color: '#e5e7eb' }
                            }
                        }
                    }
                });
            });
        </script>
    @endpush
    

</x-desa-module-template::admin-layout>

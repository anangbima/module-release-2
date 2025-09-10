@extends('layouts.core-layout')
@section('content')

{{-- Importants --}}
@vite([
    'modules/desa-module-template/resources/js/app.js',
    'modules/desa-module-template/resources/css/app.css',
])

{{-- Important --}}
<meta 
    name="user-id" 
    content="{{ Auth::guard(desa_module_template_meta('snake').'_web')->user()->id }}"
>

<div 
    x-data="sidebar" 
    @resize.window="isDesktop = (window.innerWidth >= 1024)" 
    @keydown.escape.window="isMobileMenuOpen = false" 
    class="bg-inherit font-sans h-screen flex flex-col"
>
    <div class="flex flex-1 min-h-0 overflow-hidden">
        @php
            $currentRoute = Route::currentRouteName();

            $menuItems = [
                [
                    'label' => 'Dashboard',
                    'url' => route(desa_module_template_meta('kebab').'.user.index'),
                    'route' => desa_module_template_meta('kebab').'.user.index',
                    'icon' => '<span class="icon-[mage--dashboard-chart] text-xl flex-shrink-0"></span>'
                ],
                [
                    'type' => 'divider',
                    'label' => 'System'
                ],
                [
                    'label' => 'My Log',
                    'url' => route(desa_module_template_meta('kebab').'.user.logs.index'),
                    'route' => desa_module_template_meta('kebab').'.user.logs.*',
                    'icon' => '<span class="icon-[iconamoon--history-fill] text-lg flex-shrink-0"></span>'
                ],
            ];
        @endphp

        {{-- Sidebar component --}}
        <x-partials.component.sidebar :menuItems="$menuItems" />

        {{-- Main content --}}
        <div 
            x-cloak class="duration-200 ease-in-out flex flex-col bg-white dark:bg-gray-930 flex-1 w-full overflow-hidden"
            :class="isSidebarCollapsed ? 'lg:ml-16' : 'lg:ml-[260px]'"
        >
            {{-- Navbar content --}}
            <x-partials.component.navbar 
                :title="$title" 
                :breadcrumbs="$breadcrumbs"
            >
                {{-- Action navbar --}}
                <x-slot:actions>

                    {{-- Theme button --}}
                    <x-theme-button />

                    {{-- Notification button --}}
                    <x-notification-button 
                        :urlListNotification="route('quick-notifications')" 
                        :urlAllNotification="route(desa_module_template_meta('kebab').'.user.notifications.index')" 
                    />

                    {{-- Profile button --}}
                    <x-profile-button 
                        :name="desa_module_template_auth_user()->name" 
                        :email="desa_module_template_auth_user()->email" 
                        :src_url="desa_module_template_auth_user()->profile_image_url ?? asset('assets/default-profile.jpg')"
                        :account_url="route(desa_module_template_meta('kebab').'.user.profile.index')" 
                        :setting_url="'#'" 
                        :logout_url="route(desa_module_template_meta('kebab').'.logout')" 
                    />
                </x-slot:actions>
            </x-partials.component.navbar>

            {{-- Main content --}}
            <main class="flex-1 flex flex-col min-h-0 overflow-y-auto custom-scrollbar transition-[margin-left,scrollbar-color] px-2 sm:px-4 md:px-6 lg:px-8">
                {{ $slot }}
                <x-partials.component.footer />
            </main>
        </div>
    </div>
    
    <div 
        x-show=" isMobileMenuOpen" 
        @click="isMobileMenuOpen = false" 
        x-init="$watch('isMobileMenuOpen', value => { document.body.style.overflow = value ? 'hidden' : ''; })" 
        class="fixed inset-0 z-30 bg-black/60 backdrop-blur-sm lg:hidden" 
        x-transition.opacity
    ></div>

    @stack('scripts')
</div>

@endsection


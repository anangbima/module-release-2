@extends('layouts.core-layout')
@section('content')

{{-- Navbar --}}
<nav @scroll.window="isScrolled = (window.scrollY > 50)" :class="{ 'scrolled': isScrolled }" class="fixed top-0 w-full z-50 glass-nav">
    <div class="container mx-auto px-6 py-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3 animate-[fadeInDown_0.8s_ease-out]">
                <x-logo />
            </div>

            <div class="hidden lg:flex items-center space-x-8 animate-[fadeInDown_0.8s_ease-out]">
                <a href="#beranda" class="nav-link">@lang('Home')</a>
                <a href="#tentang" class="nav-link">@lang('About Us')</a>
                <a href="#fitur" class="nav-link">@lang('Feature')</a>
                <a href="#panduan" class="nav-link">@lang('Guide')</a>
                <a href="#kontak" class="nav-link">@lang('Contact')</a>
            </div>

            <div class="flex items-center gap-4 animate-[fadeInDown_0.8s_ease-out]">
                <x-theme-button />

                <button class="lg:hidden text-dark dark:text-light text-2xl hover:text-success transition-colors" @click="toggleMobileMenu()">
                    <span x-show="isMobileMenuOpen" x-cloak class="icon-[material-symbols--close]"></span>
                    <span x-show="!isMobileMenuOpen" class="icon-[material-symbols--menu]"></span>
                </button>

                <x-link href="{{ route(desa_module_template_meta('kebab').'.login') }}" intent="primary" size="base" class="hidden lg:flex">
                    <x-slot:iconBefore>
                        <span class="icon-[material-symbols--login] scale-[1.5]"></span>
                    </x-slot:iconBefore>
                    @lang('Login')
                </x-link>
            </div>
        </div>

        <div x-show="isMobileMenuOpen" x-cloak @click.away="isMobileMenuOpen = false" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform -translate-y-4" class="lg:hidden mt-4">
            <div class="flex flex-col space-y-3 pb-4 glass-card-light rounded-xl p-4 mt-4">
                <a @click="isMobileMenuOpen = false" href="#beranda" class="nav-link-mobile">@lang('Home')</a>
                <a @click="isMobileMenuOpen = false" href="#tentang" class="nav-link-mobile">@lang('About Us')</a>
                <a @click="isMobileMenuOpen = false" href="#fitur" class="nav-link-mobile">@lang('Feature')</a>
                <a @click="isMobileMenuOpen = false" href="#panduan" class="nav-link-mobile">@lang('Guide')</a>
                <a @click="isMobileMenuOpen = false" href="#kontak" class="nav-link-mobile">@lang('Contact')</a>
            </div>
        </div>
    </div>
</nav>

{{ $slot }}


@endsection
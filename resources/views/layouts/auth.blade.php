@extends('layouts.core-layout')

@section('content')
<section class="bg-inherit min-h-screen flex items-center relative overflow-hidden">
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute opacity-10 animate-[float_8s_ease-in-out_infinite] top-[20%] left-[10%] [animation-delay:0s] w-32 h-32 bg-success rounded-full"></div>
        <div class="absolute opacity-10 animate-[float_8s_ease-in-out_infinite] top-[60%] right-[10%] [animation-delay:2s] w-24 h-24 bg-accent rounded-full"></div>
        <div class="absolute opacity-10 animate-[float_8s_ease-in-out_infinite] bottom-[20%] left-[20%] [animation-delay:4s] w-40 h-40 bg-secondary rounded-full"></div>
    </div>

    <div class="absolute top-0 w-full">
        <div class="flex justify-between items-center py-6 pl-0 lg:pl-4 pr-8 animate-fade-in-up">
            <div class="">
                <x-link 
                    href="{{ route(desa_module_template_meta('kebab').'.index') }}" 
                    intent="ghost" 
                    size="base"
                >
                    <x-slot:iconBefore>
                        <span class="icon-[basil--arrow-left-outline] text-lg"></span>
                    </x-slot:iconBefore>
                    @lang('Back to home')
                </x-link>
            </div>
            <div class="">
                <x-theme-button />
            </div>
        </div>
    </div>

    <div class="container mx-auto px-6 relative z-10">
        <div class="flex items-center justify-center min-h-screen py-12 ">
            <div class="">
                <div class="text-center mb-8 animate-fade-in-down">
                    <div class="flex items-center justify-center space-x-3 mb-6">
                        <div>
                            {{-- <span class="font-bold text-lg tracking-wide uppercase drop-shadow-sm whitespace-nowrap bg-dark dark:text-light bg-clip-text text-transparent ">
                                Welcome to
                            </span> --}}
                            <span class="font-bold text-lg tracking-wide uppercase drop-shadow-sm whitespace-nowrap bg-gradient-to-r from-success via-green-400 to-info bg-clip-text text-transparent ">
                                Desa Digital
                            </span>
                        </div>
                    </div>
                </div>

                {{ $slot }}

            </div>
        </div>
    </div>
</section>
@endsection

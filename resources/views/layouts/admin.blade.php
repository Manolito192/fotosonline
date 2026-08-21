<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ __('Administration') }} | {{ config('app.name') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 flex">
            <aside class="hidden md:flex w-64 flex-col bg-gray-900 text-gray-200">
                <div class="px-6 py-6">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                        <x-application-logo class="block h-9 w-auto fill-current text-white" />
                        <span class="text-lg font-bold text-white">{{ config('app.name') }}</span>
                    </a>
                </div>

                <nav class="flex-1 px-3 space-y-1">
                    <a href="{{ route('admin.dashboard') }}"
                       class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                        {{ __('Dashboard') }}
                    </a>
                    <a href="{{ route('admin.photos.index') }}"
                       class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.photos.*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                        {{ __('Photos') }}
                    </a>
                    <a href="{{ route('admin.categories.index') }}"
                       class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.categories.*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                        {{ __('Categories') }}
                    </a>
                    <a href="{{ route('admin.orders.index') }}"
                       class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.orders.*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                        {{ __('Orders') }}
                    </a>
                    <a href="{{ route('admin.tags.index') }}"
                       class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.tags.*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                        {{ __('Tags') }}
                    </a>
                    <a href="{{ route('admin.coupons.index') }}"
                       class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.coupons.*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                        {{ __('Coupons') }}
                    </a>
                    <a href="{{ route('admin.collections.index') }}"
                       class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.collections.*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                        {{ __('Collections') }}
                    </a>
                    <a href="{{ route('admin.reviews.index') }}"
                       class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.reviews.*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                        {{ __('Reviews') }}
                    </a>
                </nav>

                <div class="px-3 py-4 border-t border-gray-800 space-y-1">
                    <a href="{{ route('home') }}" class="block px-3 py-2 rounded-lg text-sm font-medium text-gray-400 hover:bg-gray-800 hover:text-white">
                        &larr; {{ __('Back to store') }}
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-3 py-2 rounded-lg text-sm font-medium text-gray-400 hover:bg-gray-800 hover:text-white">
                            {{ __('Logout') }}
                        </button>
                    </form>
                </div>
            </aside>

            <div class="flex-1 flex flex-col min-w-0">
                <header class="bg-white shadow-sm md:hidden">
                    <div class="px-4 py-3 flex items-center justify-between">
                        <a href="{{ route('admin.dashboard') }}" class="font-bold text-gray-900">{{ __('Administration') }}</a>
                        <a href="{{ route('home') }}" class="text-sm text-indigo-600">{{ __('Back to store') }}</a>
                    </div>
                    <nav class="px-4 pb-3 flex gap-4 text-sm text-gray-600 overflow-x-auto">
                        <a href="{{ route('admin.dashboard') }}" class="shrink-0 {{ request()->routeIs('admin.dashboard') ? 'text-indigo-600 font-medium' : '' }}">{{ __('Dashboard') }}</a>
                        <a href="{{ route('admin.photos.index') }}" class="shrink-0 {{ request()->routeIs('admin.photos.*') ? 'text-indigo-600 font-medium' : '' }}">{{ __('Photos') }}</a>
                        <a href="{{ route('admin.categories.index') }}" class="shrink-0 {{ request()->routeIs('admin.categories.*') ? 'text-indigo-600 font-medium' : '' }}">{{ __('Categories') }}</a>
                        <a href="{{ route('admin.orders.index') }}" class="shrink-0 {{ request()->routeIs('admin.orders.*') ? 'text-indigo-600 font-medium' : '' }}">{{ __('Orders') }}</a>
                        <a href="{{ route('admin.tags.index') }}" class="shrink-0 {{ request()->routeIs('admin.tags.*') ? 'text-indigo-600 font-medium' : '' }}">{{ __('Tags') }}</a>
                        <a href="{{ route('admin.coupons.index') }}" class="shrink-0 {{ request()->routeIs('admin.coupons.*') ? 'text-indigo-600 font-medium' : '' }}">{{ __('Coupons') }}</a>
                        <a href="{{ route('admin.collections.index') }}" class="shrink-0 {{ request()->routeIs('admin.collections.*') ? 'text-indigo-600 font-medium' : '' }}">{{ __('Collections') }}</a>
                        <a href="{{ route('admin.reviews.index') }}" class="shrink-0 {{ request()->routeIs('admin.reviews.*') ? 'text-indigo-600 font-medium' : '' }}">{{ __('Reviews') }}</a>
                    </nav>
                </header>

                @if (session('success'))
                    <div class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 mt-4">
                        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">
                            {{ session('success') }}
                        </div>
                    </div>
                @endif

                <main class="flex-1 p-4 sm:p-6 lg:p-8">
                    <div class="max-w-7xl mx-auto">
                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>
    </body>
</html>

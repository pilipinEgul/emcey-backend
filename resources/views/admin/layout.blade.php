<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') · Emcey Brows</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-cream-100 text-ink-900">
    <div x-data="{ open: false }" class="flex min-h-screen">
        {{-- Sidebar --}}
        <aside
            x-cloak
            :class="open ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
            class="fixed inset-y-0 left-0 z-40 flex w-72 flex-col border-r border-nude-100 bg-cream-50/95 backdrop-blur-md transform transition-transform duration-300 lg:static lg:translate-x-0"
        >
            <div class="flex items-center justify-between gap-3 px-6 py-5 border-b border-nude-100">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                    <span class="grid h-9 w-9 place-items-center rounded-full bg-gradient-to-br from-terracotta-400 to-gold-500 text-white shadow-sm">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                            <path d="M3 14c4-3 8-3 12-1s5 3 6 2c-2 4-7 6-12 5S3 14 3 14Z" fill="currentColor"/>
                        </svg>
                    </span>
                    <div class="leading-tight">
                        <div class="font-semibold text-ink-900">Emcey Brows</div>
                        <div class="text-[10px] uppercase tracking-[0.32em] text-terracotta-500">Admin</div>
                    </div>
                </a>
                <button type="button" @click="open = false" class="lg:hidden rounded-full border border-nude-200 p-1.5 text-ink-500">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M6 6l12 12M6 18L18 6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                </button>
            </div>

            <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-0.5">
                @php
                    $is = fn (string $name) => request()->routeIs($name) ? 'is-active' : '';
                @endphp

                <a href="{{ route('admin.dashboard') }}" class="nav-item {{ $is('admin.dashboard') }}">
                    <span>◈</span> Dashboard
                </a>

                <div class="nav-section">Content</div>
                <a href="{{ route('admin.services.index') }}" class="nav-item {{ $is('admin.services.*') }}"><span>✦</span> Services</a>
                <a href="{{ route('admin.service-categories.index') }}" class="nav-item {{ $is('admin.service-categories.*') }}"><span>›</span> Service categories</a>
                <a href="{{ route('admin.testimonials.index') }}" class="nav-item {{ $is('admin.testimonials.*') }}"><span>♡</span> Testimonials</a>
                <a href="{{ route('admin.faqs.index') }}" class="nav-item {{ $is('admin.faqs.*') }}"><span>?</span> FAQs</a>
                <a href="{{ route('admin.gallery.index') }}" class="nav-item {{ $is('admin.gallery.*') }}"><span>▦</span> Gallery</a>
                <a href="{{ route('admin.promos.index') }}" class="nav-item {{ $is('admin.promos.*') }}"><span>%</span> Promos</a>

                <div class="nav-section">Inbox</div>
                <a href="{{ route('admin.appointments.index') }}" class="nav-item {{ $is('admin.appointments.*') }}"><span>◷</span> Appointments</a>
                <a href="{{ route('admin.inquiries.index') }}" class="nav-item {{ $is('admin.inquiries.*') }}"><span>✉</span> Contact inquiries</a>
            </nav>

            <div class="border-t border-nude-100 px-4 py-4">
                <div class="flex items-center gap-3">
                    <span class="grid h-9 w-9 place-items-center rounded-full bg-gradient-to-br from-blush-200 to-nude-200 text-xs font-semibold text-terracotta-600">
                        {{ strtoupper(substr(auth()->user()?->name ?? 'A', 0, 2)) }}
                    </span>
                    <div class="flex-1 min-w-0">
                        <div class="truncate text-sm font-medium text-ink-900">{{ auth()->user()?->name }}</div>
                        <div class="truncate text-xs text-ink-500">{{ auth()->user()?->email }}</div>
                    </div>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="rounded-full border border-nude-200 px-3 py-1.5 text-xs text-ink-700 hover:border-terracotta-400 hover:text-terracotta-500" title="Sign out">↩</button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- Backdrop on mobile --}}
        <div
            x-cloak
            x-show="open"
            @click="open = false"
            x-transition.opacity
            class="fixed inset-0 z-30 bg-ink-900/30 backdrop-blur-sm lg:hidden"
        ></div>

        {{-- Main column --}}
        <div class="flex-1 min-w-0 flex flex-col">
            <header class="sticky top-0 z-20 border-b border-nude-100 bg-cream-50/85 backdrop-blur-md">
                <div class="flex items-center gap-3 px-4 py-3 sm:px-6">
                    <button type="button" @click="open = true" class="rounded-full border border-nude-200 p-2 text-ink-700 lg:hidden" aria-label="Open menu">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M4 7h16M4 12h16M4 17h16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                    </button>
                    <div class="flex-1 min-w-0">
                        <div class="eyebrow">@yield('eyebrow', 'Admin')</div>
                        <h1 class="mt-1 text-xl sm:text-2xl font-semibold tracking-tight text-ink-900">@yield('title', 'Dashboard')</h1>
                    </div>
                    @hasSection('actions')
                        <div class="flex items-center gap-2">@yield('actions')</div>
                    @endif
                </div>
            </header>

            <main class="flex-1 px-4 py-6 sm:px-6 sm:py-8">
                @if (session('status'))
                    <div class="flash flash-success mb-5">{{ session('status') }}</div>
                @endif
                @if ($errors->any() && ! isset($hideErrorSummary))
                    <div class="flash flash-error mb-5">
                        <div class="font-semibold">Please review the form:</div>
                        <ul class="mt-1 list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>

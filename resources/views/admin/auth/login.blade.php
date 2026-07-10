<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in · Emcey Brows Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-cream-grain text-ink-900">
    <div class="relative min-h-screen flex items-center justify-center p-6 overflow-hidden">
        <div aria-hidden class="pointer-events-none absolute -left-32 -top-20 h-96 w-96 rounded-full bg-blush-200/50 blur-3xl"></div>
        <div aria-hidden class="pointer-events-none absolute -right-24 bottom-0 h-[28rem] w-[28rem] rounded-full bg-nude-200/60 blur-3xl"></div>

        <div class="relative w-full max-w-md">
            <div class="flex flex-col items-center text-center mb-8">
                <span class="grid h-14 w-14 place-items-center rounded-full bg-gradient-to-br from-terracotta-400 to-gold-500 text-white shadow-lg">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                        <path d="M3 14c4-3 8-3 12-1s5 3 6 2c-2 4-7 6-12 5S3 14 3 14Z" fill="currentColor"/>
                    </svg>
                </span>
                <div class="mt-4 eyebrow">Emcey Brows · Admin</div>
                <h1 class="mt-2 text-3xl font-semibold tracking-tight text-ink-900">Welcome back</h1>
                <p class="mt-2 text-sm text-ink-500">Sign in to manage your studio.</p>
            </div>

            <div class="card p-7 sm:p-8">
                @if ($errors->any())
                    <div class="flash flash-error mb-5">{{ $errors->first() }}</div>
                @endif

                <form method="POST" action="{{ route('admin.login.attempt') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label for="email" class="field-label">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus class="field-input">
                    </div>
                    <div>
                        <label for="password" class="field-label">Password</label>
                        <input type="password" id="password" name="password" required class="field-input">
                    </div>
                    <label class="flex items-center gap-2 text-sm text-ink-700">
                        <input type="checkbox" name="remember" value="1" class="h-4 w-4 rounded border-nude-300 text-terracotta-500 focus:ring-terracotta-400">
                        Remember me on this device
                    </label>
                    <button type="submit" class="btn btn-primary w-full">Sign in</button>
                </form>
            </div>

            <p class="mt-6 text-center text-xs text-ink-500">
                © {{ date('Y') }} Emcey Brows Aesthetics
            </p>
        </div>
    </div>
</body>
</html>

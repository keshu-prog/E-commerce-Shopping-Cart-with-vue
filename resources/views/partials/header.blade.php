<header class="max-w-7xl mx-auto px-4 mb-6 flex items-center justify-between">
    <div class="flex items-center gap-2">
        <a href="{{ url('/') }}">
        <img src="/logo.png" alt="Logo" class="h-10 w-10">
        <span class="text-xl font-bold">Ecom</span>
        </a>
    </div>

    @if (Route::has('login'))
        <nav class="flex items-center gap-4">
            @auth
                <a href="{{ url('/dashboard') }}"
                   class="px-5 py-1.5 border rounded-sm text-sm">
                    Dashboard
                </a>
                <a href="{{ url('/cart') }}"
                   class="px-5 py-1.5 border rounded-sm text-sm">
                    View Cart
                </a>
            @else
                <a href="{{ route('login') }}"
                   class="px-5 py-1.5 border rounded-sm text-sm">
                    Log in
                </a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}"
                       class="px-5 py-1.5 border rounded-sm text-sm">
                        Register
                    </a>
                @endif
            @endauth
        </nav>
    @endif
</header>

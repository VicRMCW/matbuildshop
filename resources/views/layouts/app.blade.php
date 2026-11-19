<!DOCTYPE html>
<html lang="id">
<!-- Moving Text -->
    <div class="moving-bar">
        <div class="moving-text">
            Welcome to Our Website! Enjoy Shopping and Discover Our Latest Tools!
        </div>
    </div>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'MatbuildShop' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            background-color: var(--shop-bg, #FAF8F5);
            color: var(--shop-text, #1E1E1E);
            font-family: var(--font-sans, 'Inter', sans-serif);
            line-height: 1.5;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        main {
            flex: 1;
        }
    </style>
</head>
<body>
    <header class="shop-nav">
        <div class="shop-nav-inner">
            <a href="{{ route('shop.home') }}" class="shop-brand">MatbuildShop</a>

            <nav aria-label="Navigasi Utama">
                <ul class="shop-nav-menu">
                    <li>
                        <a href="{{ route('shop.catalog') }}" class="shop-nav-link {{ request()->routeIs('shop.catalog') ? 'active' : '' }}">
                            Katalog
                        </a>
                    </li>

                    @auth
                        @if(auth()->user()->isAdmin())
                            <li>
                                <a href="{{ route('admin.dashboard') }}" class="shop-nav-link">
                                    Dashboard Admin
                                </a>
                            </li>
                        @else
                            <li>
                                <a href="{{ route('cart.index') }}" class="shop-nav-link {{ request()->routeIs('cart.*') ? 'active' : '' }}">
                                    Keranjang ({{ auth()->user()->cartItems()->count() }})
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('orders.index') }}" class="shop-nav-link {{ request()->routeIs('orders.*') ? 'active' : '' }}">
                                    Pesanan Saya
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('profile.edit') }}" class="shop-nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                                    {{ auth()->user()->name }}
                                </a>
                            </li>
                        @endif
                        <li>
                            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                                @csrf
                                <button type="submit" class="shop-btn shop-btn-secondary" style="min-height: 44px; padding: 0 16px; font-size: 15px;">
                                    Keluar
                                </button>
                            </form>
                        </li>
                    @else
                        <li>
                            <a href="{{ route('login') }}" class="shop-nav-link {{ request()->routeIs('login') ? 'active' : '' }}">
                                Masuk
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('register') }}" class="shop-btn shop-btn-primary" style="min-height: 44px; padding: 0 18px; font-size: 15px;">
                                Daftar
                            </a>
                        </li>
                    @endauth
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <div class="shop-container" style="padding-top: 20px; padding-bottom: 0;">
            @include('partials.flash')
        </div>
        @yield('content')
    </main>
</body>

<footer class="site-footer">
    <h3>Contact Information</h3>

    <p>Email: MatBuildShop@email.com</p>
    <p>Phone: 08217724045</p>
    <p>Address: Bengkulu, Indonesia</p>

    <div class="footer-bottom">
        &copy; 2026 MatBuildShop. All Rights Reserved.
    </div>
</footer>

</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin - MatbuildShop' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            background-color: var(--adm-bg, #F2F1EF);
            color: var(--adm-text, #1E1E1E);
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            display: flex;
            min-height: 100vh;
        }
        aside {
            width: 250px;
            background: #2C2C2C;
            color: #FFFFFF;
            display: flex;
            flex-direction: column;
            padding: 24px 16px;
            flex-shrink: 0;
        }
        aside .brand {
            font-size: 20px;
            font-weight: 700;
            color: #FFFFFF;
            text-decoration: none;
            margin-bottom: 32px;
            padding-left: 12px;
            letter-spacing: -0.01em;
        }
        aside nav ul {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        .admin-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
            background: var(--adm-bg, #F2F1EF);
        }
        .admin-header {
            background: #FFFFFF;
            border-bottom: 1px solid #D6D3D1;
            padding: 16px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>
</head>
<body>
    <aside>
        <a href="{{ route('admin.dashboard') }}" class="brand">Matbuild Admin</a>
        <nav>
            <ul>
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
                </li>
                <li>
                    <a href="{{ Route::has('admin.categories.index') ? route('admin.categories.index') : '#' }}" class="sidebar-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">Kategori</a>
                </li>
                <li>
                    <a href="{{ Route::has('admin.products.index') ? route('admin.products.index') : '#' }}" class="sidebar-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">Produk</a>
                </li>
                <li>
                    <a href="{{ route('admin.orders.index') }}" class="sidebar-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">Pesanan</a>
                </li>
                <li style="margin-top: 16px; padding-top: 16px; border-top: 1px solid #3E3E3E;">
                    <a href="{{ route('shop.home') }}" class="sidebar-link" target="_blank">Lihat Toko &nearr;</a>
                </li>
            </ul>
        </nav>
    </aside>

    <div class="admin-main">
        <header class="admin-header">
            <div style="font-size: 16px; font-weight: 700; color: #1E1E1E;">Panel Administrator</div>
            <div style="display: flex; align-items: center; gap: 16px;">
                <span style="font-size: 15px; font-weight: 500; color: #333333;">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-secondary btn-sm">Keluar</button>
                </form>
            </div>
        </header>

        {{-- Seluruh view admin kini dibungkus dengan container terpusat .admin-content --}}
        <main class="admin-content">
            @include('partials.flash')
            @yield('content')
        </main>
    </div>
</body>
</html>
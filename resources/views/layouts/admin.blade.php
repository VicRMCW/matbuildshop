<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin - MatbuildShop' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --bg-body: #F2F1EF;
            --text-main: #1E1E1E;
            --surface: #FFFFFF;
            --border-color: #D6D3D1;
            --accent: #C8601A;
            --accent-hover: #A64E13;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            background-color: var(--bg-body);
            color: var(--text-main);
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            display: flex;
            min-height: 100vh;
        }
        aside {
            width: 240px;
            background: #2C2C2C;
            color: #FFFFFF;
            display: flex;
            flex-direction: column;
            padding: 1.5rem 1rem;
            flex-shrink: 0;
        }
        aside .brand {
            font-size: 1.15rem;
            font-weight: 700;
            color: #FFFFFF;
            text-decoration: none;
            margin-bottom: 2rem;
            letter-spacing: -0.01em;
        }
        aside nav ul {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
        }
        aside nav a {
            color: #D6D3D1;
            text-decoration: none;
            padding: 0.6rem 0.75rem;
            border-radius: 4px;
            display: block;
            font-size: 0.9rem;
            transition: background-color 150ms ease, color 150ms ease;
        }
        aside nav a:hover {
            background: #3E3E3E;
            color: #FFFFFF;
        }
        aside nav a.active {
            background: var(--accent);
            color: #FFFFFF;
            font-weight: 600;
        }
        .admin-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }
        .admin-header {
            background: var(--surface);
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .admin-content {
            padding: 2rem 1.5rem;
            flex: 1;
        }
        .btn {
            background: var(--accent);
            color: #FFFFFF;
            padding: 0.45rem 0.85rem;
            border-radius: 4px;
            text-decoration: none;
            border: 1px solid transparent;
            font-size: 0.9rem;
            cursor: pointer;
            transition: background-color 150ms ease;
        }
        .btn:hover {
            background: var(--accent-hover);
        }
        .btn-secondary {
            background: transparent;
            color: var(--text-main);
            border-color: var(--border-color);
        }
        .btn-secondary:hover {
            background: #F2F1EF;
        }
        .card {
            background: var(--surface);
            border: 1px solid var(--border-color);
            border-radius: 6px;
            padding: 1.5rem;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 1rem;
        }
        .stat-card {
            background: var(--surface);
            border: 1px solid var(--border-color);
            border-radius: 6px;
            padding: 1.25rem;
        }
        .stat-label {
            font-size: 0.85rem;
            color: #666;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }
        .stat-value {
            font-size: 1.85rem;
            font-weight: 700;
            color: var(--text-main);
            line-height: 1;
        }
        .stat-value.accent {
            color: var(--accent);
        }
    </style>
</head>
<body>
    <aside>
        <a href="{{ route('admin.dashboard') }}" class="brand">Matbuild Admin</a>
        <nav>
            <ul>
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
                </li>
                <li>
                    <a href="{{ Route::has('admin.categories.index') ? route('admin.categories.index') : '#' }}" class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">Kategori</a>
                </li>
                <li>
                    <a href="{{ Route::has('admin.products.index') ? route('admin.products.index') : '#' }}" class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}">Produk</a>
                </li>
                <li>
                    <a href="{{ Route::has('admin.orders.index') ? route('admin.orders.index') : '#' }}" class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">Pesanan</a>
                </li>
                <li style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #3E3E3E;">
                    <a href="{{ route('shop.home') }}">Lihat Toko</a>
                </li>
            </ul>
        </nav>
    </aside>

    <div class="admin-main">
        <header class="admin-header">
            <div><strong>Panel Administrator</strong></div>
            <div style="display: flex; align-items: center; gap: 1rem;">
                <span style="font-size: 0.9rem;">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-secondary">Keluar</button>
                </form>
            </div>
        </header>

        <section class="admin-content">
            @include('partials.flash')
            @yield('content')
        </section>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin - MatbuildShop' }}</title>
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
            gap: 0.5rem;
        }
        aside nav a {
            color: #D6D3D1;
            text-decoration: none;
            padding: 0.5rem 0.75rem;
            border-radius: 4px;
            display: block;
            font-size: 0.9rem;
        }
        aside nav a:hover, aside nav a.active {
            background: #3E3E3E;
            color: #FFFFFF;
        }
        .admin-main {
            flex: 1;
            display: flex;
            flex-direction: column;
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
    </style>
</head>
<body>
    <aside>
        <a href="{{ route('admin.dashboard') }}" class="brand">Matbuild Admin</a>
        <nav>
            <ul>
                <li><a href="{{ route('admin.dashboard') }}" class="active">Dashboard</a></li>
                <li><a href="{{ route('shop.home') }}">Lihat Toko</a></li>
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
            @yield('content')
        </section>
    </div>
</body>
</html>

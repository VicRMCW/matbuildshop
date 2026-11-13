<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'MatbuildShop' }}</title>
    <style>
        :root {
            --bg-body: #FAF8F5;
            --text-main: #1E1E1E;
            --surface: #FFFFFF;
            --border-color: #D6D3D1;
            --accent: #C8601A;
            --accent-hover: #A64E13;
            --danger: #B91C1C;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            background-color: var(--bg-body);
            color: var(--text-main);
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            line-height: 1.5;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        header {
            background: var(--surface);
            border-bottom: 1px solid var(--border-color);
        }
        .nav-container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }
        .brand {
            font-size: 1.25rem;
            font-weight: 700;
            text-decoration: none;
            color: var(--text-main);
            letter-spacing: -0.02em;
        }
        .nav-links {
            display: flex;
            align-items: center;
            gap: 1rem;
            list-style: none;
        }
        .nav-links a {
            color: var(--text-main);
            text-decoration: none;
            font-size: 0.95rem;
        }
        .nav-links a:hover {
            color: var(--accent);
        }
        .btn {
            display: inline-block;
            background: var(--accent);
            color: #FFFFFF;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            text-decoration: none;
            border: 1px solid transparent;
            font-size: 0.95rem;
            cursor: pointer;
            transition: background-color 150ms ease;
        }
        .btn:hover { background: var(--accent-hover); }
        .btn-secondary {
            background: transparent;
            color: var(--text-main);
            border-color: var(--border-color);
        }
        .btn-secondary:hover {
            background: #F2F1EF;
            color: var(--text-main);
        }
        main {
            flex: 1;
            max-width: 1100px;
            width: 100%;
            margin: 0 auto;
            padding: 2rem 1rem;
        }
        .card {
            background: var(--surface);
            border: 1px solid var(--border-color);
            border-radius: 6px;
            padding: 1.5rem;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        label {
            display: block;
            margin-bottom: 0.35rem;
            font-size: 0.9rem;
            font-weight: 600;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"],
        textarea {
            width: 100%;
            padding: 0.6rem 0.75rem;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-size: 0.95rem;
            background: #FFFFFF;
            color: var(--text-main);
        }
        input:focus, textarea:focus {
            outline: 2px solid var(--accent);
            border-color: var(--accent);
        }
        .field-error {
            color: var(--danger);
            font-size: 0.85rem;
            margin-top: 0.25rem;
        }
        .alert-success {
            background: #E8F5E9;
            color: #1E4620;
            border: 1px solid #C8E6C9;
            padding: 0.75rem 1rem;
            border-radius: 4px;
            margin-bottom: 1.25rem;
        }
    </style>
</head>
<body>
    <header>
        <div class="nav-container">
            <a href="{{ route('shop.home') }}" class="brand">MatbuildShop</a>
            <ul class="nav-links">
                @auth
                    @if(auth()->user()->isAdmin())
                        <li><a href="{{ route('admin.dashboard') }}">Dashboard Admin</a></li>
                    @else
                        <li><a href="{{ route('profile.edit') }}">{{ auth()->user()->name }}</a></li>
                    @endif
                    <li>
                        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-secondary" style="padding: 0.35rem 0.75rem;">Keluar</button>
                        </form>
                    </li>
                @else
                    <li><a href="{{ route('login') }}">Masuk</a></li>
                    <li><a href="{{ route('register') }}" class="btn">Daftar</a></li>
                @endauth
            </ul>
        </div>
    </header>

    <main>
        @yield('content')
    </main>
</body>
</html>

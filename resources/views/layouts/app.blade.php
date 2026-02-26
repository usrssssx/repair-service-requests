<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Заявки в ремонтную службу')</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600&family=Space+Grotesk:wght@500;600;700&display=swap');

        :root {
            --bg: #f2f5fb;
            --bg-gradient: radial-gradient(circle at top, #eff6ff 0%, #f7fbff 35%, #f8fafc 70%, #f1f5f9 100%);
            --surface: #ffffff;
            --surface-2: #f8fafc;
            --border: #e2e8f0;
            --text: #0f172a;
            --muted: #64748b;
            --primary: #2563eb;
            --primary-strong: #1d4ed8;
            --success: #16a34a;
            --danger: #dc2626;
            --warning: #f59e0b;
            --shadow: 0 18px 40px rgba(15, 23, 42, 0.12);
            --radius: 14px;
            --radius-sm: 10px;
            --space-1: 4px;
            --space-2: 8px;
            --space-3: 12px;
            --space-4: 16px;
            --space-5: 20px;
            --space-6: 24px;
            --space-8: 32px;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Manrope', sans-serif;
            background: var(--bg-gradient);
            margin: 0;
            color: var(--text);
            min-height: 100vh;
        }

        h1, h2, h3 {
            font-family: 'Space Grotesk', sans-serif;
            margin: 0 0 var(--space-4);
            letter-spacing: -0.02em;
        }

        a { color: inherit; text-decoration: none; }

        .container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 var(--space-5);
        }

        .app-header {
            background: rgba(15, 23, 42, 0.9);
            color: #f8fafc;
            border-bottom: 1px solid rgba(148, 163, 184, 0.2);
        }

        .header-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: var(--space-4);
            padding: var(--space-5) 0;
            flex-wrap: wrap;
        }

        .brand {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .brand-title { font-weight: 700; font-size: 1.1rem; letter-spacing: 0.01em; }
        .brand-subtitle { font-size: 0.85rem; color: rgba(226, 232, 240, 0.75); }

        .nav {
            display: flex;
            align-items: center;
            gap: var(--space-3);
            flex-wrap: wrap;
        }

        .nav-link {
            padding: 6px 10px;
            border-radius: 999px;
            background: rgba(148, 163, 184, 0.15);
            color: #e2e8f0;
            font-size: 0.9rem;
        }

        .nav-link:hover { background: rgba(148, 163, 184, 0.3); }

        .link-pill {
            padding: 6px 10px;
            border-radius: 999px;
            background: rgba(37, 99, 235, 0.1);
            color: #1d4ed8;
            font-size: 0.85rem;
        }

        .link-pill:hover { background: rgba(37, 99, 235, 0.2); }

        .chip {
            background: rgba(255, 255, 255, 0.12);
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 0.85rem;
        }

        main { padding: var(--space-6) 0 var(--space-8); }

        .card {
            background: var(--surface);
            padding: var(--space-6);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid rgba(148, 163, 184, 0.2);
        }

        .alert {
            padding: var(--space-3) var(--space-4);
            border-radius: var(--radius-sm);
            margin-bottom: var(--space-4);
            border: 1px solid transparent;
        }
        .alert ul { margin: 0; padding-left: 18px; }

        .alert-success { background: rgba(22, 163, 74, 0.08); color: #14532d; border-color: rgba(22, 163, 74, 0.2); }
        .alert-error { background: rgba(220, 38, 38, 0.08); color: #7f1d1d; border-color: rgba(220, 38, 38, 0.2); }

        .actions { display: flex; gap: var(--space-2); align-items: center; flex-wrap: wrap; }

        .btn {
            appearance: none;
            border: none;
            padding: 8px 14px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }

        .btn-primary { background: var(--primary); color: #fff; }
        .btn-primary:hover { background: var(--primary-strong); box-shadow: 0 6px 14px rgba(37, 99, 235, 0.25); }
        .btn-secondary { background: #e2e8f0; color: #0f172a; }
        .btn-secondary:hover { background: #cbd5f5; }
        .btn-danger { background: var(--danger); color: #fff; }
        .btn-danger:hover { background: #b91c1c; }

        form.inline { display: inline; }

        .field { margin-bottom: var(--space-4); }
        .label { display: block; font-weight: 600; margin-bottom: var(--space-2); }
        .input, .select, .textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid var(--border);
            border-radius: 10px;
            background: #fff;
            font-family: inherit;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }
        .input:focus, .select:focus, .textarea:focus {
            outline: none;
            border-color: rgba(37, 99, 235, 0.6);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }
        .textarea { min-height: 110px; resize: vertical; }
        .field-error { border-color: rgba(220, 38, 38, 0.8); }
        .field-error-text { color: #b91c1c; font-size: 0.85em; margin-top: 6px; }
        .text-muted { color: var(--muted); font-size: 0.9em; }

        .table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.95rem;
        }
        .table th, .table td {
            padding: 12px 10px;
            border-bottom: 1px solid var(--border);
            text-align: left;
            vertical-align: top;
        }
        .table th {
            background: var(--surface-2);
            color: #334155;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }
        .table tbody tr:nth-child(even) { background: #f8fafc; }
        .table tbody tr:hover { background: #eef2ff; }

        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: lowercase;
        }
        .status-new { background: rgba(37, 99, 235, 0.12); color: #1d4ed8; }
        .status-assigned { background: rgba(14, 116, 144, 0.12); color: #0e7490; }
        .status-in_progress { background: rgba(245, 158, 11, 0.18); color: #92400e; }
        .status-done { background: rgba(22, 163, 74, 0.16); color: #166534; }
        .status-canceled { background: rgba(148, 163, 184, 0.4); color: #475569; }

        .timeline {
            list-style: none;
            margin: 0;
            padding: 0;
            display: grid;
            gap: 6px;
        }
        .timeline-item {
            display: flex;
            gap: 8px;
            align-items: flex-start;
            color: var(--muted);
            font-size: 0.85rem;
        }
        .timeline-dot {
            width: 6px;
            height: 6px;
            background: #94a3b8;
            border-radius: 999px;
            margin-top: 6px;
            flex-shrink: 0;
        }

        @media (max-width: 820px) {
            .header-inner { padding: var(--space-4) 0; }
            .nav { width: 100%; justify-content: flex-start; }
            .table, .table tbody, .table tr, .table td { display: block; width: 100%; }
            .table thead { display: none; }
            .table tr { background: var(--surface); margin-bottom: var(--space-4); border: 1px solid var(--border); border-radius: var(--radius-sm); padding: var(--space-3); }
            .table td { border: none; padding: 6px 0; display: flex; justify-content: space-between; gap: var(--space-3); }
            .table td::before { content: attr(data-label); font-weight: 600; color: #475569; }
        }
    </style>
</head>
<body>
<header class="app-header">
    <div class="container header-inner">
        <div class="brand">
            <span class="brand-title">Заявки в ремонтную службу</span>
            <span class="brand-subtitle">Внутренняя панель управления</span>
        </div>
        <div class="nav">
            @if (!empty($currentUser))
                <span class="chip">{{ $currentUser->name }} · {{ $currentUser->role }}</span>
                <a class="nav-link" href="/requests/create">Создать заявку</a>
                @if ($currentUser->role === 'dispatcher')
                    <a class="nav-link" href="/dispatcher/requests">Панель диспетчера</a>
                @endif
                @if ($currentUser->role === 'master')
                    <a class="nav-link" href="/master/requests">Панель мастера</a>
                @endif
                <form method="POST" action="/logout" class="inline">
                    @csrf
                    <button type="submit" class="btn btn-secondary">Выйти</button>
                </form>
            @else
                <a class="nav-link" href="/login">Войти</a>
            @endif
        </div>
    </div>
</header>
<main>
    <div class="container">
        <div class="card">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-error">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </div>
</main>
</body>
</html>

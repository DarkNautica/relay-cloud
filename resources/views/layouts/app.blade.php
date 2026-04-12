<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Relay Cloud') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --bg: #0a0a0f;
            --surface: #111118;
            --border: #1e1e2e;
            --accent: #6366f1;
            --accent-hover: #818cf8;
            --text: #f8fafc;
            --text-muted: #94a3b8;
            --danger: #ef4444;
            --success: #22c55e;
            --warning: #f59e0b;
        }
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
        }
        a { color: inherit; text-decoration: none; }

        .sidebar {
            width: 260px;
            min-height: 100vh;
            background: var(--surface);
            border-right: 1px solid var(--border);
            position: fixed;
            top: 0; left: 0;
            display: flex;
            flex-direction: column;
            z-index: 50;
        }
        .sidebar-logo {
            padding: 24px 20px;
            border-bottom: 1px solid var(--border);
            font-size: 20px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .sidebar-logo .logo-icon {
            width: 32px; height: 32px;
            background: var(--accent);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
        }
        .sidebar-nav {
            padding: 16px 12px;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        .nav-link {
            display: flex; align-items: center; gap: 12px;
            padding: 10px 12px; border-radius: 8px;
            font-size: 14px; font-weight: 500;
            color: var(--text-muted);
            transition: all 0.15s;
        }
        .nav-link:hover { background: rgba(99,102,241,0.08); color: var(--text); }
        .nav-link.active { background: rgba(99,102,241,0.12); color: var(--accent); }
        .nav-link svg { width: 20px; height: 20px; flex-shrink: 0; }
        .nav-separator { height: 1px; background: var(--border); margin: 12px 0; }
        .nav-label { font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted); padding: 8px 12px 4px; opacity: 0.6; }

        .main-wrapper {
            margin-left: 260px; flex: 1;
            min-height: 100vh;
            display: flex; flex-direction: column;
        }
        .top-bar {
            height: 64px;
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 32px;
            background: var(--surface);
        }
        .top-bar-left { font-size: 14px; color: var(--text-muted); }
        .top-bar-right { display: flex; align-items: center; gap: 16px; }
        .plan-badge {
            font-size: 12px; font-weight: 600;
            padding: 4px 12px; border-radius: 9999px;
            background: rgba(99,102,241,0.15); color: var(--accent);
            text-transform: uppercase; letter-spacing: 0.03em;
        }
        .user-name { font-size: 14px; font-weight: 500; }
        .content { padding: 32px; flex: 1; }

        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 24px;
        }
        .card-header {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 20px;
        }
        .card-title { font-size: 16px; font-weight: 600; }

        .stats-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px; }
        .stat-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px; padding: 20px;
        }
        .stat-label { font-size: 13px; color: var(--text-muted); margin-bottom: 8px; }
        .stat-value { font-size: 28px; font-weight: 700; }

        .btn {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 10px 20px; border-radius: 8px;
            font-size: 14px; font-weight: 500;
            border: none; cursor: pointer;
            transition: all 0.15s; font-family: inherit;
        }
        .btn-primary { background: var(--accent); color: white; }
        .btn-primary:hover { background: var(--accent-hover); }
        .btn-secondary { background: transparent; border: 1px solid var(--border); color: var(--text); }
        .btn-secondary:hover { border-color: var(--text-muted); }
        .btn-danger { background: rgba(239,68,68,0.1); color: var(--danger); border: 1px solid rgba(239,68,68,0.2); }
        .btn-danger:hover { background: rgba(239,68,68,0.2); }
        .btn-sm { padding: 6px 14px; font-size: 13px; }

        .form-group { margin-bottom: 20px; }
        .form-label { display: block; font-size: 14px; font-weight: 500; margin-bottom: 8px; color: var(--text-muted); }
        .form-input {
            width: 100%; padding: 10px 14px;
            background: var(--bg); border: 1px solid var(--border);
            border-radius: 8px; color: var(--text);
            font-size: 14px; font-family: inherit; outline: none;
            transition: border-color 0.15s;
        }
        .form-input:focus { border-color: var(--accent); }
        .form-error { color: var(--danger); font-size: 13px; margin-top: 6px; }

        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; font-size: 12px; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted); padding: 12px 16px; border-bottom: 1px solid var(--border); }
        td { padding: 14px 16px; border-bottom: 1px solid var(--border); font-size: 14px; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: rgba(99,102,241,0.03); }

        .badge {
            display: inline-flex; align-items: center; gap: 6px;
            font-size: 12px; font-weight: 600;
            padding: 4px 10px; border-radius: 6px;
        }
        .badge-active { background: rgba(34,197,94,0.1); color: var(--success); }
        .badge-inactive { background: rgba(239,68,68,0.1); color: var(--danger); }
        .badge-dot { width: 6px; height: 6px; border-radius: 50%; }
        .badge-active .badge-dot { background: var(--success); }
        .badge-inactive .badge-dot { background: var(--danger); }

        .empty-state { text-align: center; padding: 60px 20px; color: var(--text-muted); }
        .empty-state svg { width: 48px; height: 48px; margin-bottom: 16px; opacity: 0.5; }
        .empty-state h3 { font-size: 18px; color: var(--text); margin-bottom: 8px; }
        .empty-state p { margin-bottom: 24px; font-size: 14px; }

        .cred-row {
            display: flex; align-items: center; justify-content: space-between;
            background: var(--bg); border: 1px solid var(--border);
            border-radius: 8px; padding: 12px 16px; margin-bottom: 8px;
        }
        .cred-label { font-size: 12px; color: var(--text-muted); margin-bottom: 4px; }
        .cred-value { font-family: 'Courier New', monospace; font-size: 13px; word-break: break-all; }
        .cred-blurred { filter: blur(5px); user-select: none; transition: filter 0.2s; }
        .cred-actions { display: flex; gap: 8px; flex-shrink: 0; margin-left: 12px; }
        .icon-btn {
            width: 34px; height: 34px;
            display: flex; align-items: center; justify-content: center;
            background: transparent; border: 1px solid var(--border);
            border-radius: 6px; cursor: pointer; color: var(--text-muted);
            transition: all 0.15s;
        }
        .icon-btn:hover { border-color: var(--text-muted); color: var(--text); }
        .icon-btn svg { width: 16px; height: 16px; }

        .code-tabs { display: flex; gap: 0; border-bottom: 1px solid var(--border); }
        .code-tab {
            padding: 10px 20px; font-size: 13px; font-weight: 500;
            color: var(--text-muted); background: none; border: none;
            cursor: pointer; border-bottom: 2px solid transparent;
            font-family: inherit; transition: all 0.15s;
        }
        .code-tab.active { color: var(--accent); border-bottom-color: var(--accent); }
        .code-block {
            background: var(--bg); border: 1px solid var(--border);
            border-top: none; border-radius: 0 0 8px 8px;
            padding: 20px; overflow-x: auto;
        }
        .code-block pre { font-family: 'Courier New', monospace; font-size: 13px; line-height: 1.6; color: var(--text-muted); white-space: pre; }

        .plan-cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; }
        .plan-card {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: 12px; padding: 28px; position: relative;
            transition: border-color 0.15s;
        }
        .plan-card.current { border-color: var(--accent); }
        .plan-card-name { font-size: 20px; font-weight: 700; margin-bottom: 4px; }
        .plan-card-price { font-size: 36px; font-weight: 700; margin-bottom: 4px; }
        .plan-card-price span { font-size: 16px; font-weight: 400; color: var(--text-muted); }
        .plan-card-desc { font-size: 14px; color: var(--text-muted); margin-bottom: 20px; }
        .plan-features { list-style: none; margin-bottom: 24px; }
        .plan-features li {
            font-size: 14px; color: var(--text-muted);
            padding: 6px 0; display: flex; align-items: center; gap: 10px;
        }
        .plan-features li svg { width: 16px; height: 16px; color: var(--accent); flex-shrink: 0; }

        .progress-bar { height: 8px; background: var(--border); border-radius: 4px; overflow: hidden; }
        .progress-fill { height: 100%; background: var(--accent); border-radius: 4px; transition: width 0.3s; }

        .alert {
            padding: 14px 18px; border-radius: 8px; font-size: 14px;
            margin-bottom: 20px; display: flex; align-items: center; gap: 10px;
        }
        .alert-success { background: rgba(34,197,94,0.1); border: 1px solid rgba(34,197,94,0.2); color: var(--success); }
        .alert-error { background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.2); color: var(--danger); }
        .alert-info { background: rgba(99,102,241,0.1); border: 1px solid rgba(99,102,241,0.2); color: var(--accent); }
        .alert-warning { background: rgba(245,158,11,0.1); border: 1px solid rgba(245,158,11,0.2); color: var(--warning); }

        .danger-zone {
            border: 1px solid rgba(239,68,68,0.2);
            border-radius: 12px; padding: 24px;
        }
        .danger-zone h3 { color: var(--danger); font-size: 16px; margin-bottom: 8px; }
        .danger-zone p { color: var(--text-muted); font-size: 14px; margin-bottom: 16px; }

        .page-header {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 24px;
        }
        .page-title { font-size: 24px; font-weight: 700; }
        .page-subtitle { font-size: 14px; color: var(--text-muted); margin-top: 4px; }

        .project-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 16px; }

        .logout-btn {
            background: none; border: none; color: var(--text-muted);
            font-size: 14px; font-weight: 500; cursor: pointer;
            font-family: inherit; padding: 10px 12px; border-radius: 8px;
            display: flex; align-items: center; gap: 12px; width: 100%;
            transition: all 0.15s;
        }
        .logout-btn:hover { background: rgba(239,68,68,0.08); color: var(--danger); }
        .logout-btn svg { width: 20px; height: 20px; }
    </style>
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar-logo">
            <div class="logo-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:18px;height:18px"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
            </div>
            Relay Cloud
        </div>
        <nav class="sidebar-nav">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
                Dashboard
            </a>
            <a href="{{ route('projects.index') }}" class="nav-link {{ request()->routeIs('projects.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/></svg>
                Projects
            </a>
            <a href="{{ route('billing.index') }}" class="nav-link {{ request()->routeIs('billing.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                Billing
            </a>
            <div class="nav-separator"></div>
            <div class="nav-label">Resources</div>
            <a href="https://darknautica.github.io/Relay" target="_blank" class="nav-link">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                Docs
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:14px;height:14px;margin-left:auto;opacity:0.5"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
            </a>
            <a href="https://github.com/DarkNautica/Relay" target="_blank" class="nav-link">
                <svg viewBox="0 0 24 24" fill="currentColor" style="width:20px;height:20px"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                GitHub
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:14px;height:14px;margin-left:auto;opacity:0.5"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
            </a>
            <div style="flex:1"></div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                    Log out
                </button>
            </form>
        </nav>
    </aside>

    <div class="main-wrapper">
        <header class="top-bar">
            <div class="top-bar-left">@yield('header', 'Dashboard')</div>
            <div class="top-bar-right">
                <span class="plan-badge">{{ Auth::user()->fresh()->plan ?? 'hobby' }} plan</span>
                <span class="user-name">{{ Auth::user()->name }}</span>
            </div>
        </header>
        <main class="content">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif
            @if(session('info'))
                <div class="alert alert-info">{{ session('info') }}</div>
            @endif
            @yield('content')
        </main>
    </div>
</body>
</html>

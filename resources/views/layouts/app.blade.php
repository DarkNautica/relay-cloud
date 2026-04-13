<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Relay Cloud') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --bg-base: #0c0c0e;
            --bg-surface: #111115;
            --bg-elevated: #18181d;
            --bg-hover: #1e1e25;
            --border: #27272f;
            --border-strong: #35353f;
            --accent: #7c3aed;
            --accent-light: #8b5cf6;
            --accent-glow: rgba(124,58,237,0.12);
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --text-primary: #f1f0f5;
            --text-secondary: #8b8a98;
            --text-tertiary: #4f4e5c;
            --font-sans: 'Inter', system-ui, -apple-system, sans-serif;
            --font-mono: 'JetBrains Mono', 'Courier New', monospace;
            --shadow-card: 0 1px 3px rgba(0,0,0,0.5), 0 4px 16px rgba(0,0,0,0.3), inset 0 1px 0 rgba(255,255,255,0.04);
            --shadow-elevated: 0 4px 24px rgba(0,0,0,0.5);
            --shadow-glow: 0 0 0 3px var(--accent-glow);
        }
        body {
            font-family: var(--font-sans); background-color: var(--bg-base); color: var(--text-primary);
            min-height: 100vh; display: flex; -webkit-font-smoothing: antialiased;
            background-image:
                radial-gradient(ellipse 80% 50% at 50% -20%, rgba(124,58,237,0.08) 0%, transparent 60%),
                url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.015'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        a { color: inherit; text-decoration: none; }

        /* ── Sidebar ── */
        .sidebar {
            width: 240px; min-height: 100vh; position: fixed; top: 0; left: 0; z-index: 50;
            background: linear-gradient(180deg, #0e0e14 0%, #0c0c0e 100%);
            border-right: 1px solid #1a1a24; box-shadow: 4px 0 24px rgba(0,0,0,0.4);
            display: flex; flex-direction: column;
        }
        .sidebar-brand {
            padding: 20px 16px 16px; border-bottom: 1px solid var(--border);
        }
        .sidebar-brand-top {
            display: flex; align-items: center; gap: 10px;
            font-size: 15px; font-weight: 700; color: var(--text-primary);
        }
        .sidebar-brand-top svg { width: 20px; height: 20px; color: var(--accent-light); }
        .sidebar-brand-email { font-size: 12px; color: var(--text-tertiary); margin-top: 6px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .sidebar-nav { padding: 12px 8px; flex: 1; display: flex; flex-direction: column; gap: 2px; overflow-y: auto; }
        .nav-section { font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; color: var(--text-tertiary); padding: 16px 12px 6px; }
        .nav-item {
            display: flex; align-items: center; gap: 10px; height: 38px;
            padding: 0 12px; border-radius: 8px; font-size: 13px; font-weight: 500;
            color: var(--text-secondary); transition: all 150ms ease; border-left: 2px solid transparent;
            cursor: pointer;
        }
        .nav-item:hover { background: var(--bg-hover); color: var(--text-primary); }
        .nav-item.active { background: rgba(124,58,237,0.12); color: var(--accent-light); border-left-color: #7c3aed; box-shadow: inset 0 0 20px rgba(124,58,237,0.05); }
        .nav-item svg { width: 16px; height: 16px; flex-shrink: 0; }
        .nav-item .ext-icon { margin-left: auto; width: 12px; height: 12px; opacity: 0.4; }
        .sidebar-footer { padding: 12px 8px; border-top: 1px solid var(--border); }
        .sidebar-footer-info { display: flex; align-items: center; justify-content: space-between; padding: 8px 12px; }
        .sidebar-plan {
            font-size: 11px; font-weight: 600; padding: 3px 8px; border-radius: 4px;
            background: rgba(124,58,237,0.15); color: var(--accent-light); text-transform: uppercase; letter-spacing: 0.03em;
        }
        .sidebar-user { font-size: 12px; color: var(--text-secondary); font-weight: 500; }
        .logout-btn {
            display: flex; align-items: center; gap: 10px; width: 100%;
            padding: 8px 12px; border-radius: 8px; border: none; background: none;
            font-size: 13px; font-weight: 500; color: var(--text-tertiary);
            cursor: pointer; font-family: var(--font-sans); transition: all 150ms ease;
        }
        .logout-btn:hover { background: rgba(239,68,68,0.08); color: var(--danger); }
        .logout-btn svg { width: 16px; height: 16px; }

        /* ── Main Area ── */
        .main { margin-left: 240px; flex: 1; min-height: 100vh; display: flex; flex-direction: column; }
        .top-bar {
            height: 56px; position: sticky; top: 0; z-index: 40;
            background: rgba(12,12,14,0.9); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid #1a1a22;
            box-shadow: 0 1px 0 rgba(255,255,255,0.03), 0 4px 16px rgba(0,0,0,0.3);
            display: flex; align-items: center; justify-content: space-between; padding: 0 28px;
        }
        .top-bar-left { font-size: 13px; color: var(--text-secondary); font-weight: 500; }
        .top-bar-right { display: flex; align-items: center; gap: 16px; }
        .top-bar-status {
            display: flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 500;
        }
        .top-bar-status.online { color: var(--success); }
        .top-bar-status.offline { color: var(--danger); }
        @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:0.4} }
        .status-dot { width: 6px; height: 6px; border-radius: 50%; }
        .status-dot.online { background: var(--success); animation: pulse 2s ease-in-out infinite; }
        .status-dot.offline { background: var(--danger); }
        .top-badge {
            font-size: 11px; font-weight: 600; padding: 3px 8px; border-radius: 4px;
            background: rgba(124,58,237,0.15); color: var(--accent-light); text-transform: uppercase;
        }
        .top-user { font-size: 13px; color: var(--text-secondary); font-weight: 500; }
        .content { padding: 28px; flex: 1; max-width: 1200px; width: 100%; }

        /* ── Cards ── */
        .card {
            background: linear-gradient(145deg, #161620 0%, #111115 100%);
            border: 1px solid var(--border); border-top: 1px solid rgba(255,255,255,0.06);
            border-radius: 12px; box-shadow: var(--shadow-card);
        }
        .card-pad { padding: 24px; }
        .card-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 18px 24px; border-bottom: 1px solid var(--border);
        }
        .card-title { font-size: 14px; font-weight: 600; }

        /* ── Stat Cards ── */
        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-bottom: 24px; }
        .stat-card {
            background: linear-gradient(145deg, #161620 0%, #111115 100%);
            border: 1px solid var(--border); border-top: 1px solid rgba(255,255,255,0.06);
            border-radius: 12px; padding: 20px 22px; box-shadow: var(--shadow-card);
            transition: all 200ms ease;
        }
        .stat-card:hover { border-color: var(--border-strong); transform: translateY(-1px); box-shadow: var(--shadow-card), 0 0 24px rgba(124,58,237,0.08); }
        .stat-top { display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; }
        .stat-label { font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; color: #6b6a7a; }
        .stat-icon { width: 16px; height: 16px; color: var(--accent-light); }
        .stat-value { font-size: 36px; font-weight: 700; letter-spacing: -0.02em; color: var(--text-primary); line-height: 1; }
        .stat-sub { font-size: 12px; color: var(--text-tertiary); margin-top: 6px; }
        .stat-sub a { color: var(--accent-light); }
        .stat-sub a:hover { text-decoration: underline; }

        /* ── Buttons ── */
        .btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            padding: 9px 18px; border-radius: 8px; font-size: 13px; font-weight: 500;
            border: none; cursor: pointer; font-family: var(--font-sans);
            transition: all 150ms ease; white-space: nowrap;
        }
        .btn:active { transform: scale(0.98); }
        .btn-primary { background: var(--accent); color: #fff; }
        .btn-primary:hover { background: var(--accent-light); box-shadow: var(--shadow-glow); }
        .btn-secondary { background: transparent; border: 1px solid var(--border); color: var(--text-secondary); }
        .btn-secondary:hover { border-color: var(--border-strong); color: var(--text-primary); }
        .btn-danger { background: rgba(239,68,68,0.1); color: var(--danger); border: 1px solid rgba(239,68,68,0.15); }
        .btn-danger:hover { background: rgba(239,68,68,0.18); }
        .btn-sm { padding: 6px 12px; font-size: 12px; }
        .btn svg { width: 15px; height: 15px; }

        /* ── Forms ── */
        .form-group { margin-bottom: 18px; }
        .form-label { display: block; font-size: 13px; font-weight: 500; color: var(--text-secondary); margin-bottom: 6px; }
        .form-input {
            width: 100%; padding: 9px 14px; background: var(--bg-base);
            border: 1px solid var(--border); border-radius: 8px;
            color: var(--text-primary); font-size: 14px; font-family: var(--font-sans);
            outline: none; transition: all 150ms ease;
        }
        .form-input:focus { border-color: var(--accent); box-shadow: var(--shadow-glow); }
        .form-error { color: var(--danger); font-size: 12px; margin-top: 4px; }

        /* ── Tables ── */
        table { width: 100%; border-collapse: collapse; }
        th {
            text-align: left; font-size: 11px; font-weight: 600; text-transform: uppercase;
            letter-spacing: 0.06em; color: var(--text-tertiary); padding: 12px 20px;
            border-bottom: 1px solid var(--border); background: none;
        }
        td { padding: 14px 20px; border-bottom: 1px solid var(--border); font-size: 13px; color: var(--text-secondary); vertical-align: middle; }
        tr:last-child td { border-bottom: none; }
        tbody tr { transition: all 150ms ease; cursor: pointer; border-left: 2px solid transparent; }
        tbody tr:nth-child(odd) td { background: rgba(255,255,255,0.01); }
        tbody tr:hover { border-left-color: rgba(124,58,237,0.4); }
        tbody tr:hover td { background: rgba(124,58,237,0.06); }
        .col-status { width: 120px; }
        .col-conn { width: 160px; }
        .col-created { width: 160px; }
        .col-actions { width: 60px; }
        tbody tr:hover { background: var(--bg-hover); }

        /* ── Badges ── */
        .badge {
            display: inline-flex; align-items: center; gap: 5px; font-size: 11px;
            font-weight: 600; padding: 3px 8px; border-radius: 5px;
        }
        .badge-active { background: rgba(16,185,129,0.1); color: var(--success); }
        .badge-inactive { background: rgba(79,78,92,0.2); color: var(--text-tertiary); }
        .badge-dot { width: 5px; height: 5px; border-radius: 50%; }
        .badge-active .badge-dot { background: var(--success); }
        .badge-inactive .badge-dot { background: var(--text-tertiary); }

        /* ── Empty State ── */
        .empty-state { text-align: center; padding: 56px 20px; }
        .empty-state-icon { width: 40px; height: 40px; color: var(--text-tertiary); margin: 0 auto 16px; opacity: 0.5; }
        .empty-state h3 { font-size: 15px; font-weight: 600; color: var(--text-primary); margin-bottom: 6px; }
        .empty-state p { font-size: 13px; color: var(--text-tertiary); margin-bottom: 20px; }

        /* ── Alerts ── */
        .alert {
            padding: 12px 16px; border-radius: 8px; font-size: 13px; font-weight: 500;
            margin-bottom: 20px; display: flex; align-items: center; gap: 8px;
        }
        .alert-success { background: rgba(16,185,129,0.08); border: 1px solid rgba(16,185,129,0.15); color: var(--success); }
        .alert-error { background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.15); color: var(--danger); }
        .alert-info { background: rgba(124,58,237,0.08); border: 1px solid rgba(124,58,237,0.15); color: var(--accent-light); }
        .alert-warning { background: rgba(245,158,11,0.08); border: 1px solid rgba(245,158,11,0.15); color: var(--warning); }

        /* ── Credential Rows ── */
        .cred-row {
            display: flex; align-items: center; gap: 12px;
            background: var(--bg-base); border: 1px solid var(--border);
            border-radius: 8px; padding: 12px 16px; margin-bottom: 8px;
        }
        .cred-info { flex: 1; min-width: 0; }
        .cred-label { font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-tertiary); margin-bottom: 4px; }
        .cred-value { font-family: var(--font-mono); font-size: 13px; color: var(--text-secondary); word-break: break-all; }
        .cred-blurred { filter: blur(5px); user-select: none; transition: filter 200ms; }
        .icon-btn {
            width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;
            background: none; border: 1px solid var(--border); border-radius: 6px;
            cursor: pointer; color: var(--text-tertiary); transition: all 150ms ease; flex-shrink: 0;
        }
        .icon-btn:hover { border-color: var(--border-strong); color: var(--text-secondary); }
        .icon-btn svg { width: 14px; height: 14px; }

        /* ── Code Blocks ── */
        .code-tabs { display: flex; border-bottom: 1px solid var(--border); }
        .code-tab {
            padding: 10px 18px; font-size: 12px; font-weight: 500; color: var(--text-tertiary);
            background: none; border: none; cursor: pointer; font-family: var(--font-sans);
            border-bottom: 2px solid transparent; transition: all 150ms ease;
        }
        .code-tab.active { color: var(--accent-light); border-bottom-color: var(--accent); }
        .code-tab:hover:not(.active) { color: var(--text-secondary); }
        .code-block {
            background: var(--bg-base); border: 1px solid var(--border); border-top: none;
            border-radius: 0 0 8px 8px; padding: 18px 20px; overflow-x: auto;
            position: relative;
        }
        .code-block pre { font-family: var(--font-mono); font-size: 12px; line-height: 1.7; color: var(--text-secondary); }
        .code-lang {
            position: absolute; top: 10px; right: 14px; font-size: 10px; font-weight: 600;
            text-transform: uppercase; color: var(--text-tertiary); letter-spacing: 0.04em;
        }

        /* ── Plan Cards ── */
        .plan-cards { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
        .plan-card {
            background: linear-gradient(145deg, #161620 0%, #111115 100%);
            border: 1px solid var(--border); border-top: 1px solid rgba(255,255,255,0.06);
            border-radius: 12px; padding: 28px; position: relative;
            box-shadow: var(--shadow-card); transition: all 150ms ease;
        }
        .plan-card.current { border-color: var(--accent); box-shadow: var(--shadow-card), 0 0 20px var(--accent-glow); }
        .plan-name { font-size: 18px; font-weight: 700; margin-bottom: 2px; }
        .plan-price { font-size: 32px; font-weight: 700; letter-spacing: -0.02em; margin-bottom: 2px; }
        .plan-price span { font-size: 14px; font-weight: 400; color: var(--text-tertiary); }
        .plan-desc { font-size: 13px; color: var(--text-tertiary); margin-bottom: 20px; }
        .plan-features { list-style: none; margin-bottom: 24px; }
        .plan-features li { font-size: 13px; color: var(--text-secondary); padding: 5px 0; display: flex; align-items: center; gap: 8px; }
        .plan-features li svg { width: 14px; height: 14px; color: var(--accent-light); flex-shrink: 0; }

        /* ── Progress Bars ── */
        .progress-bar { height: 6px; background: var(--border); border-radius: 3px; overflow: hidden; }
        .progress-fill { height: 100%; border-radius: 3px; transition: width 300ms; background: var(--success); }
        .progress-fill.amber { background: var(--warning); }
        .progress-fill.red { background: var(--danger); }

        /* ── Danger Zone ── */
        .danger-zone { border: 1px solid rgba(239,68,68,0.15); border-radius: 12px; padding: 24px; }
        .danger-zone h3 { color: var(--danger); font-size: 14px; font-weight: 600; margin-bottom: 6px; }
        .danger-zone p { color: var(--text-tertiary); font-size: 13px; margin-bottom: 14px; }

        /* ── Page Layout ── */
        .page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 32px; padding-bottom: 24px; border-bottom: 1px solid #1a1a22; }
        .page-title { font-size: 22px; font-weight: 700; letter-spacing: -0.02em; }
        .page-sub { font-size: 13px; color: var(--text-tertiary); margin-top: 2px; }

        /* ── Context Menu ── */
        .ctx-menu { position: relative; display: inline-block; }
        .ctx-trigger {
            width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;
            background: none; border: 1px solid transparent; border-radius: 6px;
            cursor: pointer; color: var(--text-tertiary); transition: all 150ms;
        }
        .ctx-trigger:hover { background: var(--bg-hover); border-color: var(--border); color: var(--text-secondary); }
        .ctx-trigger svg { width: 16px; height: 16px; }
        .ctx-dropdown {
            display: none; position: fixed; z-index: 200;
            background: var(--bg-elevated); border: 1px solid var(--border);
            border-radius: 10px; box-shadow: var(--shadow-elevated);
            min-width: 180px; padding: 4px;
        }
        .ctx-item {
            display: flex; align-items: center; gap: 8px; width: 100%;
            padding: 8px 12px; border-radius: 6px; font-size: 13px; font-weight: 500;
            color: var(--text-secondary); background: none; border: none;
            cursor: pointer; font-family: var(--font-sans); text-align: left;
            transition: all 150ms;
        }
        .ctx-item:hover { background: var(--bg-hover); color: var(--text-primary); }
        .ctx-item.danger { color: var(--danger); }
        .ctx-item.danger:hover { background: rgba(239,68,68,0.08); }
        .ctx-item svg { width: 14px; height: 14px; flex-shrink: 0; }
        .ctx-sep { height: 1px; background: var(--border); margin: 4px 0; }

        /* ── Tooltip ── */
        .tooltip {
            position: absolute; bottom: calc(100% + 6px); left: 50%; transform: translateX(-50%);
            background: var(--bg-elevated); border: 1px solid var(--border); border-radius: 6px;
            padding: 4px 10px; font-size: 11px; font-weight: 500; color: var(--text-primary);
            white-space: nowrap; pointer-events: none; opacity: 0; transition: opacity 150ms;
            box-shadow: var(--shadow-elevated);
        }
        .tooltip.show { opacity: 1; }

        /* ── Two Column ── */
        .two-col { display: grid; grid-template-columns: 1fr 340px; gap: 16px; }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            .sidebar { display: none; }
            .main { margin-left: 0; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .two-col { grid-template-columns: 1fr; }
            .plan-cards { grid-template-columns: 1fr; }
            .content { padding: 16px; }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/github-dark.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="sidebar-brand-top">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
                Relay Cloud
            </div>
            <div class="sidebar-brand-email">{{ Auth::user()->email }}</div>
        </div>
        <nav class="sidebar-nav">
            <div class="nav-section">Navigation</div>
            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                Dashboard
            </a>
            <a href="{{ route('projects.index') }}" class="nav-item {{ request()->routeIs('projects.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
                Projects
            </a>
            @php $inspectorProject = Auth::user()->projects()->latest()->first(); @endphp
            <a href="{{ $inspectorProject ? route('inspector.show', $inspectorProject) : route('projects.index') }}" class="nav-item {{ request()->routeIs('inspector.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                Inspector
            </a>
            <a href="{{ route('usage') }}" class="nav-item {{ request()->routeIs('usage') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                Usage
            </a>
            <a href="{{ route('activity') }}" class="nav-item {{ request()->routeIs('activity') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                Activity
            </a>
            <a href="{{ route('webhooks.index') }}" class="nav-item {{ request()->routeIs('webhooks.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                Webhooks
            </a>
            <a href="{{ route('billing.index') }}" class="nav-item {{ request()->routeIs('billing.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                Billing
            </a>
            <div class="nav-section">Resources</div>
            <a href="{{ route('docs') }}" class="nav-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                Documentation
            </a>
            <a href="https://github.com/DarkNautica/Relay" target="_blank" class="nav-item">
                <svg viewBox="0 0 24 24" fill="currentColor" style="width:16px;height:16px"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                GitHub
                <svg class="ext-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
            </a>
            <a href="{{ route('settings') }}" class="nav-item {{ request()->routeIs('settings*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                Settings
            </a>
        </nav>
        <div class="sidebar-footer">
            <div class="sidebar-footer-info">
                <span class="sidebar-plan">{{ Auth::user()->fresh()->plan ?? 'hobby' }}</span>
                <span class="sidebar-user">{{ Auth::user()->name }}</span>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                    Log out
                </button>
            </form>
        </div>
    </aside>

    <div class="main">
        <header class="top-bar">
            <div class="top-bar-left"></div>
            <div class="top-bar-right">
                @hasSection('server-status')
                    @yield('server-status')
                @endif
                <span class="top-badge">{{ Auth::user()->fresh()->plan ?? 'hobby' }}</span>
                <span class="top-user">{{ Auth::user()->name }}</span>
            </div>
        </header>
        <main class="content">
            @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
            @if(session('error'))<div class="alert alert-error">{{ session('error') }}</div>@endif
            @if(session('info'))<div class="alert alert-info">{{ session('info') }}</div>@endif
            @yield('content')
        </main>
    </div>

    <script>
    function copyToClipboard(text, btn) {
        navigator.clipboard.writeText(text);
        if (btn) {
            const tip = btn.querySelector('.tooltip');
            if (tip) { tip.classList.add('show'); setTimeout(() => tip.classList.remove('show'), 1500); }
        }
    }
    function toggleSecret() { document.getElementById('secret-value').classList.toggle('cred-blurred'); }
    document.addEventListener('click', () => {
        document.querySelectorAll('.ctx-dropdown').forEach(d => d.style.display = 'none');
    });
    function toggleCtx(el, e) {
        if (e) e.stopPropagation();
        const dd = el.closest('.ctx-menu').querySelector('.ctx-dropdown');
        const open = dd.style.display === 'block';
        document.querySelectorAll('.ctx-dropdown').forEach(d => d.style.display = 'none');
        if (open) return;
        dd.style.display = 'block';
        const rect = el.getBoundingClientRect();
        dd.style.top = (rect.bottom + 8) + 'px';
        dd.style.left = (rect.right - dd.offsetWidth) + 'px';
    }
    </script>
</body>
</html>

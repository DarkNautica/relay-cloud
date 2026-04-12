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
        body {
            font-family: 'Inter', system-ui, sans-serif;
            background: #0c0c0e; color: #f1f0f5;
            min-height: 100vh; display: flex; flex-direction: column;
            align-items: center; justify-content: center; padding: 24px;
            -webkit-font-smoothing: antialiased;
            background-image:
                radial-gradient(ellipse 600px 400px at 50% 0%, rgba(124,58,237,0.06) 0%, transparent 70%),
                linear-gradient(rgba(255,255,255,0.015) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.015) 1px, transparent 1px);
            background-size: 100% 100%, 48px 48px, 48px 48px;
        }
        a { color: #8b5cf6; text-decoration: none; }
        a:hover { color: #a78bfa; }
        .auth-brand {
            display: flex; align-items: center; gap: 10px;
            font-size: 20px; font-weight: 700; margin-bottom: 8px;
        }
        .auth-brand svg { width: 22px; height: 22px; color: #8b5cf6; }
        .auth-tagline { font-size: 13px; color: #4f4e5c; margin-bottom: 28px; }
        .auth-card {
            width: 100%; max-width: 400px;
            background: #111115; border: 1px solid #27272f;
            border-top: 1px solid rgba(255,255,255,0.06);
            border-radius: 12px; padding: 28px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.5);
        }
        .form-group { margin-bottom: 16px; }
        .form-label { display: block; font-size: 13px; font-weight: 500; color: #8b8a98; margin-bottom: 6px; }
        .form-input {
            width: 100%; padding: 9px 14px; background: #0c0c0e;
            border: 1px solid #27272f; border-radius: 8px; color: #f1f0f5;
            font-size: 14px; font-family: inherit; outline: none; transition: all 150ms;
        }
        .form-input:focus { border-color: #7c3aed; box-shadow: 0 0 0 3px rgba(124,58,237,0.12); }
        .form-error { color: #ef4444; font-size: 12px; margin-top: 4px; }
        .btn-submit {
            display: flex; align-items: center; justify-content: center;
            width: 100%; padding: 10px; border-radius: 8px; font-size: 14px;
            font-weight: 600; border: none; cursor: pointer; font-family: inherit;
            background: linear-gradient(135deg, #7c3aed, #6d28d9); color: #fff;
            transition: all 150ms;
        }
        .btn-submit:hover { background: linear-gradient(135deg, #8b5cf6, #7c3aed); box-shadow: 0 0 0 3px rgba(124,58,237,0.12); }
        .btn-submit:active { transform: scale(0.98); }
        .auth-footer { margin-top: 18px; text-align: center; font-size: 13px; color: #8b8a98; }
        .form-row {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 18px; font-size: 13px;
        }
        .form-row label { display: flex; align-items: center; gap: 6px; color: #8b8a98; cursor: pointer; }
        .form-row input[type="checkbox"] { accent-color: #7c3aed; width: 14px; height: 14px; }
        .alert-box {
            padding: 10px 14px; border-radius: 8px; font-size: 13px; margin-bottom: 16px;
        }
        .alert-success-box { background: rgba(16,185,129,0.08); border: 1px solid rgba(16,185,129,0.15); color: #10b981; }
    </style>
</head>
<body>
    <div class="auth-brand">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
        Relay Cloud
    </div>
    <div class="auth-tagline">Managed WebSocket infrastructure</div>
    <div class="auth-card">
        {{ $slot }}
    </div>
</body>
</html>

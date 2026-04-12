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
            background: #0a0a0f;
            color: #f8fafc;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
        a { color: #6366f1; text-decoration: none; }
        a:hover { color: #818cf8; }
        .auth-logo {
            display: flex; align-items: center; gap: 10px;
            font-size: 24px; font-weight: 700; margin-bottom: 32px;
        }
        .auth-logo .logo-icon {
            width: 40px; height: 40px;
            background: #6366f1; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
        }
        .auth-card {
            width: 100%; max-width: 420px;
            background: #111118;
            border: 1px solid #1e1e2e;
            border-radius: 12px;
            padding: 32px;
        }
        .form-group { margin-bottom: 20px; }
        .form-label {
            display: block; font-size: 14px; font-weight: 500;
            margin-bottom: 8px; color: #94a3b8;
        }
        .form-input {
            width: 100%; padding: 10px 14px;
            background: #0a0a0f; border: 1px solid #1e1e2e;
            border-radius: 8px; color: #f8fafc;
            font-size: 14px; font-family: inherit; outline: none;
            transition: border-color 0.15s;
        }
        .form-input:focus { border-color: #6366f1; }
        .form-error { color: #ef4444; font-size: 13px; margin-top: 6px; }
        .btn-primary {
            display: inline-flex; align-items: center; justify-content: center;
            width: 100%; padding: 10px 20px; border-radius: 8px;
            font-size: 14px; font-weight: 500; border: none;
            cursor: pointer; font-family: inherit;
            background: #6366f1; color: white;
            transition: background 0.15s;
        }
        .btn-primary:hover { background: #818cf8; }
        .auth-footer {
            margin-top: 20px; text-align: center;
            font-size: 14px; color: #94a3b8;
        }
        .form-row {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 20px; font-size: 14px;
        }
        .form-row label { display: flex; align-items: center; gap: 8px; color: #94a3b8; cursor: pointer; }
        .form-row input[type="checkbox"] { accent-color: #6366f1; }
    </style>
</head>
<body>
    <div class="auth-logo">
        <div class="logo-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:22px;height:22px"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
        </div>
        Relay Cloud
    </div>
    <div class="auth-card">
        {{ $slot }}
    </div>
</body>
</html>

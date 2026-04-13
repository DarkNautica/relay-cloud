<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Docs') - Relay Cloud</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
        :root{
            --bg:#0c0c0e;--surface:#111115;--elevated:#18181d;--hover:#1e1e25;
            --border:#27272f;--accent:#7c3aed;--accent-l:#8b5cf6;--accent-glow:rgba(124,58,237,0.12);
            --success:#10b981;--warn:#f59e0b;--danger:#ef4444;
            --t1:#f1f0f5;--t2:#8b8a98;--t3:#4f4e5c;
            --sans:'Inter',system-ui,sans-serif;--mono:'JetBrains Mono',monospace;
        }
        body{font-family:var(--sans);background:var(--bg);color:var(--t1);-webkit-font-smoothing:antialiased;
            background-image:radial-gradient(ellipse 80% 50% at 50% -20%,rgba(124,58,237,0.06) 0%,transparent 60%),
            url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none'%3E%3Cg fill='%23ffffff' fill-opacity='0.012'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        a{color:inherit;text-decoration:none}

        /* ── Navbar ── */
        .navbar{position:sticky;top:0;z-index:100;height:56px;display:flex;align-items:center;justify-content:space-between;padding:0 24px;background:rgba(12,12,14,0.92);backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);border-bottom:1px solid #1a1a22;box-shadow:0 1px 0 rgba(255,255,255,0.03)}
        .nav-left{display:flex;align-items:center;gap:28px}
        .nav-brand{display:flex;align-items:center;gap:8px;font-size:15px;font-weight:700;color:var(--t1)}
        .nav-brand svg{width:18px;height:18px;color:var(--accent-l)}
        .nav-links{display:flex;align-items:center;gap:20px}
        .nav-links a{font-size:13px;font-weight:500;color:var(--t2);transition:color 150ms}
        .nav-links a:hover,.nav-links a.active{color:var(--t1)}
        .nav-right{display:flex;align-items:center;gap:10px}
        .nav-btn{padding:7px 16px;border-radius:7px;font-size:13px;font-weight:500;border:none;cursor:pointer;font-family:var(--sans);transition:all 150ms}
        .nav-btn-ghost{background:transparent;color:var(--t2);border:1px solid var(--border)}
        .nav-btn-ghost:hover{border-color:var(--t2);color:var(--t1)}
        .nav-btn-primary{background:var(--accent);color:#fff}
        .nav-btn-primary:hover{background:var(--accent-l);box-shadow:0 0 0 3px var(--accent-glow)}

        /* ── Docs Layout ── */
        .docs-wrap{display:flex;min-height:calc(100vh - 56px)}
        .docs-sidebar{width:240px;position:sticky;top:56px;height:calc(100vh - 56px);overflow-y:auto;padding:20px 12px;border-right:1px solid var(--border);flex-shrink:0}
        .docs-sidebar::-webkit-scrollbar{width:4px}.docs-sidebar::-webkit-scrollbar-thumb{background:var(--border);border-radius:2px}
        .ds-section{font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:0.08em;color:var(--t3);padding:16px 12px 6px;display:flex;align-items:center;gap:6px}
        .ds-badge{font-size:9px;padding:1px 5px;border-radius:3px;background:rgba(124,58,237,0.15);color:var(--accent-l);font-weight:700}
        .ds-link{display:block;padding:7px 12px;border-radius:6px;font-size:13px;font-weight:500;color:var(--t2);transition:all 150ms;margin-bottom:1px}
        .ds-link:hover{background:var(--hover);color:var(--t1)}
        .ds-link.active{background:var(--accent-glow);color:var(--accent-l)}
        .docs-content{flex:1;max-width:780px;padding:32px 40px 60px;min-width:0}

        /* ── Prose ── */
        .prose h1{font-size:28px;font-weight:700;letter-spacing:-0.02em;margin-bottom:8px}
        .prose h2{font-size:20px;font-weight:700;letter-spacing:-0.01em;margin:36px 0 12px;padding-top:24px;border-top:1px solid var(--border)}
        .prose h3{font-size:16px;font-weight:600;margin:24px 0 8px}
        .prose h4{font-size:14px;font-weight:600;margin:20px 0 6px;color:var(--t2)}
        .prose p{font-size:14px;line-height:1.7;color:var(--t2);margin-bottom:14px}
        .prose strong{color:var(--t1);font-weight:600}
        .prose a{color:var(--accent-l)}
        .prose a:hover{text-decoration:underline}
        .prose ul,.prose ol{margin:0 0 14px 20px;font-size:14px;line-height:1.7;color:var(--t2)}
        .prose li{margin-bottom:4px}
        .prose code{font-family:var(--mono);font-size:12px;background:var(--elevated);padding:2px 6px;border-radius:4px;color:var(--accent-l)}
        .prose pre{background:var(--bg);border:1px solid var(--border);border-radius:8px;padding:16px 20px;overflow-x:auto;margin:0 0 16px;position:relative}
        .prose pre code{background:none;padding:0;font-size:12px;line-height:1.7;color:var(--t2)}
        .prose pre code.hljs{background:none;padding:0}
        .code-header{position:absolute;top:8px;right:10px;display:flex;align-items:center;gap:8px;z-index:2}
        .code-lang-badge{font-family:var(--mono);font-size:9px;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;color:var(--t3);padding:2px 6px;border-radius:3px;background:rgba(255,255,255,0.04)}
        .code-copy{padding:3px 10px;border-radius:5px;font-size:11px;font-weight:500;color:var(--t3);background:rgba(255,255,255,0.04);border:1px solid var(--border);cursor:pointer;font-family:var(--sans);transition:all 150ms}
        .code-copy:hover{color:var(--t2);border-color:var(--t3)}
        .prose table{width:100%;border-collapse:collapse;margin:0 0 16px;font-size:13px}
        .prose th{text-align:left;padding:10px 14px;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;color:var(--t3);border-bottom:1px solid var(--border)}
        .prose td{padding:10px 14px;border-bottom:1px solid var(--border);color:var(--t2)}
        .prose tr:last-child td{border-bottom:none}
        .prose .subtitle{font-size:16px;color:var(--t2);margin-bottom:24px}

        /* ── Tabs ── */
        .tabs{display:flex;border-bottom:1px solid var(--border);margin-bottom:0}
        .tab{padding:8px 16px;font-size:12px;font-weight:500;color:var(--t3);background:none;border:none;cursor:pointer;font-family:var(--sans);border-bottom:2px solid transparent;transition:all 150ms}
        .tab.active{color:var(--accent-l);border-bottom-color:var(--accent)}
        .tab:hover:not(.active){color:var(--t2)}
        .tab-panel{display:none}
        .tab-panel.active{display:block}

        /* ── Cloud Nudge ── */
        .cloud-nudge{display:flex;align-items:center;gap:14px;padding:14px 18px;margin:0 0 24px;background:linear-gradient(135deg,rgba(124,58,237,0.06),rgba(124,58,237,0.02));border:1px solid rgba(124,58,237,0.15);border-left:3px solid var(--accent);border-radius:8px;font-size:13px;color:var(--t2)}
        .cloud-nudge strong{color:var(--t1)}
        .cloud-nudge a{color:var(--accent-l);font-weight:600;margin-left:auto;white-space:nowrap}
        .cloud-nudge a:hover{text-decoration:underline}
        .cloud-nudge-icon{font-size:18px;flex-shrink:0}

        /* ── Note Box ── */
        .note{padding:14px 18px;border-radius:8px;font-size:13px;color:var(--t2);margin:0 0 16px;background:rgba(124,58,237,0.06);border:1px solid rgba(124,58,237,0.12)}
        .note strong{color:var(--t1)}

        /* ── Step ── */
        .step{display:flex;align-items:center;gap:10px;margin:28px 0 10px}
        .step-num{width:28px;height:28px;display:flex;align-items:center;justify-content:center;border-radius:50%;background:var(--accent-glow);color:var(--accent-l);font-size:13px;font-weight:700;border:1px solid rgba(124,58,237,0.2);flex-shrink:0}
        .step h2{margin:0;padding:0;border:none;font-size:16px}

        /* ── Footer ── */
        .docs-footer{border-top:1px solid var(--border);padding:32px 24px;text-align:center}
        .docs-footer-brand{display:flex;align-items:center;justify-content:center;gap:8px;font-size:14px;font-weight:600;margin-bottom:12px}
        .docs-footer-brand svg{width:16px;height:16px;color:var(--accent-l)}
        .docs-footer-links{display:flex;justify-content:center;gap:20px;font-size:13px;color:var(--t3);margin-bottom:8px}
        .docs-footer-links a{color:var(--t2)}.docs-footer-links a:hover{color:var(--t1)}
        .docs-footer-copy{font-size:12px;color:var(--t3)}

        @media(max-width:768px){.docs-sidebar{display:none}.docs-content{padding:20px 16px}}
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/github-dark.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
</head>
<body>
    <nav class="navbar">
        <div class="nav-left">
            <a href="/" class="nav-brand">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
                Relay Cloud
            </a>
            <div class="nav-links">
                <a href="{{ route('docs') }}" class="{{ request()->is('docs*') ? 'active' : '' }}">Docs</a>
                <a href="{{ route('blog') }}" class="{{ request()->is('blog*') ? 'active' : '' }}">Blog</a>
                <a href="https://github.com/DarkNautica/Relay" target="_blank">GitHub</a>
            </div>
        </div>
        <div class="nav-right">
            <a href="{{ route('docs.os.getting-started') }}" class="nav-btn nav-btn-ghost">Self-Host Free</a>
            <a href="{{ route('register') }}" class="nav-btn nav-btn-primary">Start Free</a>
        </div>
    </nav>

    @hasSection('no-sidebar')
        @yield('content')
    @else
    <div class="docs-wrap">
        <aside class="docs-sidebar">
            <div class="ds-section">Open Source</div>
            <a href="{{ route('docs.os.getting-started') }}" class="ds-link {{ request()->routeIs('docs.os.getting-started') ? 'active' : '' }}">Getting Started</a>
            <a href="{{ route('docs.os.configuration') }}" class="ds-link {{ request()->routeIs('docs.os.configuration') ? 'active' : '' }}">Configuration</a>
            <a href="{{ route('docs.os.api-reference') }}" class="ds-link {{ request()->routeIs('docs.os.api-reference') ? 'active' : '' }}">API Reference</a>
            <a href="{{ route('docs.os.sdks') }}" class="ds-link {{ request()->routeIs('docs.os.sdks') ? 'active' : '' }}">SDKs</a>
            <div class="ds-section">Framework Guides</div>
            <a href="{{ route('docs.guides.nextjs') }}" class="ds-link {{ request()->routeIs('docs.guides.nextjs') ? 'active' : '' }}">Next.js</a>
            <a href="{{ route('docs.guides.rails') }}" class="ds-link {{ request()->routeIs('docs.guides.rails') ? 'active' : '' }}">Rails</a>
            <a href="{{ route('docs.guides.django') }}" class="ds-link {{ request()->routeIs('docs.guides.django') ? 'active' : '' }}">Django</a>
            <a href="{{ route('docs.guides.node') }}" class="ds-link {{ request()->routeIs('docs.guides.node') ? 'active' : '' }}">Node.js</a>
            <a href="{{ route('docs.guides.pusher-sdks') }}" class="ds-link {{ request()->routeIs('docs.guides.pusher-sdks') ? 'active' : '' }}">All Pusher SDKs</a>
            <div class="ds-section">Cloud</div>
            <a href="{{ route('docs.cloud.getting-started') }}" class="ds-link {{ request()->routeIs('docs.cloud.getting-started') ? 'active' : '' }}">Getting Started</a>
            <a href="{{ route('docs.cloud.projects') }}" class="ds-link {{ request()->routeIs('docs.cloud.projects') ? 'active' : '' }}">Projects</a>
            <a href="{{ route('docs.cloud.billing') }}" class="ds-link {{ request()->routeIs('docs.cloud.billing') ? 'active' : '' }}">Billing</a>
            <div class="ds-section">Comparisons</div>
            <a href="{{ route('docs.vs-reverb') }}" class="ds-link {{ request()->routeIs('docs.vs-reverb') ? 'active' : '' }}">vs Reverb</a>
        </aside>
        <main class="docs-content prose">
            @yield('content')
        </main>
    </div>
    @endif

    <footer class="docs-footer">
        <div class="docs-footer-brand">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
            Relay Cloud
        </div>
        <div class="docs-footer-links">
            <a href="{{ route('docs') }}">Docs</a>
            <a href="https://github.com/DarkNautica/Relay" target="_blank">GitHub</a>
            <a href="{{ route('docs.os.getting-started') }}">Self-Hosting</a>
            <a href="{{ route('docs.os.api-reference') }}">API Reference</a>
            <a href="{{ route('blog') }}">Blog</a>
        </div>
        <div class="docs-footer-copy">MIT Licensed &middot; Open Source</div>
    </footer>

    <script>
    function showDocTab(group,name,btn){
        document.querySelectorAll('[data-tab-group="'+group+'"]').forEach(p=>p.classList.remove('active'));
        document.querySelectorAll('[data-tab-btn="'+group+'"]').forEach(b=>b.classList.remove('active'));
        document.querySelector('[data-tab-group="'+group+'"][data-tab="'+name+'"]').classList.add('active');
        btn.classList.add('active');
    }
    document.addEventListener('DOMContentLoaded',()=>{
        const nameMap={bash:'bash',shell:'bash',php:'php',javascript:'js',js:'js',json:'json',yaml:'yaml',yml:'yaml',ruby:'ruby',python:'python',html:'html',xml:'xml',css:'css',sql:'sql',ini:'env',dockerfile:'docker'};
        document.querySelectorAll('pre code').forEach(el=>{
            hljs.highlightElement(el);
            const pre=el.closest('pre');
            if(!pre||pre.querySelector('.code-header'))return;
            let lang='';
            const m=el.className.match(/language-(\w+)/);
            if(m)lang=nameMap[m[1]]||m[1];
            if(!lang&&el.result&&el.result.language)lang=nameMap[el.result.language]||el.result.language;
            if(!lang){const cls=Array.from(el.classList).find(c=>c.startsWith('hljs')&&c!=='hljs');if(cls)lang='';}
            const hdr=document.createElement('div');hdr.className='code-header';
            if(lang){const badge=document.createElement('span');badge.className='code-lang-badge';badge.textContent=lang;hdr.appendChild(badge);}
            const btn=document.createElement('button');btn.className='code-copy';btn.textContent='Copy';
            btn.onclick=()=>{navigator.clipboard.writeText(el.textContent);btn.textContent='Copied!';setTimeout(()=>btn.textContent='Copy',1500);};
            hdr.appendChild(btn);pre.appendChild(hdr);
        });
    });
    </script>
</body>
</html>

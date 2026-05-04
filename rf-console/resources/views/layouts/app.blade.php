<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Dashboard') | RF Console</title>
  <link rel="preconnect" href="https://rsms.me/">
  <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: { extend: {
        colors: {
          navy: { 950:'#04080F', 900:'#070F1F', 800:'#0B1830', 700:'#122446', 600:'#1A325F' },
          gold: { 300:'#F8D47A', 400:'#F5C24D', 500:'#E5A92E', 600:'#B8851D' },
          signal: { 300:'#7DE5F2', 400:'#22D3EE', 500:'#06B6D4' },
          anomaly: { 400:'#F87171', 500:'#EF4444', 600:'#DC2626' },
        },
        fontFamily: {
          sans: ['Inter','system-ui','sans-serif'],
          mono: ['JetBrains Mono','ui-monospace','monospace'],
        },
        boxShadow: {
          panel: '0 0 0 1px rgba(148,163,184,0.08), 0 12px 32px -16px rgba(0,0,0,0.6)',
          glow: '0 0 24px -4px rgba(34,211,238,0.45)',
        },
      } }
    }
  </script>
  <style>
    body { font-family: Inter, system-ui, sans-serif; }
    .grid-bg { background-image: linear-gradient(rgba(34,211,238,0.05) 1px, transparent 1px), linear-gradient(90deg, rgba(34,211,238,0.05) 1px, transparent 1px); background-size: 32px 32px; }
    .scrollbar-thin::-webkit-scrollbar { height: 6px; width: 6px; }
    .scrollbar-thin::-webkit-scrollbar-thumb { background: rgba(148,163,184,0.25); border-radius: 4px; }
  </style>
</head>
<body class="h-full bg-navy-950 text-slate-200 antialiased grid-bg">
  <div class="flex min-h-screen">
    @include('partials.sidebar')
    <div class="flex-1 flex flex-col min-w-0">
      @include('partials.topbar')
      <main class="flex-1 p-6 space-y-6">
        @yield('content')
      </main>
      <footer class="px-6 py-3 border-t border-slate-800/60 text-[11px] text-slate-500 flex items-center justify-between">
        <div>RF Console - Laravel Blade frontend for MISPrf - schema owned by MISP</div>
        <div class="font-mono">UTC {{ now()->utc()->format('H:i:s') }}</div>
      </footer>
    </div>
  </div>
</body>
</html>

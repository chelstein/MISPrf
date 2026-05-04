<header class="h-14 px-6 flex items-center justify-between border-b border-slate-800/60 bg-navy-900/60 backdrop-blur">
  <div class="flex items-center gap-3">
    <div class="text-[10px] tracking-[0.2em] text-slate-500">@yield('eyebrow', 'OPERATIONAL')</div>
    <span class="text-slate-700">/</span>
    <h1 class="text-base font-semibold text-slate-100">@yield('title', 'Dashboard')</h1>
  </div>
  <div class="flex items-center gap-4 text-xs">
    <div class="flex items-center gap-2 text-slate-400">
      <span class="h-2 w-2 rounded-full bg-signal-400 animate-pulse"></span>
      MISP backend: {{ config('services.misp.use_mock') ? 'mock' : 'live' }}
    </div>
    <div class="text-slate-500 font-mono">{{ now()->utc()->format('Y-m-d H:i') }}Z</div>
    <div class="h-7 w-7 rounded-full bg-navy-700 grid place-items-center text-[11px] text-slate-300">OP</div>
  </div>
</header>

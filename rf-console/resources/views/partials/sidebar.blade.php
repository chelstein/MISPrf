<aside class="w-60 shrink-0 border-r border-slate-800/60 bg-navy-900/80 backdrop-blur">
  <div class="px-5 py-5 flex items-center gap-3 border-b border-slate-800/60">
    <div class="h-9 w-9 rounded-md bg-gradient-to-br from-signal-500 to-gold-500 grid place-items-center text-navy-950 font-bold">RF</div>
    <div>
      <div class="text-sm font-semibold text-slate-100">RF Console</div>
      <div class="text-[10px] uppercase tracking-[0.2em] text-slate-500">MISPrf - v0.1</div>
    </div>
  </div>
  <nav class="px-3 py-4 space-y-0.5 text-sm">
    @php
      $nav = [
        ['route'=>'dashboard','label'=>'Dashboard','grp'=>'OPS'],
        ['route'=>'events.index','label'=>'RF Events','grp'=>'OPS'],
        ['route'=>'attributes.index','label'=>'Signal Attributes','grp'=>'INTEL'],
        ['route'=>'frequencies.index','label'=>'Frequencies','grp'=>'INTEL'],
        ['route'=>'locations.index','label'=>'Locations','grp'=>'INTEL'],
        ['route'=>'sources.index','label'=>'Sources','grp'=>'INTEL'],
        ['route'=>'compliance.index','label'=>'Compliance','grp'=>'REG'],
        ['route'=>'admin.index','label'=>'Admin Settings','grp'=>'SYS'],
      ];
      $lastGrp = null;
    @endphp
    @foreach($nav as $item)
      @if($item['grp'] !== $lastGrp)
        <div class="px-3 pt-3 pb-1 text-[10px] tracking-[0.2em] text-slate-600">{{ $item['grp'] }}</div>
        @php $lastGrp = $item['grp']; @endphp
      @endif
      @php
        $active = request()->routeIs($item['route']) || ($item['route']==='events.index' && request()->routeIs('events.*'));
      @endphp
      <a href="{{ route($item['route']) }}"
         class="flex items-center gap-2 px-3 py-2 rounded-md transition {{ $active ? 'bg-signal-500/10 text-signal-300 ring-1 ring-signal-400/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-slate-200' }}">
        <span class="h-1.5 w-1.5 rounded-full {{ $active ? 'bg-signal-400 shadow-glow' : 'bg-slate-600' }}"></span>
        {{ $item['label'] }}
      </a>
    @endforeach
  </nav>
  <div class="mx-3 mt-6 mb-4 rounded-md border border-slate-800/60 bg-navy-800/60 p-3">
    <div class="text-[10px] tracking-[0.2em] text-slate-500">SHARING SCOPE</div>
    <div class="mt-1.5 text-sm text-gold-400 font-medium">Community</div>
    <div class="mt-2 text-[11px] text-slate-500">MISP distribution: 1 (this community only)</div>
  </div>
</aside>

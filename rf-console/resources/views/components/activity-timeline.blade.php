@props(['items','title'=>'Activity'])
<div class="rounded-lg border border-slate-800/60 bg-navy-800/60 p-4">
  <div class="flex items-center justify-between mb-3">
    <div class="text-[10px] tracking-[0.2em] uppercase text-slate-500">{{ $title }}</div>
  </div>
  <ol class="relative space-y-3 pl-4 border-l border-slate-700/50">
    @foreach($items as $it)
      @php
        $kind = $it['kind'] ?? 'event';
        $dot = match($kind) {
          'sighting' => 'bg-signal-400',
          'classification' => 'bg-gold-400',
          'correlation' => 'bg-purple-400',
          'created' => 'bg-slate-400',
          default => 'bg-slate-500',
        };
        $text = $it['text'] ?? $it['detail'] ?? '';
        $sub = $it['source'] ?? ($it['event'] ?? null);
      @endphp
      <li class="relative">
        <span class="absolute -left-[21px] top-1.5 h-2.5 w-2.5 rounded-full ring-2 ring-navy-800 {{ $dot }}"></span>
        <div class="flex items-baseline justify-between gap-3">
          <div class="text-sm text-slate-200">{{ $text }}</div>
          <div class="font-mono text-[11px] text-slate-500">{{ $it['ts'] }}</div>
        </div>
        @if($sub)
          <div class="text-[11px] text-slate-500">{{ $sub }}</div>
        @endif
      </li>
    @endforeach
  </ol>
</div>

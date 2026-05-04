@props(['locations'])
<div class="rounded-lg border border-slate-800/60 bg-navy-800/60 p-4">
  <div class="flex items-center justify-between mb-3">
    <div class="text-[10px] tracking-[0.2em] uppercase text-slate-500">Source Sites</div>
    <div class="text-[11px] font-mono text-slate-500">{{ count($locations) }} sites</div>
  </div>
  <svg viewBox="0 0 360 180" class="w-full bg-navy-900/60 rounded ring-1 ring-slate-800/60" style="height: 220px">
    <g fill="none" stroke="rgba(34,211,238,0.08)" stroke-width="0.4">
      @for($x=0;$x<=360;$x+=30)<line x1="{{ $x }}" y1="0" x2="{{ $x }}" y2="180"/>@endfor
      @for($y=0;$y<=180;$y+=30)<line x1="0" y1="{{ $y }}" x2="360" y2="{{ $y }}"/>@endfor
    </g>
    <line x1="0" y1="90" x2="360" y2="90" stroke="rgba(245,194,77,0.25)" stroke-width="0.6" stroke-dasharray="2,3"/>
    @foreach($locations as $loc)
      @php
        $cx = ($loc['lon'] + 180);
        $cy = (90 - $loc['lat']);
        $color = $loc['status']==='live' ? '#22D3EE' : ($loc['status']==='degraded' ? '#F5C24D' : '#F87171');
      @endphp
      <circle cx="{{ $cx }}" cy="{{ $cy }}" r="3" fill="{{ $color }}" opacity="0.4">
        <animate attributeName="r" values="3;7;3" dur="2.5s" repeatCount="indefinite"/>
        <animate attributeName="opacity" values="0.4;0;0.4" dur="2.5s" repeatCount="indefinite"/>
      </circle>
      <circle cx="{{ $cx }}" cy="{{ $cy }}" r="1.6" fill="{{ $color }}"/>
    @endforeach
  </svg>
  <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-2 text-[11px]">
    @foreach($locations as $loc)
      <div class="flex items-center justify-between rounded bg-navy-900/50 px-2.5 py-1.5">
        <div class="flex items-center gap-2">
          <span class="h-1.5 w-1.5 rounded-full {{ $loc['status']==='live' ? 'bg-signal-400' : ($loc['status']==='degraded' ? 'bg-amber-300' : 'bg-anomaly-400') }}"></span>
          <span class="text-slate-300">{{ $loc['name'] }}</span>
        </div>
        <span class="font-mono text-slate-500">{{ number_format($loc['lat'],2) }}, {{ number_format($loc['lon'],2) }}</span>
      </div>
    @endforeach
  </div>
</div>

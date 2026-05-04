@props(['bins','title'=>'Spectrum','range'=>'156.0 - 158.4 MHz','height'=>140])
@php
$min = -100; $max = -50;
$count = max(count($bins), 1);
$w = 100 / $count;
@endphp
<div class="rounded-lg border border-slate-800/60 bg-navy-800/60 p-4">
  <div class="flex items-center justify-between mb-3">
    <div class="text-[10px] tracking-[0.2em] uppercase text-slate-500">{{ $title }}</div>
    <div class="flex items-center gap-3 text-[11px] font-mono text-slate-500">
      <span class="flex items-center gap-1.5"><span class="h-1.5 w-1.5 rounded-full bg-signal-400"></span>signal</span>
      <span class="flex items-center gap-1.5"><span class="h-1.5 w-1.5 rounded-full bg-anomaly-500"></span>peak</span>
      <span>{{ $range }}</span>
    </div>
  </div>
  <svg viewBox="0 0 100 {{ $height }}" preserveAspectRatio="none" class="w-full" style="height: {{ $height }}px">
    <defs>
      <linearGradient id="specGrad" x1="0" y1="0" x2="0" y2="1">
        <stop offset="0%" stop-color="#22D3EE" stop-opacity="0.85"/>
        <stop offset="100%" stop-color="#22D3EE" stop-opacity="0.05"/>
      </linearGradient>
    </defs>
    <g stroke="rgba(148,163,184,0.08)" stroke-width="0.3">
      <line x1="0" y1="{{ $height*0.25 }}" x2="100" y2="{{ $height*0.25 }}"/>
      <line x1="0" y1="{{ $height*0.5 }}" x2="100" y2="{{ $height*0.5 }}"/>
      <line x1="0" y1="{{ $height*0.75 }}" x2="100" y2="{{ $height*0.75 }}"/>
    </g>
    @foreach($bins as $i => $bin)
      @php
        $p = max($min, min($max, (float)$bin['p']));
        $h = ($p - $min) / ($max - $min) * $height;
        $y = $height - $h;
        $isPeak = (float)$bin['p'] > -55;
      @endphp
      <rect x="{{ $i*$w }}" y="{{ $y }}" width="{{ $w*0.85 }}" height="{{ $h }}" fill="{{ $isPeak ? '#F87171' : 'url(#specGrad)' }}" opacity="{{ $isPeak ? 0.95 : 0.85 }}"/>
    @endforeach
  </svg>
  <div class="mt-2 flex justify-between text-[10px] font-mono text-slate-500">
    <span>-100 dBm</span><span>noise floor -85</span><span>-50 dBm</span>
  </div>
</div>

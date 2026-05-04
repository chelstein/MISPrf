@props(['value'=>0,'label'=>'Confidence'])
@php
$pct = max(0, min(100, (float)$value*100));
$bar = $pct >= 90 ? 'bg-gold-400' : ($pct >= 70 ? 'bg-signal-400' : ($pct >= 40 ? 'bg-amber-400' : 'bg-anomaly-500'));
@endphp
<div {{ $attributes->merge(['class'=>'w-full']) }}>
  <div class="flex items-center justify-between text-[10px] tracking-[0.2em] uppercase text-slate-500">
    <span>{{ $label }}</span>
    <span class="font-mono text-slate-300">{{ number_format($pct,0) }}%</span>
  </div>
  <div class="mt-1.5 h-1.5 w-full rounded-full bg-slate-800/80 overflow-hidden">
    <div class="h-full {{ $bar }}" style="width: {{ $pct }}%"></div>
  </div>
</div>

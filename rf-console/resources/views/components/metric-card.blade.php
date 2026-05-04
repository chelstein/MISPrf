@props(['label','value','delta'=>'','tone'=>'slate'])
@php
$toneText = match($tone) {
  'gold' => 'text-gold-400',
  'signal' => 'text-signal-400',
  'anomaly' => 'text-anomaly-500',
  default => 'text-slate-100',
};
$toneRing = match($tone) {
  'gold' => 'ring-gold-500/20',
  'signal' => 'ring-signal-500/20',
  'anomaly' => 'ring-anomaly-500/30',
  default => 'ring-slate-700/40',
};
@endphp
<div {{ $attributes->merge(['class' => "rounded-lg border border-slate-800/60 bg-navy-800/70 p-4 ring-1 $toneRing shadow-panel"]) }}>
  <div class="text-[10px] tracking-[0.2em] uppercase text-slate-500">{{ $label }}</div>
  <div class="mt-2 flex items-end justify-between gap-2">
    <div class="font-mono text-3xl font-semibold {{ $toneText }}">{{ $value }}</div>
    @if($delta)
      <div class="text-[11px] text-slate-400">{{ $delta }}</div>
    @endif
  </div>
</div>

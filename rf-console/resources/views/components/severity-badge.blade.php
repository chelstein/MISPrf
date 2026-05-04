@props(['severity'=>'info'])
@php
$key = strtolower((string)$severity);
$cls = match($key) {
  'critical' => 'bg-anomaly-500/15 text-anomaly-400 ring-anomaly-500/30',
  'high' => 'bg-anomaly-500/10 text-anomaly-400 ring-anomaly-500/25',
  'medium' => 'bg-amber-500/10 text-amber-300 ring-amber-500/25',
  'low' => 'bg-signal-500/10 text-signal-300 ring-signal-500/25',
  'info' => 'bg-slate-500/10 text-slate-300 ring-slate-500/25',
  'verified' => 'bg-gold-500/10 text-gold-400 ring-gold-500/25',
  'observed' => 'bg-signal-500/10 text-signal-300 ring-signal-500/25',
  'live' => 'bg-signal-500/10 text-signal-300 ring-signal-500/25',
  'anomaly' => 'bg-anomaly-500/15 text-anomaly-400 ring-anomaly-500/30',
  'open' => 'bg-anomaly-500/10 text-anomaly-400 ring-anomaly-500/25',
  'investigating' => 'bg-amber-500/10 text-amber-300 ring-amber-500/25',
  'degraded' => 'bg-amber-500/10 text-amber-300 ring-amber-500/25',
  'closed' => 'bg-slate-700/40 text-slate-400 ring-slate-600/40',
  default => 'bg-slate-500/10 text-slate-300 ring-slate-500/25',
};
@endphp
<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-[10px] font-mono uppercase tracking-[0.15em] ring-1 $cls"]) }}>
  <span class="h-1.5 w-1.5 rounded-full bg-current opacity-70"></span>{{ $severity }}
</span>

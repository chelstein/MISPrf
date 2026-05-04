@extends('layouts.app')
@section('title','RF Events')
@section('eyebrow','OPS - INVENTORY')
@section('content')
  <div class="flex items-start justify-between gap-4 flex-wrap">
    <p class="text-sm text-slate-400 max-w-xl">All MISP events surfaced as <span class="text-gold-400">RF Events</span>. Filtering, severity, sharing scope and source come straight from MISP and are rendered with regulatory-grade emphasis.</p>
    <div class="flex gap-2 text-[11px]">
      <button class="px-3 py-1.5 rounded-md bg-navy-800 ring-1 ring-slate-700 text-slate-300">All</button>
      <button class="px-3 py-1.5 rounded-md bg-anomaly-500/15 text-anomaly-400 ring-1 ring-anomaly-500/30">Anomalies</button>
      <button class="px-3 py-1.5 rounded-md bg-gold-500/10 text-gold-400 ring-1 ring-gold-500/25">Verified</button>
      <button class="px-3 py-1.5 rounded-md bg-signal-500/10 text-signal-300 ring-1 ring-signal-500/25">Observed</button>
    </div>
  </div>
  <x-event-table :events="$events" />
@endsection

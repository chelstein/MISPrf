@extends('layouts.app')
@section('title','Sources')
@section('eyebrow','INTEL - COLLECTION')
@section('content')
  <p class="text-sm text-slate-400 max-w-2xl">Collection sources feeding the MISP backend. Reliability is the rolling MISP source-confidence score; degraded sources are surfaced in amber so operators can triage before correlations are trusted.</p>
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    @foreach($sources as $s)
      <div class="rounded-lg border border-slate-800/60 bg-navy-800/60 p-4">
        <div class="flex items-center justify-between">
          <div class="min-w-0">
            <div class="font-mono text-signal-300 text-sm truncate">{{ $s['id'] }}</div>
            <div class="text-base text-slate-100 font-medium truncate">{{ $s['name'] }}</div>
          </div>
          <x-severity-badge :severity="$s['status']"/>
        </div>
        <dl class="mt-3 grid grid-cols-2 gap-2 text-[11px]">
          <div class="rounded bg-navy-900/50 px-2.5 py-1.5"><dt class="text-slate-500">Type</dt><dd class="text-slate-200">{{ $s['kind'] }}</dd></div>
          <div class="rounded bg-navy-900/50 px-2.5 py-1.5"><dt class="text-slate-500">Org</dt><dd class="text-slate-200">{{ $s['org'] }}</dd></div>
          <div class="rounded bg-navy-900/50 px-2.5 py-1.5"><dt class="text-slate-500">Last seen</dt><dd class="font-mono text-slate-200">{{ $s['last'] }}</dd></div>
          <div class="rounded bg-navy-900/50 px-2.5 py-1.5"><dt class="text-slate-500">Reliability</dt><dd class="font-mono text-gold-400">{{ number_format($s['reliability']*100,0) }}%</dd></div>
        </dl>
        <div class="mt-3"><x-confidence-meter :value="$s['reliability']" label="Reliability"/></div>
      </div>
    @endforeach
  </div>
@endsection

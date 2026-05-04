@extends('layouts.app')
@section('title','Admin Settings')
@section('eyebrow','SYS - CONFIG')
@section('content')
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
    <div class="lg:col-span-2 rounded-lg border border-slate-800/60 bg-navy-800/60 p-5">
      <div class="flex items-center justify-between mb-3">
        <div class="text-[10px] tracking-[0.2em] uppercase text-slate-500">MISP Backend</div>
        @if($reachable)
          <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-[10px] font-mono uppercase tracking-[0.15em] ring-1 bg-gold-500/10 text-gold-400 ring-gold-500/25"><span class="h-1.5 w-1.5 rounded-full bg-current"></span>reachable</span>
        @else
          <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-[10px] font-mono uppercase tracking-[0.15em] ring-1 bg-anomaly-500/10 text-anomaly-400 ring-anomaly-500/30"><span class="h-1.5 w-1.5 rounded-full bg-current"></span>unreachable</span>
        @endif
      </div>
      <dl class="grid grid-cols-2 gap-3 text-sm">
        <div class="rounded bg-navy-900/50 px-3 py-2"><dt class="text-[10px] uppercase tracking-[0.2em] text-slate-500">Mode</dt><dd class="mt-1 font-mono {{ $misp['use_mock'] ? 'text-signal-400' : 'text-gold-400' }}">{{ $misp['use_mock'] ? 'MOCK' : 'LIVE' }}</dd></div>
        <div class="rounded bg-navy-900/50 px-3 py-2"><dt class="text-[10px] uppercase tracking-[0.2em] text-slate-500">Base URL</dt><dd class="mt-1 font-mono text-slate-200 truncate">{{ $misp['base_url'] ?: '(unset)' }}</dd></div>
        <div class="rounded bg-navy-900/50 px-3 py-2"><dt class="text-[10px] uppercase tracking-[0.2em] text-slate-500">API Key</dt><dd class="mt-1 font-mono {{ $misp['api_key_set'] ? 'text-gold-400' : 'text-anomaly-400' }}">{{ $misp['api_key_set'] ? 'configured' : 'missing' }}</dd></div>
        <div class="rounded bg-navy-900/50 px-3 py-2"><dt class="text-[10px] uppercase tracking-[0.2em] text-slate-500">Verify TLS</dt><dd class="mt-1 font-mono {{ $misp['verify_tls'] ? 'text-gold-400' : 'text-amber-300' }}">{{ $misp['verify_tls'] ? 'on' : 'off' }}</dd></div>
        <div class="rounded bg-navy-900/50 px-3 py-2"><dt class="text-[10px] uppercase tracking-[0.2em] text-slate-500">Server version</dt><dd class="mt-1 font-mono text-slate-200">{{ $version['version'] ?? 'n/a' }}</dd></div>
        <div class="rounded bg-navy-900/50 px-3 py-2"><dt class="text-[10px] uppercase tracking-[0.2em] text-slate-500">perm_sync</dt><dd class="mt-1 font-mono text-slate-200">{{ array_key_exists('perm_sync', $version ?? []) ? ($version['perm_sync'] ? 'yes' : 'no') : 'n/a' }}</dd></div>
      </dl>
      @if($error)
        <div class="mt-4 rounded border border-anomaly-500/30 bg-anomaly-500/5 p-3 text-[12px] text-anomaly-400 font-mono">{{ $error }}</div>
      @endif
      <p class="mt-4 text-[12px] text-slate-500 leading-relaxed">MISP remains the source of truth for events, attributes, objects, tags, galaxies, sightings, and correlations. rf-console reads (and, when authorised, writes) only through documented MISP REST endpoints. The schema is owned by MISP. Run <span class="font-mono text-slate-300">php artisan misp:ping</span> from the server to reproduce this probe on the CLI.</p>
    </div>
    <div class="rounded-lg border border-slate-800/60 bg-navy-800/60 p-5">
      <div class="text-[10px] tracking-[0.2em] uppercase text-slate-500 mb-3">Term Mapping</div>
      <ul class="text-[12px] divide-y divide-slate-800/60">
        @php $map = [['Events','RF Events'],['Attributes','Signal Attributes'],['Objects','Signal Objects'],['Tags','Classifications'],['Galaxies','RF Taxonomies'],['Correlations','RF Correlations'],['Sightings','Signal Sightings'],['Threat level','Signal severity'],['Distribution','Sharing scope']]; @endphp
        @foreach($map as $row)
          <li class="py-1.5 flex items-center justify-between">
            <span class="text-slate-400 font-mono">{{ $row[0] }}</span>
            <span class="text-gold-400">{{ $row[1] }}</span>
          </li>
        @endforeach
      </ul>
    </div>
  </div>
@endsection

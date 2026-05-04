@extends('layouts.app')
@section('title','RF Event ' . $event['id'])
@section('eyebrow','OPS - EVENT DETAIL')
@section('content')
  <div class="rounded-lg border border-slate-800/60 bg-navy-800/60 p-5">
    <div class="flex items-start justify-between gap-6 flex-wrap">
      <div class="min-w-0">
        <div class="flex items-center gap-3 flex-wrap">
          <div class="font-mono text-signal-300 text-lg">{{ $event['id'] }}</div>
          <x-severity-badge :severity="$event['severity']" />
          <x-severity-badge :severity="$event['status']" />
          <span class="text-[11px] tracking-[0.2em] uppercase text-slate-500">Sharing scope: {{ $event['sharing'] }}</span>
        </div>
        <h2 class="mt-2 text-xl text-slate-100">{{ $event['info'] }}</h2>
        <div class="mt-3 flex flex-wrap gap-1.5">
          @foreach($event['tags'] as $t)
            <span class="px-2 py-0.5 rounded text-[10px] font-mono uppercase tracking-[0.15em] bg-navy-900/70 text-slate-300 ring-1 ring-slate-700/60">#{{ $t }}</span>
          @endforeach
        </div>
      </div>
      <div class="w-72 shrink-0 space-y-3">
        <x-confidence-meter :value="$event['confidence']" label="Signal Confidence"/>
        <div class="grid grid-cols-2 gap-2 text-[11px]">
          <div class="rounded bg-navy-900/50 px-2.5 py-1.5"><div class="text-slate-500">Frequency</div><div class="font-mono text-slate-200">{{ $event['frequency'] }}</div></div>
          <div class="rounded bg-navy-900/50 px-2.5 py-1.5"><div class="text-slate-500">Band</div><div class="text-slate-200">{{ $event['band'] }}</div></div>
          <div class="rounded bg-navy-900/50 px-2.5 py-1.5"><div class="text-slate-500">Source</div><div class="text-slate-200">{{ $event['source'] }}</div></div>
          <div class="rounded bg-navy-900/50 px-2.5 py-1.5"><div class="text-slate-500">Updated</div><div class="font-mono text-slate-200">{{ $event['updated'] }}</div></div>
        </div>
      </div>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
    <div class="lg:col-span-2 space-y-4">
      <x-spectrum-panel :bins="$spectrum" title="Capture Snapshot" :range="$event['frequency']" />
      <div class="rounded-lg border border-slate-800/60 bg-navy-800/60 p-4">
        <div class="text-[10px] tracking-[0.2em] uppercase text-slate-500 mb-3">Signal Attributes</div>
        <table class="w-full text-sm">
          <thead class="text-[10px] uppercase tracking-[0.2em] text-slate-500">
            <tr><th class="text-left py-1">Type</th><th class="text-left py-1">Category</th><th class="text-left py-1">Value</th><th class="text-left py-1">IDS</th><th class="text-left py-1">Comment</th></tr>
          </thead>
          <tbody class="divide-y divide-slate-800/60">
            @foreach($attributes as $a)
              <tr>
                <td class="py-2 font-mono text-signal-300">{{ $a['type'] }}</td>
                <td class="py-2 text-slate-300">{{ $a['category'] }}</td>
                <td class="py-2 font-mono text-slate-100">{{ $a['value'] }}</td>
                <td class="py-2">@if($a['ids'])<span class="text-gold-400 text-xs">YES</span>@else<span class="text-slate-600 text-xs">no</span>@endif</td>
                <td class="py-2 text-slate-400">{{ $a['comment'] }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    <div class="space-y-4">
      <x-activity-timeline :items="$timeline" title="Event Timeline"/>
      <div class="rounded-lg border border-slate-800/60 bg-navy-800/60 p-4">
        <div class="text-[10px] tracking-[0.2em] uppercase text-slate-500 mb-3">Signal Sightings</div>
        <ul class="space-y-2">
          @foreach($sightings as $s)
            <li class="flex items-center justify-between text-[12px]">
              <div>
                <div class="text-slate-200">{{ $s['source'] }}</div>
                <div class="text-slate-500 font-mono">{{ $s['lat'] }}, {{ $s['lon'] }}</div>
              </div>
              <div class="text-right">
                <div class="font-mono text-signal-300">{{ $s['snr'] }} dB</div>
                <div class="text-slate-500 font-mono text-[10px]">{{ $s['ts'] }}</div>
              </div>
            </li>
          @endforeach
        </ul>
      </div>
    </div>
  </div>
@endsection

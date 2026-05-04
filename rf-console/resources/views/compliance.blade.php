@extends('layouts.app')
@section('title','Compliance')
@section('eyebrow','REG - FCC / ITU')
@section('content')
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
    @foreach($summary as $s)
      <x-metric-card :label="$s['label']" :value="$s['value']" :tone="$s['tone']"/>
    @endforeach
  </div>
  <div class="rounded-lg border border-slate-800/60 bg-navy-800/60 p-4">
    <div class="flex items-center justify-between mb-3">
      <div class="text-[10px] tracking-[0.2em] uppercase text-slate-500">Findings</div>
      <div class="text-[11px] text-slate-500">FCC Part 73 / 74 / 80 - ITU RR 15.21 - AIS</div>
    </div>
    <table class="w-full text-sm">
      <thead class="text-[10px] uppercase tracking-[0.2em] text-slate-500">
        <tr><th class="text-left py-1">ID</th><th class="text-left py-1">Event</th><th class="text-left py-1">Rule</th><th class="text-left py-1">Severity</th><th class="text-left py-1">Opened</th><th class="text-left py-1">State</th></tr>
      </thead>
      <tbody class="divide-y divide-slate-800/60">
        @foreach($findings as $f)
          <tr class="hover:bg-navy-700/30">
            <td class="py-2 font-mono text-slate-100">{{ $f['id'] }}</td>
            <td class="py-2 font-mono"><a href="{{ route('events.show', $f['event']) }}" class="text-signal-300 hover:text-signal-200">{{ $f['event'] }}</a></td>
            <td class="py-2 text-slate-300">{{ $f['rule'] }}</td>
            <td class="py-2"><x-severity-badge :severity="$f['severity']"/></td>
            <td class="py-2 font-mono text-[11px] text-slate-500">{{ $f['opened'] }}</td>
            <td class="py-2"><x-severity-badge :severity="$f['state']"/></td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
@endsection

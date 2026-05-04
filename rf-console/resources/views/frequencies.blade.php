@extends('layouts.app')
@section('title','Frequencies')
@section('eyebrow','INTEL - FREQUENCY PLAN')
@section('content')
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
    <div class="rounded-lg border border-slate-800/60 bg-navy-800/60 p-4">
      <div class="text-[10px] tracking-[0.2em] uppercase text-slate-500 mb-3">Bands</div>
      <table class="w-full text-sm">
        <thead class="text-[10px] uppercase tracking-[0.2em] text-slate-500">
          <tr><th class="text-left py-1">Band</th><th class="text-left py-1">Range</th><th class="text-right py-1">Events</th><th class="text-right py-1">Verified</th><th class="text-right py-1">Anomalies</th></tr>
        </thead>
        <tbody class="divide-y divide-slate-800/60">
          @foreach($bands as $b)
            <tr>
              <td class="py-2 text-slate-200">{{ $b['band'] }}</td>
              <td class="py-2 font-mono text-slate-400">{{ $b['range'] }}</td>
              <td class="py-2 text-right font-mono text-slate-100">{{ $b['events'] }}</td>
              <td class="py-2 text-right font-mono text-gold-400">{{ $b['verified'] }}</td>
              <td class="py-2 text-right font-mono {{ $b['anomalies']>0 ? 'text-anomaly-400' : 'text-slate-500' }}">{{ $b['anomalies'] }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div class="rounded-lg border border-slate-800/60 bg-navy-800/60 p-4">
      <div class="text-[10px] tracking-[0.2em] uppercase text-slate-500 mb-3">Allocations / Licensing</div>
      <table class="w-full text-sm">
        <thead class="text-[10px] uppercase tracking-[0.2em] text-slate-500">
          <tr><th class="text-left py-1">Callsign</th><th class="text-left py-1">Frequency</th><th class="text-left py-1">Holder</th><th class="text-left py-1">Expires</th><th class="text-left py-1">State</th></tr>
        </thead>
        <tbody class="divide-y divide-slate-800/60">
          @foreach($allocations as $a)
            <tr>
              <td class="py-2 font-mono text-slate-100">{{ $a['callsign'] }}</td>
              <td class="py-2 font-mono text-slate-300">{{ $a['freq'] }}</td>
              <td class="py-2 text-slate-300">{{ $a['holder'] }}</td>
              <td class="py-2 font-mono text-slate-500">{{ $a['expires'] }}</td>
              <td class="py-2"><x-severity-badge :severity="$a['state']"/></td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endsection

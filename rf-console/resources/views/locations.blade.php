@extends('layouts.app')
@section('title','Locations')
@section('eyebrow','INTEL - GEOGRAPHY')
@section('content')
  <x-map-panel :locations="$locations"/>
  <div class="rounded-lg border border-slate-800/60 bg-navy-800/60 p-4">
    <div class="text-[10px] tracking-[0.2em] uppercase text-slate-500 mb-3">Sites</div>
    <table class="w-full text-sm">
      <thead class="text-[10px] uppercase tracking-[0.2em] text-slate-500">
        <tr><th class="text-left py-1">Site</th><th class="text-left py-1">Country</th><th class="text-left py-1">Lat</th><th class="text-left py-1">Lon</th><th class="text-right py-1">Sources</th><th class="text-left py-1">Status</th></tr>
      </thead>
      <tbody class="divide-y divide-slate-800/60">
        @foreach($locations as $l)
          <tr>
            <td class="py-2 text-slate-200">{{ $l['name'] }}</td>
            <td class="py-2 font-mono text-slate-400">{{ $l['country'] }}</td>
            <td class="py-2 font-mono text-slate-300">{{ $l['lat'] }}</td>
            <td class="py-2 font-mono text-slate-300">{{ $l['lon'] }}</td>
            <td class="py-2 text-right font-mono text-slate-100">{{ $l['sources'] }}</td>
            <td class="py-2"><x-severity-badge :severity="$l['status']"/></td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
@endsection

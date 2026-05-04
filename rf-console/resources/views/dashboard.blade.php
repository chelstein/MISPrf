@extends('layouts.app')
@section('title','Operational Dashboard')
@section('eyebrow','OPS - LIVE')
@section('content')
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
    @foreach($metrics as $m)
      <x-metric-card :label="$m['label']" :value="$m['value']" :delta="$m['delta']" :tone="$m['tone']" />
    @endforeach
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
    <div class="lg:col-span-2 space-y-4">
      <x-spectrum-panel :bins="$spectrum" title="VHF Marine - Live Spectrum" range="156.0 - 158.4 MHz" />
      <div class="rounded-lg border border-slate-800/60 bg-navy-800/60 p-4">
        <div class="flex items-center justify-between mb-3">
          <div class="text-[10px] tracking-[0.2em] uppercase text-slate-500">Recent RF Events</div>
          <a href="{{ route('events.index') }}" class="text-[11px] text-signal-300 hover:text-signal-200">View all -&gt;</a>
        </div>
        <x-event-table :events="$recentEvents" />
      </div>
    </div>
    <div class="space-y-4">
      <x-map-panel :locations="$sites" />
      <x-activity-timeline :items="$sightings" title="Latest Signal Sightings" />
    </div>
  </div>
@endsection

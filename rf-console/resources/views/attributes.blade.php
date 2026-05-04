@extends('layouts.app')
@section('title','Signal Attributes')
@section('eyebrow','INTEL - ATTRIBUTES')
@section('content')
  <p class="text-sm text-slate-400 max-w-2xl">MISP attributes rendered as <span class="text-gold-400">Signal Attributes</span>. Each row corresponds to a single MISP attribute; types are RF-specific (frequency, modulation, bandwidth, SNR, geo-bbox, SigMF artifact).</p>
  <div class="overflow-hidden rounded-lg border border-slate-800/60 bg-navy-800/60">
    <table class="w-full text-sm">
      <thead class="bg-navy-900/70 text-[10px] uppercase tracking-[0.2em] text-slate-500">
        <tr>
          <th class="px-4 py-2.5 text-left">Event</th>
          <th class="px-4 py-2.5 text-left">Type</th>
          <th class="px-4 py-2.5 text-left">Category</th>
          <th class="px-4 py-2.5 text-left">Value</th>
          <th class="px-4 py-2.5 text-left">IDS</th>
          <th class="px-4 py-2.5 text-left">Comment</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-800/60">
        @foreach($attributes as $a)
          <tr class="hover:bg-navy-700/30">
            <td class="px-4 py-2.5 font-mono"><a href="{{ route('events.show', $a['event_id']) }}" class="text-signal-300 hover:text-signal-200">{{ $a['event_id'] }}</a></td>
            <td class="px-4 py-2.5 font-mono text-slate-200">{{ $a['type'] }}</td>
            <td class="px-4 py-2.5 text-slate-300">{{ $a['category'] }}</td>
            <td class="px-4 py-2.5 font-mono text-slate-100">{{ $a['value'] }}</td>
            <td class="px-4 py-2.5">@if($a['ids'])<span class="text-gold-400 text-xs">YES</span>@else<span class="text-slate-600 text-xs">no</span>@endif</td>
            <td class="px-4 py-2.5 text-slate-400">{{ $a['comment'] }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
@endsection

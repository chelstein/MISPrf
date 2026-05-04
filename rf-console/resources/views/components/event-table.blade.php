@props(['events'])
<div class="overflow-hidden rounded-lg border border-slate-800/60 bg-navy-800/60">
  <table class="w-full text-sm">
    <thead class="bg-navy-900/70 text-[10px] uppercase tracking-[0.2em] text-slate-500">
      <tr>
        <th class="px-4 py-2.5 text-left">RF Event</th>
        <th class="px-4 py-2.5 text-left">Info</th>
        <th class="px-4 py-2.5 text-left">Frequency</th>
        <th class="px-4 py-2.5 text-left">Severity</th>
        <th class="px-4 py-2.5 text-left">Status</th>
        <th class="px-4 py-2.5 text-left">Source</th>
        <th class="px-4 py-2.5 text-left">Updated</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-slate-800/60">
      @foreach($events as $e)
        <tr class="hover:bg-navy-700/30 transition">
          <td class="px-4 py-3 font-mono text-signal-300">
            <a href="{{ route('events.show', $e['id']) }}" class="hover:text-signal-200">{{ $e['id'] }}</a>
          </td>
          <td class="px-4 py-3 text-slate-200">{{ $e['info'] }}</td>
          <td class="px-4 py-3 font-mono text-slate-300">{{ $e['frequency'] }}</td>
          <td class="px-4 py-3"><x-severity-badge :severity="$e['severity']" /></td>
          <td class="px-4 py-3"><x-severity-badge :severity="$e['status']" /></td>
          <td class="px-4 py-3 text-slate-400">{{ $e['source'] }}</td>
          <td class="px-4 py-3 font-mono text-[11px] text-slate-500">{{ $e['updated'] }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>

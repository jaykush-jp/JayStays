@extends('layouts.hotel')
@section('title','Room Availability')
@section('content')
<div class="max-w-3xl mx-auto">
  <a href="{{ route('hotel.rooms', $room->hotel_id) }}" class="inline-flex items-center gap-1.5 text-muted hover:text-iris text-sm mb-5 transition-colors"><x-icon name="arrow-right" class="w-4 h-4 rotate-180"/>Rooms</a>
  <h2 class="font-display text-xl font-semibold text-ink mb-1">Availability calendar</h2>
  <p class="text-muted text-sm mb-7">{{ $room->name }} · click a date to block or unblock it</p>
  <div class="card p-6" x-data="{loading:false}">
    <div class="grid grid-cols-7 gap-1 text-center text-xs font-semibold text-muted mb-2">
      @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $d)<div>{{ $d }}</div>@endforeach
    </div>
    <div class="grid grid-cols-7 gap-1">
      @php $start = now()->startOfMonth(); $today = now()->startOfDay(); @endphp
      @for($d = 0; $d < $start->dayOfWeek; $d++)<div></div>@endfor
      @for($d = 1; $d <= $start->daysInMonth; $d++)
        @php $date = now()->day($d)->format('Y-m-d'); $avail = $dates[$date] ?? null; $blocked = $avail?->is_blocked; $past = now()->day($d)->lt($today); @endphp
        <button
          @unless($past) onclick="toggleDate('{{ $date }}', this)" @endunless
          class="aspect-square rounded-lg text-xs font-semibold font-mono flex items-center justify-center transition-all
            {{ $past ? 'text-line cursor-default' : ($blocked ? 'bg-red-100 text-red-700 hover:bg-red-200' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100 cursor-pointer') }}">
          {{ $d }}
        </button>
      @endfor
    </div>
    <div class="flex items-center gap-4 mt-4 text-xs text-muted">
      <div class="flex items-center gap-1.5"><div class="w-4 h-4 bg-emerald-50 rounded"></div>Available</div>
      <div class="flex items-center gap-1.5"><div class="w-4 h-4 bg-red-100 rounded"></div>Blocked</div>
    </div>
  </div>
</div>
@push('scripts')
<script>
async function toggleDate(date,btn){
  const r=await fetch('{{ route('hotel.availability.toggle',[$room->hotel_id,$room->id]) }}',{
    method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content},
    body:JSON.stringify({date})
  });
  const d=await r.json();
  if(d.success){btn.classList.toggle('bg-red-100');btn.classList.toggle('text-red-700');btn.classList.toggle('bg-emerald-50');btn.classList.toggle('text-emerald-700');}
}
</script>
@endpush
@endsection

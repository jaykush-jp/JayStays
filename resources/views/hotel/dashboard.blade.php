@extends('layouts.hotel')
@section('title','Dashboard')
@section('content')
<div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-7">
  @foreach([
    ['Pending bookings',$stats['pending'],'clock'],
    ['Confirmed',$stats['confirmed'],'check-circle'],
    ["Today's arrivals",$stats['today'],'calendar'],
    ['This month','₹'.number_format($stats['month_revenue']),'wallet'],
    ['Total revenue','₹'.number_format($stats['revenue']),'chart'],
  ] as [$l,$v,$ic])
    <div class="card p-5"><div class="w-10 h-10 rounded-xl flex items-center justify-center mb-3 bg-brand-50 text-iris"><x-icon name="{{ $ic }}" class="w-5 h-5"/></div><div class="text-2xl font-bold text-ink font-mono tnum">{{ $v }}</div><div class="text-xs font-medium text-muted uppercase tracking-wide mt-1">{{ $l }}</div></div>
  @endforeach
</div>

@if($pendingBookings->isNotEmpty())
<div class="card overflow-hidden mb-6 border-amber/30">
  <div class="px-5 py-4 border-b border-amber/20 bg-amber-soft flex items-center justify-between">
    <h2 class="font-semibold text-amber-deep flex items-center gap-2"><x-icon name="clock" class="w-5 h-5"/>Pending — action required ({{ $pendingBookings->count() }})</h2>
    <a href="{{ route('hotel.bookings') }}" class="text-iris text-sm font-semibold hover:underline flex items-center gap-1">Manage all <x-icon name="arrow-right" class="w-3.5 h-3.5"/></a>
  </div>
  <div class="divide-y divide-line">
    @foreach($pendingBookings->take(3) as $b)
      <div class="p-5 flex items-start justify-between gap-4">
        <div>
          <div class="font-semibold text-ink">{{ $b->guest_name }} <span class="font-mono text-xs text-iris ml-1">{{ $b->booking_ref }}</span></div>
          <div class="text-muted text-sm">{{ $b->room?->name }} · {{ $b->stay_type==='hourly' ? $b->hours.' hrs' : 'Overnight' }} · {{ $b->checkin_at?->format('d M Y, h:i A') }}</div>
          <div class="text-xs text-muted mt-0.5">Waiting {{ $b->created_at->diffForHumans() }}</div>
        </div>
        <div class="flex gap-2 flex-shrink-0">
          <form method="POST" action="{{ route('hotel.bookings.accept',$b->id) }}">@csrf<button class="btn btn-sm bg-emerald-600 text-white hover:bg-emerald-700"><x-icon name="check" class="w-3.5 h-3.5"/>Accept</button></form>
          <a href="{{ route('hotel.bookings') }}" class="btn btn-sm btn-white">Details</a>
        </div>
      </div>
    @endforeach
    @if($pendingBookings->count() > 3)
      <div class="p-4 text-center"><a href="{{ route('hotel.bookings') }}" class="text-iris text-sm font-semibold hover:underline">+ {{ $pendingBookings->count()-3 }} more pending</a></div>
    @endif
  </div>
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
  <div class="card overflow-hidden">
    <div class="flex items-center justify-between px-5 py-4 border-b border-line">
      <h2 class="font-display font-semibold text-ink">Today's arrivals</h2>
    </div>
    <div class="divide-y divide-line">
      @forelse($todayBookings->take(5) as $b)
        <div class="px-5 py-3 flex items-center justify-between">
          <div>
            <div class="font-semibold text-ink text-sm">{{ $b->guest_name }}</div>
            <div class="text-muted text-xs">{{ $b->room?->name }} · {{ $b->checkin_at?->format('h:i A') }}</div>
          </div>
          <span class="status-{{ $b->status }}">{{ $b->status_label }}</span>
        </div>
      @empty
        <div class="px-5 py-6 text-center text-muted text-sm">No arrivals today.</div>
      @endforelse
    </div>
  </div>
  <div class="card overflow-hidden">
    <div class="flex items-center justify-between px-5 py-4 border-b border-line">
      <h2 class="font-display font-semibold text-ink">My properties</h2>
      <a href="{{ route('hotel.properties') }}" class="text-iris text-xs font-semibold hover:underline flex items-center gap-1">Manage <x-icon name="arrow-right" class="w-3.5 h-3.5"/></a>
    </div>
    <div class="divide-y divide-line">
      @forelse($myHotels as $h)
        <div class="px-5 py-3 flex items-center justify-between">
          <div>
            <div class="font-semibold text-ink text-sm">{{ $h->name }}</div>
            <div class="text-muted text-xs">{{ $h->city }} · {{ $h->rooms_count }} rooms</div>
          </div>
          <span class="status-{{ $h->status }}">{{ ucfirst($h->status) }}</span>
        </div>
      @empty
        <div class="px-5 py-6 text-center text-muted text-sm">No hotels yet. <a href="{{ route('hotel.properties.create') }}" class="text-iris hover:underline">Add one</a></div>
      @endforelse
    </div>
  </div>
</div>
@endsection

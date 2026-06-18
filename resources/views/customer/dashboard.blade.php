@extends('layouts.customer')
@section('title','My Dashboard')
@section('content')

<div class="mb-7">
  <h1 class="font-display text-2xl font-bold text-ink mb-1 tracking-tight">Welcome back, {{ Str::words($user->name, 1, '') }}</h1>
  <p class="text-muted text-sm">Manage your bookings, wishlist and profile.</p>
</div>

<div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-7">
  @foreach([
    ['Total bookings', $stats['total_bookings'], 'doc'],
    ['Upcoming',       $stats['upcoming_count'], 'clock'],
    ['Completed',      $stats['completed_count'],'check-circle'],
    ['Total spent',    '₹'.number_format($stats['total_spent']), 'wallet'],
    ['Wishlist',       $stats['wishlist_count'], 'heart'],
  ] as [$lbl,$val,$icon])
    <div class="card p-5">
      <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-3 bg-brand-50 text-iris"><x-icon name="{{ $icon }}" class="w-5 h-5"/></div>
      <div class="text-2xl font-bold text-ink leading-tight font-mono tnum">{{ $val }}</div>
      <div class="text-xs font-medium text-muted uppercase tracking-wide mt-1">{{ $lbl }}</div>
    </div>
  @endforeach
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
  <div class="card overflow-hidden">
    <div class="flex items-center justify-between px-5 py-4 border-b border-line">
      <h2 class="font-display font-semibold text-ink">Upcoming stays</h2>
      <a href="{{ route('customer.bookings') }}" class="text-iris text-xs font-semibold hover:underline flex items-center gap-1">View all <x-icon name="arrow-right" class="w-3.5 h-3.5"/></a>
    </div>
    <div class="divide-y divide-line">
      @forelse($upcoming as $b)
        <div class="px-5 py-4">
          <div class="flex items-start justify-between gap-3 mb-2">
            <div>
              <div class="font-semibold text-ink text-sm">{{ $b->hotel?->name }}</div>
              <div class="text-muted text-xs">{{ $b->room?->name }} · {{ $b->hotel?->city }}</div>
            </div>
            <span class="status-{{ $b->status }} flex-shrink-0">{{ $b->status_label }}</span>
          </div>
          <div class="flex items-center gap-3 text-xs text-muted">
            <span class="flex items-center gap-1"><x-icon name="calendar" class="w-3.5 h-3.5"/>{{ $b->checkin_at?->format('d M Y, h:i A') }}</span>
            <span class="flex items-center gap-1"><x-icon name="wallet" class="w-3.5 h-3.5"/>₹{{ number_format($b->balance_amount) }}</span>
          </div>
          @if($b->isPending())
            <div class="mt-2 text-xs text-amber-deep font-medium bg-amber-soft rounded-lg px-3 py-1.5 flex items-center gap-1.5"><x-icon name="clock" class="w-3.5 h-3.5"/>Awaiting hotel confirmation</div>
          @endif
        </div>
      @empty
        <div class="px-5 py-8 text-center text-muted text-sm">No upcoming bookings. <a href="{{ route('search') }}" class="text-iris hover:underline">Find hotels</a></div>
      @endforelse
    </div>
  </div>

  <div class="card overflow-hidden">
    <div class="flex items-center justify-between px-5 py-4 border-b border-line">
      <h2 class="font-display font-semibold text-ink">Recent activity</h2>
      <a href="{{ route('customer.bookings') }}" class="text-iris text-xs font-semibold hover:underline flex items-center gap-1">View all <x-icon name="arrow-right" class="w-3.5 h-3.5"/></a>
    </div>
    <div class="divide-y divide-line">
      @forelse($recent as $b)
        <div class="px-5 py-4 flex items-start justify-between gap-3">
          <div>
            <div class="font-semibold text-ink text-sm">{{ $b->hotel?->name }}</div>
            <div class="text-muted text-xs mt-0.5 font-mono">{{ $b->checkin_at?->format('d M Y') }} · {{ $b->booking_ref }}</div>
            @if($b->canReview())
              <a href="{{ route('customer.bookings.show', $b->booking_ref) }}" class="text-xs text-iris hover:underline font-medium mt-1 flex items-center gap-1"><x-icon name="star" class="w-3.5 h-3.5"/>Write a review</a>
            @endif
          </div>
          <span class="status-{{ $b->status }} flex-shrink-0">{{ $b->status_label }}</span>
        </div>
      @empty
        <div class="px-5 py-8 text-center text-muted text-sm">No past bookings yet.</div>
      @endforelse
    </div>
  </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-6">
  @foreach([
    ['search','Book a room','Search & book instantly',route('search')],
    ['heart','My wishlist',$stats['wishlist_count'].' saved hotels',route('customer.wishlist')],
    ['doc','Track booking','Check booking status',route('booking.track')],
  ] as [$icon,$title,$sub,$url])
    <a href="{{ $url }}" class="card-hover p-5 flex items-center gap-4 group">
      <div class="w-11 h-11 rounded-xl bg-ink flex items-center justify-center text-white group-hover:scale-105 transition-transform"><x-icon name="{{ $icon }}" class="w-5 h-5"/></div>
      <div><div class="font-semibold text-ink">{{ $title }}</div><div class="text-muted text-xs mt-0.5">{{ $sub }}</div></div>
    </a>
  @endforeach
</div>
@endsection

@extends('layouts.hotel')
@section('title','My Properties')
@section('content')
<div class="flex items-center justify-between mb-5">
  <h2 class="font-display text-xl font-semibold text-ink">Properties ({{ $hotels->count() }})</h2>
  <a href="{{ route('hotel.properties.create') }}" class="btn btn-primary"><x-icon name="plus" class="w-4 h-4"/>Add property</a>
</div>
<div class="space-y-4">
  @forelse($hotels as $h)
    <div class="card p-5 flex flex-col sm:flex-row items-start gap-5">
      <div class="w-16 h-16 rounded-xl overflow-hidden bg-paper flex-shrink-0">
        @if($h->cover_image)<img src="{{ $h->cover_image }}" class="w-full h-full object-cover" loading="lazy" alt="{{ $h->name }}"/>
        @else<div class="w-full h-full flex items-center justify-center"><x-icon name="building" class="w-7 h-7 text-iris"/></div>@endif
      </div>
      <div class="flex-1 min-w-0">
        <div class="flex items-center gap-2 flex-wrap mb-1">
          <h3 class="font-semibold text-ink">{{ $h->name }}</h3>
          <span class="status-{{ $h->status }}">{{ ucfirst($h->status) }}</span>
          @if($h->is_featured)<span class="badge badge-warning"><x-icon name="star" class="w-3 h-3"/>Featured</span>@endif
        </div>
        <div class="text-muted text-sm">{{ $h->city }}{{ $h->area ? ', '.$h->area : '' }}</div>
        <div class="text-muted text-xs mt-1 flex items-center gap-3 flex-wrap">
          <span class="flex items-center gap-1"><x-icon name="bed" class="w-3.5 h-3.5"/>{{ $h->rooms_count }} rooms</span>
          <span class="flex items-center gap-1"><x-icon name="doc" class="w-3.5 h-3.5"/>{{ $h->bookings_count }} bookings</span>
          <span class="flex items-center gap-1"><x-icon name="star" class="w-3.5 h-3.5 text-amber"/>{{ $h->avg_rating }}</span>
        </div>
        @if($h->status === 'pending')
          <div class="mt-2 text-xs text-amber-deep bg-amber-soft rounded-lg px-3 py-1.5 inline-flex items-center gap-1.5"><x-icon name="clock" class="w-3.5 h-3.5"/>Under admin review — usually approved within 24 hours.</div>
        @endif
        @if($h->status === 'rejected')
          <div class="mt-2 text-xs text-red-600 bg-red-50 rounded-lg px-3 py-1.5 inline-flex items-center gap-1.5"><x-icon name="info" class="w-3.5 h-3.5"/>Rejected: {{ $h->rejection_reason }}</div>
        @endif
      </div>
      <div class="flex flex-row sm:flex-col gap-2 flex-shrink-0 flex-wrap">
        <a href="{{ route('hotel.rooms',$h->id) }}" class="btn btn-sm btn-primary">Manage rooms</a>
        <a href="{{ route('hotel.properties.edit',$h->id) }}" class="btn btn-sm btn-white">Edit details</a>
        <a href="{{ route('hotel.bookings') }}" class="btn btn-sm btn-white">View bookings</a>
      </div>
    </div>
  @empty
    <div class="card p-12 text-center">
      <div class="inline-flex w-16 h-16 rounded-2xl bg-brand-50 items-center justify-center mb-4"><x-icon name="building" class="w-8 h-8 text-iris"/></div>
      <h3 class="font-display text-xl font-semibold text-ink mb-2">No properties yet</h3>
      <p class="text-muted text-sm mb-6">Add your hotel to start receiving bookings from guests.</p>
      <a href="{{ route('hotel.properties.create') }}" class="btn btn-primary inline-flex"><x-icon name="plus" class="w-4 h-4"/>Add your first hotel</a>
    </div>
  @endforelse
</div>
@endsection

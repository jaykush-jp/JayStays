@extends('layouts.customer')
@section('title','My Bookings')
@section('content')
<div class="flex items-center justify-between mb-5">
  <h2 class="font-display text-xl font-semibold text-ink">My bookings</h2>
  <form method="GET" class="flex gap-2">
    <select name="status" onchange="this.form.submit()" class="form-select w-auto text-sm py-2">
      <option value="">All status</option>
      @foreach(['pending'=>'Pending','confirmed'=>'Confirmed','completed'=>'Completed','cancelled'=>'Cancelled','rejected'=>'Rejected'] as $v=>$l)
        <option value="{{ $v }}" @selected(request('status')===$v)>{{ $l }}</option>
      @endforeach
    </select>
  </form>
</div>
<div class="space-y-4">
  @forelse($bookings as $b)
    <div class="card p-5">
      <div class="flex items-start justify-between gap-4 mb-3">
        <div>
          <div class="flex items-center gap-2 mb-1">
            <span class="font-mono font-semibold text-iris text-sm">{{ $b->booking_ref }}</span>
            <span class="status-{{ $b->status }}">{{ $b->status_label }}</span>
          </div>
          <div class="font-semibold text-ink">{{ $b->hotel?->name }}</div>
          <div class="text-muted text-sm">{{ $b->room?->name }} · {{ $b->hotel?->city }}</div>
        </div>
        <div class="text-right flex-shrink-0">
          <div class="font-bold text-ink font-mono tnum">₹{{ number_format($b->room_rate) }}</div>
          <div class="text-xs text-emerald-600 font-mono">Paid ₹{{ number_format($b->advance_amount) }}</div>
          <div class="text-xs text-amber-deep font-mono">Balance ₹{{ number_format($b->balance_amount) }}</div>
        </div>
      </div>
      <div class="flex items-center gap-4 text-xs text-muted mb-3">
        <span class="flex items-center gap-1"><x-icon name="calendar" class="w-3.5 h-3.5"/>{{ $b->checkin_at?->format('d M Y, h:i A') }}</span>
        <span class="flex items-center gap-1"><x-icon name="{{ $b->stay_type==='hourly'?'bolt':'moon' }}" class="w-3.5 h-3.5"/>{{ $b->stay_type==='hourly'?$b->hours.' hrs':'Overnight' }}</span>
      </div>
      @if($b->isPending())
        <div class="text-xs text-amber-deep bg-amber-soft rounded-lg px-3 py-2 mb-3 flex items-center gap-1.5"><x-icon name="clock" class="w-3.5 h-3.5"/>Waiting for hotel confirmation</div>
      @endif
      <div class="flex gap-2 flex-wrap">
        <a href="{{ route('customer.bookings.show',$b->booking_ref) }}" class="btn btn-sm btn-white">View details</a>
        <a href="{{ route('customer.bookings.pdf',$b->booking_ref) }}" class="btn btn-sm btn-white"><x-icon name="doc" class="w-3.5 h-3.5"/>PDF</a>
        @if(in_array($b->status,['pending','confirmed']))
          <form method="POST" action="{{ route('customer.bookings.cancel',$b->booking_ref) }}">@csrf
            <button onclick="return confirm('Cancel this booking?')" class="btn btn-sm border border-red-200 text-red-500 hover:bg-red-50">Cancel</button>
          </form>
        @endif
        @if($b->canReview())
          <a href="{{ route('customer.bookings.show',$b->booking_ref) }}#review" class="btn btn-sm bg-amber-soft text-amber-deep"><x-icon name="star" class="w-3.5 h-3.5"/>Review</a>
        @endif
      </div>
    </div>
  @empty
    <div class="card p-12 text-center">
      <div class="inline-flex w-16 h-16 rounded-2xl bg-brand-50 items-center justify-center mb-4"><x-icon name="doc" class="w-8 h-8 text-iris"/></div>
      <h3 class="font-display text-xl font-semibold text-ink mb-2">No bookings yet</h3>
      <p class="text-muted text-sm mb-6">Book your first hotel room on MyRoom.</p>
      <a href="{{ route('search') }}" class="btn btn-primary inline-flex">Find hotels</a>
    </div>
  @endforelse
</div>
@if($bookings->hasPages())<div class="mt-4 flex justify-center">{{ $bookings->links() }}</div>@endif
@endsection

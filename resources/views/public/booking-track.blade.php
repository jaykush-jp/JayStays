@extends('layouts.app')
@section('content')
<div class="min-h-[80vh] bg-paper flex items-center justify-center px-4 py-14">
  <div class="w-full max-w-md">
    <div class="text-center mb-8">
      <div class="w-16 h-16 bg-brand-50 rounded-2xl flex items-center justify-center mx-auto mb-4"><x-icon name="search" class="w-8 h-8 text-iris"/></div>
      <h1 class="font-display text-3xl font-bold text-ink tracking-tight">Track your booking</h1>
      <p class="text-muted mt-2 text-sm">Enter your Booking ID and mobile to view details</p>
    </div>
    @if(session('error'))<div class="alert-error mb-5">{{ session('error') }}</div>@endif
    @if(!isset($booking))
      <div class="card p-7">
        <form method="POST" action="{{ route('booking.track.post') }}" class="space-y-4">@csrf
          <div><label class="form-label" for="booking_ref">Booking ID</label><input type="text" id="booking_ref" name="booking_ref" value="{{ old('booking_ref') }}" placeholder="MRABCD1234" class="form-input uppercase font-mono text-lg text-center tracking-widest" required/></div>
          <div><label class="form-label" for="track_phone">Mobile number</label><div class="flex gap-2"><div class="flex-shrink-0 bg-paper border border-line rounded-xl px-3 flex items-center text-sm font-semibold text-muted font-mono">+91</div><input type="tel" id="track_phone" name="phone" value="{{ old('phone') }}" placeholder="10-digit number" maxlength="10" class="form-input flex-1" required/></div></div>
          <button class="btn btn-primary w-full justify-center btn-lg">Track booking <x-icon name="arrow-right" class="w-4 h-4"/></button>
        </form>
      </div>
    @else
      <div class="card overflow-hidden">
        <div class="relative p-5 text-center bg-ink overflow-hidden">
          <div class="absolute inset-0 ink-grid opacity-40"></div>
          <div class="relative font-display text-2xl font-bold text-white font-mono">{{ $booking->booking_ref }}</div>
          <span class="status-{{ $booking->status }} mt-2 inline-flex relative">{{ $booking->status_label }}</span>
        </div>
        <div class="p-6 divide-y divide-line text-sm">
          @foreach(['Hotel'=>$booking->hotel->name,'Room'=>$booking->room->name,'Check-in'=>$booking->checkin_at->format('d M Y, h:i A'),'Balance to pay'=>'₹'.number_format($booking->balance_amount)] as $l=>$v)
            <div class="flex justify-between py-3"><span class="text-muted">{{ $l }}</span><span class="font-semibold text-ink">{{ $v }}</span></div>
          @endforeach
        </div>
        <div class="p-5 border-t border-line flex gap-3">
          <a href="{{ route('booking.confirmation',$booking->booking_ref) }}" class="btn btn-primary flex-1 justify-center btn-sm">View details</a>
          <a href="{{ route('booking.track') }}" class="btn btn-white flex-1 justify-center btn-sm">Track another</a>
        </div>
      </div>
    @endif
  </div>
</div>
@endsection

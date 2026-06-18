@extends('layouts.app')
@section('content')
<div class="max-w-2xl mx-auto px-4 py-10">
  <div id="booking-receipt" class="card overflow-hidden">
    <div class="relative overflow-hidden px-8 py-10 text-center bg-ink">
      <div class="absolute inset-0 ink-grid opacity-40"></div>
      <div class="absolute -top-8 right-1/4 w-40 h-40 rounded-full opacity-30 pointer-events-none" style="background:radial-gradient(circle,#5B5BD6,transparent 70%)"></div>
      <div class="relative">
        <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lift"><x-icon name="check" class="w-8 h-8 text-emerald-500"/></div>
        <h1 class="font-display text-3xl font-bold text-white mb-2">Booking submitted</h1>
        <p class="text-white/65">
          @if(in_array($booking->status,['pending']))
            Awaiting hotel confirmation. We'll notify you via SMS.
          @else
            Your room is confirmed. Show this at check-in.
          @endif
        </p>
      </div>
    </div>
    <div class="bg-paper border-b border-line px-8 py-5 flex items-center justify-between">
      <div><div class="text-xs font-semibold text-muted uppercase tracking-widest mb-1">Booking ID</div><div class="font-display text-3xl font-bold text-iris font-mono tracking-wider">{{ $booking->booking_ref }}</div></div>
      <span class="status-{{ $booking->status }}">{{ $booking->status_label }}</span>
    </div>
    <div class="px-8 py-5 divide-y divide-line text-sm">
      @foreach(['Guest'=>$booking->guest_name,'Mobile'=>$booking->guest_phone,'Hotel'=>$booking->hotel->name,'Location'=>$booking->hotel->city,'Room'=>$booking->room->name,'Stay'=>($booking->stay_type==='hourly'?$booking->hours.' hours':'Overnight'),'Check-in'=>$booking->checkin_at->format('d M Y, h:i A'),'Check-out'=>($booking->checkout_at?->format('d M Y, h:i A')??' —')] as $l=>$v)
        <div class="flex justify-between py-3"><span class="text-muted">{{ $l }}</span><span class="font-semibold text-ink text-right">{{ $v }}</span></div>
      @endforeach
    </div>
    <div class="mx-8 mb-5 bg-paper rounded-2xl p-5 border border-line">
      <h2 class="font-semibold text-ink text-sm uppercase tracking-wide mb-3">Payment</h2>
      <div class="space-y-2 text-sm">
        <div class="flex justify-between text-muted"><span>Total rate</span><span class="font-mono tnum">₹{{ number_format($booking->room_rate+$booking->discount_amount) }}</span></div>
        @if($booking->discount_amount>0)<div class="flex justify-between text-emerald-600 font-semibold"><span>Discount</span><span class="font-mono tnum">-₹{{ number_format($booking->discount_amount) }}</span></div>@endif
        <div class="flex justify-between font-semibold text-emerald-600"><span class="flex items-center gap-1"><x-icon name="check-circle" class="w-4 h-4"/>Paid online</span><span class="font-mono tnum">₹{{ number_format($booking->advance_amount) }}</span></div>
        <div class="flex justify-between font-bold text-base border-t border-line pt-2"><span>Pay at hotel</span><span class="text-iris font-mono tnum">₹{{ number_format($booking->balance_amount) }}</span></div>
      </div>
    </div>
    @if($booking->balance_amount > 0)
      <div class="mx-8 mb-5 bg-amber-soft border border-amber/20 rounded-2xl p-4 flex gap-3">
        <x-icon name="info" class="w-5 h-5 text-amber-deep flex-shrink-0"/>
        <div class="text-sm text-amber-deep"><strong>Remember:</strong> Show ID <strong class="font-mono">{{ $booking->booking_ref }}</strong> at the hotel. Pay <strong class="font-mono">₹{{ number_format($booking->balance_amount) }}</strong> on arrival.</div>
      </div>
    @endif
    <div class="px-8 pb-6 flex gap-3 no-print">
      <button onclick="window.print()" class="btn btn-white flex-1 justify-center"><x-icon name="doc" class="w-4 h-4"/>Print</button>
      <a href="{{ route('booking.track') }}" class="btn btn-primary flex-1 justify-center">Track booking</a>
    </div>
  </div>
</div>
@endsection

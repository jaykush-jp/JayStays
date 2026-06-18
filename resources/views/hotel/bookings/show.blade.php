@extends('layouts.hotel')
@section('title','Booking #'.($booking->booking_ref??''))
@section('content')
<div class="max-w-2xl mx-auto">
  <a href="{{ route('hotel.bookings') }}" class="inline-flex items-center gap-1.5 text-muted hover:text-iris text-sm mb-5 transition-colors"><x-icon name="arrow-right" class="w-4 h-4 rotate-180"/>All bookings</a>
  <div class="card overflow-hidden">
    <div class="p-6 border-b border-line flex items-start justify-between">
      <div><div class="font-mono font-bold text-iris text-xl">{{ $booking->booking_ref }}</div><div class="text-muted text-xs mt-0.5">{{ $booking->created_at->format('d M Y, h:i A') }}</div></div>
      <span class="status-{{ $booking->status }}">{{ $booking->status_label }}</span>
    </div>
    <div class="p-6 divide-y divide-line text-sm">
      @foreach(['Guest'=>$booking->guest_name,'Mobile'=>$booking->guest_phone,'Email'=>($booking->guest_email??'—'),'Room'=>$booking->room?->name,'Stay'=>($booking->stay_type==='hourly'?$booking->hours.' hours':'Overnight'),'Check-in'=>$booking->checkin_at?->format('d M Y, h:i A'),'Check-out'=>($booking->checkout_at?->format('d M Y, h:i A')??' —'),'Special requests'=>($booking->special_requests??'None'),'Hotel notes'=>($booking->hotel_notes??'None')] as $l=>$v)
        <div class="flex justify-between py-3"><span class="text-muted">{{ $l }}</span><span class="font-semibold text-ink text-right max-w-[60%]">{{ $v }}</span></div>
      @endforeach
    </div>
    <div class="mx-6 mb-5 bg-paper rounded-2xl p-4">
      <h2 class="font-semibold text-sm text-ink uppercase tracking-wide mb-3">Payment</h2>
      <div class="space-y-2 text-sm">
        <div class="flex justify-between text-muted"><span>Room rate</span><span class="font-mono tnum">₹{{ number_format($booking->room_rate) }}</span></div>
        <div class="flex justify-between font-semibold text-emerald-600"><span class="flex items-center gap-1"><x-icon name="check-circle" class="w-4 h-4"/>Paid online (platform fee)</span><span class="font-mono tnum">₹{{ number_format($booking->advance_amount) }}</span></div>
        <div class="flex justify-between font-bold text-base border-t border-line pt-2"><span>Pay at hotel</span><span class="text-iris font-mono tnum">₹{{ number_format($booking->balance_amount) }}</span></div>
      </div>
    </div>
    <div class="mx-6 mb-6 flex flex-wrap gap-2">
      @if($booking->isPending())
        <form method="POST" action="{{ route('hotel.bookings.accept',$booking->id) }}" class="flex gap-2 flex-wrap">@csrf<input type="text" name="hotel_notes" class="form-input text-sm py-2 w-56" placeholder="Optional note to guest..."/><button class="btn btn-sm bg-emerald-600 text-white hover:bg-emerald-700"><x-icon name="check" class="w-3.5 h-3.5"/>Accept</button></form>
        <button onclick="document.getElementById('rj-form').classList.toggle('hidden')" class="btn btn-sm border border-red-200 text-red-600"><x-icon name="close" class="w-3.5 h-3.5"/>Reject</button>
      @elseif($booking->status==='confirmed')
        <form method="POST" action="{{ route('hotel.bookings.checkin',$booking->id) }}">@csrf<button class="btn btn-sm btn-primary"><x-icon name="check" class="w-3.5 h-3.5"/>Mark checked in</button></form>
      @elseif($booking->status==='checked_in')
        <form method="POST" action="{{ route('hotel.bookings.complete',$booking->id) }}">@csrf<button class="btn btn-sm bg-emerald-600 text-white hover:bg-emerald-700"><x-icon name="check-circle" class="w-3.5 h-3.5"/>Mark completed</button></form>
      @endif
    </div>
    <div id="rj-form" class="hidden mx-6 mb-6 bg-red-50 rounded-xl p-4 border border-red-200">
      <form method="POST" action="{{ route('hotel.bookings.reject',$booking->id) }}" class="flex gap-2 flex-wrap">@csrf
        <input type="text" name="reason" class="form-input flex-1 text-sm py-2" placeholder="Reason for rejection..." required/>
        <button class="btn btn-sm bg-red-600 text-white flex-shrink-0">Confirm reject</button>
      </form>
    </div>
  </div>
</div>
@endsection

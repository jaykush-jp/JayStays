@extends('layouts.customer')
@section('title','Booking #'.$booking->booking_ref)
@section('content')
<div class="max-w-2xl mx-auto">
  <a href="{{ route('customer.bookings') }}" class="inline-flex items-center gap-1.5 text-muted hover:text-iris text-sm mb-5 transition-colors"><x-icon name="arrow-right" class="w-4 h-4 rotate-180"/>My bookings</a>
  <div class="card overflow-hidden mb-5">
    <div class="p-6 border-b border-line flex items-start justify-between">
      <div><div class="font-mono font-bold text-iris text-xl">{{ $booking->booking_ref }}</div><div class="text-muted text-xs mt-0.5">{{ $booking->created_at->format('d M Y, h:i A') }}</div></div>
      <span class="status-{{ $booking->status }}">{{ $booking->status_label }}</span>
    </div>
    <div class="p-6 divide-y divide-line text-sm">
      @foreach(['Hotel'=>$booking->hotel?->name,'Location'=>$booking->hotel?->address,'Room'=>$booking->room?->name,'Stay'=>($booking->stay_type==='hourly'?$booking->hours.' hours':'Overnight'),'Check-in'=>$booking->checkin_at?->format('d M Y, h:i A'),'Check-out'=>($booking->checkout_at?->format('d M Y, h:i A')??' —'),'Special req.'=>($booking->special_requests??'None')] as $l=>$v)
        <div class="flex justify-between py-3"><span class="text-muted">{{ $l }}</span><span class="font-semibold text-ink text-right max-w-[60%]">{{ $v }}</span></div>
      @endforeach
    </div>
    <div class="mx-6 mb-5 bg-paper rounded-2xl p-5">
      <h2 class="font-semibold text-sm text-ink uppercase tracking-wide mb-3">Payment</h2>
      <div class="space-y-2 text-sm">
        <div class="flex justify-between text-muted"><span>Room rate</span><span class="font-mono tnum">₹{{ number_format($booking->room_rate) }}</span></div>
        @if($booking->discount_amount>0)<div class="flex justify-between text-emerald-600 font-semibold"><span>Discount ({{ $booking->offer_code }})</span><span class="font-mono tnum">-₹{{ number_format($booking->discount_amount) }}</span></div>@endif
        <div class="flex justify-between font-semibold"><span class="flex items-center gap-1"><x-icon name="check-circle" class="w-4 h-4 text-emerald-600"/>Paid online</span><span class="text-emerald-600 font-mono tnum">₹{{ number_format($booking->advance_amount) }}</span></div>
        <div class="flex justify-between font-bold text-base border-t border-line pt-2"><span>Pay at hotel</span><span class="text-iris font-mono tnum">₹{{ number_format($booking->balance_amount) }}</span></div>
      </div>
    </div>
    @if($booking->isPending())
      <div class="mx-6 mb-5 alert-warning flex items-start gap-3">
        <x-icon name="clock" class="w-5 h-5 flex-shrink-0 mt-0.5"/>
        <div><strong>Awaiting hotel confirmation.</strong> The hotel usually responds within 30 minutes. You'll receive an SMS once confirmed.</div>
      </div>
    @elseif($booking->isConfirmed())
      <div class="mx-6 mb-5 alert-success flex items-start gap-3">
        <x-icon name="check-circle" class="w-5 h-5 flex-shrink-0 mt-0.5"/>
        <div><strong>Booking confirmed.</strong> Show Booking ID <strong class="font-mono">{{ $booking->booking_ref }}</strong> at the hotel. Pay ₹{{ number_format($booking->balance_amount) }} on arrival.</div>
      </div>
    @endif
    <div class="mx-6 mb-6 flex flex-wrap gap-3">
      <a href="{{ route('customer.bookings.pdf',$booking->booking_ref) }}" class="btn btn-sm btn-outline"><x-icon name="doc" class="w-3.5 h-3.5"/>Download PDF</a>
      @if(in_array($booking->status,['pending','confirmed']))
        <form method="POST" action="{{ route('customer.bookings.cancel',$booking->booking_ref) }}">@csrf
          <button onclick="return confirm('Cancel this booking?')" class="btn btn-sm border border-red-200 text-red-500 hover:bg-red-50">Cancel booking</button>
        </form>
      @endif
    </div>
  </div>

  @if($booking->canReview())
    <div class="card p-6" id="review">
      <h2 class="font-display font-semibold text-lg text-ink mb-4 flex items-center gap-2"><x-icon name="star" class="w-5 h-5 text-amber"/>Write a review</h2>
      <form method="POST" action="{{ route('customer.reviews.store',$booking->booking_ref) }}" class="space-y-4">@csrf
        <div>
          <label class="form-label">Rating *</label>
          <div class="flex gap-2" x-data="{rating:5}">
            @for($i=1;$i<=5;$i++)
              <button type="button" @click="rating=@js($i)" :class="rating>=@js($i)?'text-amber':'text-line'" class="transition-colors hover:scale-110"><x-icon name="star" class="w-8 h-8"/></button>
            @endfor
            <input type="hidden" name="rating" :value="rating"/>
          </div>
        </div>
        <div><label class="form-label">Your review</label><textarea name="comment" rows="4" class="form-textarea" placeholder="Share your experience..."></textarea></div>
        <button class="btn btn-primary">Submit review</button>
      </form>
    </div>
  @endif
</div>
@endsection

@extends('layouts.app')
@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
  <a href="{{ route('hotel.show',$hotel->slug) }}" class="inline-flex items-center gap-2 text-muted hover:text-iris text-sm mb-6 transition-colors"><x-icon name="arrow-right" class="w-4 h-4 rotate-180"/>Back to hotel</a>
  <h1 class="font-display text-3xl font-bold text-ink mb-7 tracking-tight">Complete your booking</h1>
  <div class="grid grid-cols-1 lg:grid-cols-5 gap-7" x-data="bookingCalc({{ $room->hourly_price ?? 0 }},{{ $room->overnight_price ?? 0 }},{{ $commission }})">
    <div class="lg:col-span-3 space-y-5">
      {{-- Hotel info --}}
      <div class="card p-5 flex gap-4 items-center bg-brand-50 border-brand-100">
        <div class="w-14 h-14 rounded-xl overflow-hidden bg-white flex-shrink-0">
          @if($hotel->cover_image)<img src="{{ $hotel->cover_image }}" class="w-full h-full object-cover" loading="lazy" alt="{{ $hotel->name }}"/>
          @else<div class="w-full h-full flex items-center justify-center"><x-icon name="building" class="w-6 h-6 text-iris"/></div>@endif
        </div>
        <div>
          <div class="font-semibold text-ink">{{ $hotel->name }}</div>
          <div class="text-muted text-sm flex items-center gap-1"><x-icon name="pin" class="w-3.5 h-3.5"/>{{ $hotel->city }} · {{ $room->name }}</div>
          <div class="flex gap-2 mt-1.5 flex-wrap">
            @if(in_array($room->stay_type,['hourly','both']))<span class="badge badge-primary"><x-icon name="bolt" class="w-3 h-3"/>Hourly from ₹{{ number_format($room->hourly_price) }}/hr</span>@endif
            @if(in_array($room->stay_type,['overnight','both']))<span class="badge badge-success"><x-icon name="moon" class="w-3 h-3"/>Overnight ₹{{ number_format($room->overnight_price) }}</span>@endif
          </div>
        </div>
      </div>

      <form method="POST" action="{{ route('booking.store',[$hotel,$room]) }}" id="booking-form">
        @csrf
        {{-- Step 1: details --}}
        <div class="card p-6">
          <h2 class="font-display font-semibold text-ink mb-4 flex items-center gap-2"><span class="w-6 h-6 rounded-full bg-ink text-white text-xs font-bold font-mono flex items-center justify-center">1</span>Your details</h2>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div><label class="form-label" for="guest_name">Full name *</label><input type="text" id="guest_name" name="guest_name" value="{{ old('guest_name',auth()->user()?->name) }}" class="form-input" required placeholder="Rahul Sharma" autocomplete="name"/>@error('guest_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror</div>
            <div><label class="form-label" for="guest_phone">Mobile *</label><div class="flex gap-2"><div class="flex-shrink-0 bg-paper border border-line rounded-xl px-3 flex items-center text-sm font-semibold text-muted font-mono">+91</div><input type="tel" id="guest_phone" name="guest_phone" value="{{ old('guest_phone',auth()->user()?->phone) }}" class="form-input flex-1" required placeholder="9876543210" maxlength="10"/></div>@error('guest_phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror</div>
            <div class="sm:col-span-2"><label class="form-label" for="guest_email">Email <span class="text-muted font-normal normal-case">(optional)</span></label><input type="email" id="guest_email" name="guest_email" value="{{ old('guest_email',auth()->user()?->email) }}" class="form-input" placeholder="rahul@email.com"/></div>
          </div>
        </div>

        {{-- Step 2: stay --}}
        <div class="card p-6">
          <h2 class="font-display font-semibold text-ink mb-4 flex items-center gap-2"><span class="w-6 h-6 rounded-full bg-ink text-white text-xs font-bold font-mono flex items-center justify-center">2</span>Stay details</h2>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="form-label">Stay type *</label>
              <div class="flex gap-2">
                @if(in_array($room->stay_type,['hourly','both']))
                  <label class="flex-1 border-[1.5px] rounded-xl p-3 cursor-pointer transition-all has-[:checked]:border-iris has-[:checked]:bg-brand-50 border-line">
                    <input type="radio" name="stay_type" value="hourly" x-model="stayType" class="sr-only" {{ old('stay_type','hourly')==='hourly'?'checked':'' }}/>
                    <div class="text-center"><x-icon name="bolt" class="w-5 h-5 mx-auto mb-1 text-iris"/><div class="text-xs font-semibold text-ink">Hourly</div></div>
                  </label>
                @endif
                @if(in_array($room->stay_type,['overnight','both']))
                  <label class="flex-1 border-[1.5px] rounded-xl p-3 cursor-pointer transition-all has-[:checked]:border-iris has-[:checked]:bg-brand-50 border-line">
                    <input type="radio" name="stay_type" value="overnight" x-model="stayType" class="sr-only" {{ old('stay_type')==='overnight'?'checked':'' }}/>
                    <div class="text-center"><x-icon name="moon" class="w-5 h-5 mx-auto mb-1 text-iris"/><div class="text-xs font-semibold text-ink">Overnight</div></div>
                  </label>
                @endif
              </div>
            </div>
            <div x-show="stayType === 'hourly'">
              <label class="form-label" for="hours">Number of hours *</label>
              <select id="hours" name="hours" x-model.number="hours" class="form-select">
                @foreach([2,3,4,5,6,8,12] as $h)
                  @if($h >= ($room->min_hours ?? 2))<option value="{{ $h }}">{{ $h }} hours</option>@endif
                @endforeach
              </select>
            </div>
            <div class="sm:col-span-2">
              <label class="form-label" for="checkin_at">Check-in *</label>
              <input type="datetime-local" id="checkin_at" name="checkin_at" min="{{ now()->addHour()->format('Y-m-d\TH:i') }}" class="form-input font-mono" required/>
              @error('checkin_at')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="sm:col-span-2">
              <label class="form-label" for="special_requests">Special requests <span class="text-muted font-normal normal-case">(optional)</span></label>
              <textarea id="special_requests" name="special_requests" rows="2" class="form-textarea" placeholder="Early check-in, quiet room, extra towels..."></textarea>
            </div>
          </div>
        </div>

        {{-- Step 3: promo --}}
        <div class="card p-6">
          <h2 class="font-display font-semibold text-ink mb-3 flex items-center gap-2"><span class="w-6 h-6 rounded-full bg-ink text-white text-xs font-bold font-mono flex items-center justify-center">3</span>Promo code</h2>
          <div class="flex gap-2">
            <input type="text" x-model="offerCode" placeholder="WEEKEND20" class="form-input flex-1 uppercase font-mono tracking-wider"/>
            <button type="button" @click="applyOffer()" class="btn btn-outline flex-shrink-0">Apply</button>
          </div>
          @if($offers->isNotEmpty())
            <div class="flex flex-wrap gap-2 mt-2.5">
              @foreach($offers->take(4) as $o)
                <button type="button" @click="offerCode='{{ $o->code }}';applyOffer()" class="text-xs bg-brand-50 text-iris border border-brand-200 px-2.5 py-1 rounded-full font-semibold font-mono hover:bg-iris hover:text-white transition-colors">{{ $o->code }}</button>
              @endforeach
            </div>
          @endif
          <p x-show="offerApplied" class="text-emerald-600 text-sm font-semibold mt-2 flex items-center gap-1"><x-icon name="check-circle" class="w-4 h-4"/>Saved ₹<span x-text="discount"></span>!</p>
          <p x-show="offerError" x-text="offerError" class="text-red-500 text-sm mt-2"></p>
          <input type="hidden" name="offer_code" :value="offerApplied ? offerCode : ''"/>
        </div>

        {{-- Step 4: payment --}}
        <div class="card p-6">
          <h2 class="font-display font-semibold text-ink mb-3 flex items-center gap-2"><span class="w-6 h-6 rounded-full bg-ink text-white text-xs font-bold font-mono flex items-center justify-center">4</span>Payment amount</h2>
          <div class="grid grid-cols-2 gap-3">
            <label class="border-[1.5px] rounded-xl p-3 cursor-pointer transition-all has-[:checked]:border-iris has-[:checked]:bg-brand-50 border-line">
              <input type="radio" name="payment_type" value="partial" x-model="payType" class="sr-only" checked/>
              <div class="text-center"><div class="font-semibold text-sm text-ink mb-0.5">Pay advance</div><div class="text-iris font-bold text-lg font-mono tnum" x-text="'₹'+advance"></div><div class="text-muted text-xs">~{{ $commission }}% online</div></div>
            </label>
            <label class="border-[1.5px] rounded-xl p-3 cursor-pointer transition-all has-[:checked]:border-iris has-[:checked]:bg-brand-50 border-line">
              <input type="radio" name="payment_type" value="full" x-model="payType" class="sr-only"/>
              <div class="text-center"><div class="font-semibold text-sm text-ink mb-0.5">Pay full</div><div class="text-iris font-bold text-lg font-mono tnum" x-text="'₹'+net"></div><div class="text-muted text-xs">Nothing at hotel</div></div>
            </label>
          </div>
          <div class="grid grid-cols-2 gap-3 mt-3">
            <label class="border-[1.5px] rounded-xl p-3 cursor-pointer has-[:checked]:border-iris has-[:checked]:bg-brand-50 border-line">
              <input type="radio" name="gateway" value="razorpay" class="sr-only" checked/>
              <div class="flex items-center gap-2.5"><x-icon name="wallet" class="w-6 h-6 text-iris"/><div><div class="font-semibold text-sm text-ink">Razorpay</div><div class="text-xs text-muted">Cards, UPI, Netbanking</div></div></div>
            </label>
            <label class="border-[1.5px] rounded-xl p-3 cursor-pointer has-[:checked]:border-iris has-[:checked]:bg-brand-50 border-line">
              <input type="radio" name="gateway" value="phonepe" class="sr-only"/>
              <div class="flex items-center gap-2.5"><x-icon name="phone" class="w-6 h-6 text-iris"/><div><div class="font-semibold text-sm text-ink">PhonePe</div><div class="text-xs text-muted">UPI & Wallet</div></div></div>
            </label>
          </div>
        </div>
      </form>
    </div>

    {{-- Price summary --}}
    <div class="lg:col-span-2">
      <div class="card p-6 sticky top-28">
        <h2 class="font-display text-lg font-semibold text-ink mb-5">Price summary</h2>
        <div class="space-y-2.5 text-sm mb-5">
          <div class="flex justify-between text-muted">
            <span x-text="stayType==='hourly'?'₹'+{{ $room->hourly_price??0 }}+' × '+hours+' hrs':'Overnight rate'"></span>
            <span class="font-semibold text-ink font-mono tnum">₹<span x-text="roomCost"></span></span>
          </div>
          <div x-show="discount>0" class="flex justify-between text-emerald-600 font-semibold">
            <span class="flex items-center gap-1"><x-icon name="gift" class="w-4 h-4"/>Promo discount</span><span class="font-mono tnum">-₹<span x-text="discount"></span></span>
          </div>
        </div>
        <div class="bg-ink rounded-xl p-4 mb-3">
          <div class="flex justify-between items-center">
            <div><div class="font-semibold text-sm text-white">You pay online</div><div class="text-xs text-white/55 mt-0.5">{{ $commission }}% · confirms your room</div></div>
            <div class="font-display text-2xl font-bold text-white font-mono tnum">₹<span x-text="advance"></span></div>
          </div>
        </div>
        <div class="bg-paper border border-line rounded-xl p-4 mb-5">
          <div class="flex justify-between items-center">
            <div><div class="font-semibold text-sm text-ink">Pay at hotel</div><div class="text-xs text-muted mt-0.5">On arrival</div></div>
            <div class="font-bold text-xl text-ink font-mono tnum">₹<span x-text="balance"></span></div>
          </div>
        </div>
        <button type="submit" form="booking-form" class="btn btn-primary w-full btn-lg justify-center">Book now <x-icon name="arrow-right" class="w-4 h-4"/></button>
        <div class="mt-4 space-y-2">
          <div class="flex items-center gap-2 text-xs text-muted"><x-icon name="lock" class="w-3.5 h-3.5"/>Secured by Razorpay / PhonePe</div>
          <div class="flex items-center gap-2 text-xs text-muted"><x-icon name="phone" class="w-3.5 h-3.5"/>Instant SMS confirmation</div>
          <div class="flex items-center gap-2 text-xs text-muted"><x-icon name="check-circle" class="w-3.5 h-3.5"/>Free cancellation 2h before check-in</div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@push('scripts')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
@endpush

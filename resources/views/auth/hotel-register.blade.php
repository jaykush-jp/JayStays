@extends('layouts.app')
@section('content')
<div class="min-h-[85vh] grid lg:grid-cols-2">
  {{-- LEFT: partner pitch --}}
  <div class="relative hidden lg:flex flex-col justify-between p-12 bg-ink overflow-hidden">
    <div class="absolute inset-0 ink-grid opacity-40"></div>
    <div class="absolute -bottom-24 -right-10 w-96 h-96 rounded-full opacity-25 animate-drift" style="background:radial-gradient(circle,#FF8A3D,transparent 65%)"></div>
    <a href="{{ route('home') }}" class="relative flex items-center gap-2.5">
      <span class="w-9 h-9 rounded-xl bg-white/10 flex items-center justify-center"><x-icon name="clock" class="w-5 h-5 text-white"/></span>
      <span class="font-display font-bold text-xl text-white">My<span class="text-brand-300">Room</span></span>
    </a>
    <div class="relative">
      <span class="eyebrow mb-3" style="color:#A9A9E9">For hotel partners</span>
      <h2 class="font-display font-bold text-4xl text-white leading-tight mb-4">Fill empty rooms,<br>hour by hour.</h2>
      <p class="text-white/60 max-w-sm mb-8">List your property and earn from short stays, day-use and overnight bookings — with guests who pay an advance before they arrive.</p>
      <div class="grid grid-cols-3 gap-4">
        @foreach([['50+','Cities live'],['2 min','To list'],['0','Setup cost']] as [$n,$l])
          <div><div class="font-display font-bold text-2xl text-white font-mono tnum">{{ $n }}</div><div class="text-white/50 text-xs">{{ $l }}</div></div>
        @endforeach
      </div>
    </div>
    <div class="relative text-white/40 text-xs">© {{ date('Y') }} MyRoom</div>
  </div>

  {{-- RIGHT: form --}}
  <div class="flex items-center justify-center px-4 py-12 bg-paper">
    <div class="w-full max-w-lg">
      <div class="mb-7">
        <h1 class="font-display text-3xl font-bold text-ink tracking-tight">List your hotel</h1>
        <p class="text-muted text-sm mt-2">Join India's leading hourly hotel platform</p>
      </div>
      @if(session('error'))<div class="alert-error mb-4">{{ session('error') }}</div>@endif
      <div class="card p-8">
        <form method="POST" action="{{ route('hotel.register.store') }}" class="space-y-4">@csrf
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div><label class="form-label">Your name *</label><input type="text" name="name" value="{{ old('name') }}" class="form-input" required/>@error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror</div>
            <div><label class="form-label">Mobile *</label><input type="tel" name="phone" value="{{ old('phone') }}" class="form-input" maxlength="10" placeholder="10-digit" required/>@error('phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror</div>
            <div class="sm:col-span-2"><label class="form-label">Email *</label><input type="email" name="email" value="{{ old('email') }}" class="form-input" required/>@error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror</div>
            <div><label class="form-label">Password *</label><input type="password" name="password" class="form-input" required minlength="6"/></div>
            <div><label class="form-label">Confirm password *</label><input type="password" name="password_confirmation" class="form-input" required/></div>
          </div>
          <div class="border-t border-line pt-4">
            <div class="text-sm font-semibold text-ink mb-3 flex items-center gap-1.5"><x-icon name="building" class="w-4 h-4 text-iris"/>Hotel information</div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div class="sm:col-span-2"><label class="form-label">Hotel name *</label><input type="text" name="hotel_name" value="{{ old('hotel_name') }}" class="form-input" required/>@error('hotel_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror</div>
              <div><label class="form-label">City *</label><input type="text" name="hotel_city" value="{{ old('hotel_city') }}" class="form-input" placeholder="Delhi, Mumbai..." required/>@error('hotel_city')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror</div>
              <div class="sm:col-span-2"><label class="form-label">Full address *</label><textarea name="hotel_address" class="form-textarea" rows="2" required>{{ old('hotel_address') }}</textarea></div>
            </div>
          </div>
          <button class="btn btn-primary w-full justify-center btn-lg">Submit registration <x-icon name="arrow-right" class="w-4 h-4"/></button>
          <p class="text-xs text-muted text-center flex items-center justify-center gap-1.5"><x-icon name="info" class="w-3.5 h-3.5"/>Reviewed within 24 hours. You'll get an SMS once approved.</p>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

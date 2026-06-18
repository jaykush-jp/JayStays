@extends('layouts.app')
@section('content')
<div class="min-h-[85vh] bg-paper flex items-center justify-center px-4 py-12">
  <div class="w-full max-w-md">
    <div class="text-center mb-8">
      <a href="{{ route('home') }}" class="inline-flex items-center gap-2 mb-4">
        <span class="w-10 h-10 rounded-xl bg-ink flex items-center justify-center"><x-icon name="clock" class="w-5 h-5 text-white"/></span>
        <span class="font-display font-bold text-2xl text-ink">My<span class="text-iris">Room</span></span>
      </a>
      <h1 class="font-display text-2xl font-bold text-ink">Create your account</h1>
      <p class="text-muted text-sm mt-1.5">Track bookings, save hotels and manage your stays</p>
    </div>
    <div class="card p-8">
      <form method="POST" action="{{ route('auth.register') }}" class="space-y-4">@csrf
        <div><label class="form-label" for="name">Full name *</label><input type="text" id="name" name="name" value="{{ old('name') }}" class="form-input" required autocomplete="name"/>@error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror</div>
        <div><label class="form-label" for="reg_phone">Mobile *</label><div class="flex gap-2"><div class="flex-shrink-0 bg-paper border border-line rounded-xl px-3 flex items-center text-sm font-semibold text-muted font-mono">+91</div><input type="tel" id="reg_phone" name="phone" value="{{ old('phone') }}" class="form-input flex-1" placeholder="9876543210" maxlength="10" required/></div>@error('phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror</div>
        <div><label class="form-label" for="reg_email">Email <span class="text-muted font-normal normal-case">(optional)</span></label><input type="email" id="reg_email" name="email" value="{{ old('email') }}" class="form-input"/>@error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror</div>
        <div><label class="form-label" for="reg_pass">Password *</label><input type="password" id="reg_pass" name="password" class="form-input" required minlength="6"/>@error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror</div>
        <div><label class="form-label" for="reg_pass2">Confirm password *</label><input type="password" id="reg_pass2" name="password_confirmation" class="form-input" required/></div>
        <button class="btn btn-primary w-full justify-center btn-lg">Create account <x-icon name="arrow-right" class="w-4 h-4"/></button>
      </form>
    </div>
    <div class="text-center mt-4 text-sm"><span class="text-muted">Already have an account?</span> <a href="{{ route('login') }}" class="text-iris font-semibold hover:underline">Log in</a></div>
  </div>
</div>
@endsection

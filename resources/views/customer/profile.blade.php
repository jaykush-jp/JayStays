@extends('layouts.customer')
@section('title','My Profile')
@section('content')
<div class="max-w-2xl mx-auto">
  <div class="card p-7 mb-5">
    <div class="flex items-center gap-4 mb-6">
      <div class="w-16 h-16 bg-ink rounded-2xl flex items-center justify-center font-display font-bold text-white text-2xl">{{ $user->initials }}</div>
      <div><h2 class="font-display font-semibold text-xl text-ink">{{ $user->name }}</h2><p class="text-muted text-sm">{{ $user->email ?? $user->phone }}</p><p class="text-xs text-muted mt-0.5">Member since {{ $user->created_at->format('M Y') }}</p></div>
    </div>
    <form method="POST" action="{{ route('customer.profile.update') }}" class="space-y-4">@csrf
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div><label class="form-label" for="name">Full name</label><input type="text" id="name" name="name" value="{{ old('name',$user->name) }}" class="form-input" required/>@error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror</div>
        <div><label class="form-label" for="email">Email</label><input type="email" id="email" name="email" value="{{ old('email',$user->email) }}" class="form-input"/>@error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror</div>
        <div><label class="form-label">Mobile</label><input type="text" value="{{ $user->phone }}" class="form-input bg-paper" readonly/><p class="text-xs text-muted mt-1">Mobile number cannot be changed.</p></div>
      </div>
      <button class="btn btn-primary">Save changes</button>
    </form>
  </div>
  <div class="card p-6">
    <h2 class="font-display font-semibold text-ink mb-4">Account info</h2>
    <div class="space-y-1 text-sm">
      @foreach(['Role' => ucfirst($user->role), 'Status' => ucfirst($user->status), 'Member since' => $user->created_at->format('d M Y'), 'Phone verified' => ($user->phone_verified_at?'Yes':'No'), 'Email verified' => ($user->email_verified_at?'Yes':'No')] as $l=>$v)
        <div class="flex justify-between py-2.5 border-b border-line last:border-0"><span class="text-muted">{{ $l }}</span><span class="font-semibold text-ink">{{ $v }}</span></div>
      @endforeach
    </div>
  </div>
</div>
@endsection

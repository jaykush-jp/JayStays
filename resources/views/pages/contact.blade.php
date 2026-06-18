@extends('layouts.app')
@section('content')
<div class="max-w-4xl mx-auto px-4 py-14">
  <div class="text-center mb-10"><p class="eyebrow justify-center mb-2">Contact</p><h1 class="font-display text-4xl font-bold text-ink mb-2 tracking-tight">Get in touch</h1><p class="text-muted">We're available 24/7.</p></div>
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-7">
    <div class="space-y-4">
      @foreach([['phone','Phone','+91 98765 43210'],['mail','Email','support@myroom.in'],['clock','Hours','24×7 support']] as [$ic,$l,$v])
        <div class="card p-5 flex gap-3 items-start"><div class="w-10 h-10 rounded-xl bg-brand-50 text-iris flex items-center justify-center flex-shrink-0"><x-icon name="{{ $ic }}" class="w-5 h-5"/></div><div><div class="font-semibold text-ink text-sm">{{ $l }}</div><div class="text-iris font-semibold">{{ $v }}</div></div></div>
      @endforeach
    </div>
    <div class="lg:col-span-2">
      <div class="card p-7">
        @if(session('success'))<div class="alert-success mb-5 flex items-center gap-2"><x-icon name="check-circle" class="w-5 h-5"/>{{ session('success') }}</div>@endif
        <h2 class="font-display text-xl font-semibold text-ink mb-5">Send a message</h2>
        <form method="POST" action="{{ route('page.contact.submit') }}" class="space-y-4">@csrf
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div><label class="form-label" for="c-name">Name *</label><input type="text" id="c-name" name="name" value="{{ old('name') }}" class="form-input" required/>@error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror</div>
            <div><label class="form-label" for="c-email">Email *</label><input type="email" id="c-email" name="email" value="{{ old('email') }}" class="form-input" required/>@error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror</div>
          </div>
          <div><label class="form-label" for="c-subject">Subject</label><select id="c-subject" name="subject" class="form-select"><option>Booking support</option><option>Payment issue</option><option>List my hotel</option><option>General query</option></select></div>
          <div><label class="form-label" for="c-msg">Message *</label><textarea id="c-msg" name="message" rows="4" class="form-textarea" required placeholder="How can we help?">{{ old('message') }}</textarea>@error('message')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror</div>
          <button class="btn btn-primary btn-lg">Send message <x-icon name="arrow-right" class="w-4 h-4"/></button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

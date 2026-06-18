@extends('layouts.app')
@section('content')
<div class="max-w-3xl mx-auto px-4 py-14">
  <h1 class="font-display text-3xl font-bold text-ink mb-2 tracking-tight">Privacy policy</h1>
  <p class="text-muted mb-8 text-sm">Last updated: January 2025</p>
  <div class="card p-8 space-y-6">
    @foreach([['1. What we collect','Name, mobile, email, booking details and transaction IDs.'],['2. How we use it','To process bookings, send confirmations and improve the platform.'],['3. Data sharing','Booking details are shared with the hotel for check-in. We do not sell your data. Payments are processed directly via Razorpay/PhonePe.'],['4. Security','HTTPS/TLS encryption with PCI-DSS compliant payment processors.'],['5. Contact','privacy@myroom.in']] as [$h,$p])
      <div><h2 class="font-display font-semibold text-ink mb-1.5">{{ $h }}</h2><p class="text-muted text-sm leading-relaxed">{{ $p }}</p></div>
    @endforeach
  </div>
</div>
@endsection

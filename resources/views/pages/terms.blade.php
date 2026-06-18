@extends('layouts.app')
@section('content')
<div class="max-w-3xl mx-auto px-4 py-14">
  <h1 class="font-display text-3xl font-bold text-ink mb-2 tracking-tight">Terms & conditions</h1>
  <p class="text-muted mb-8 text-sm">Last updated: January 2025</p>
  <div class="card p-8 space-y-6">
    @foreach([['1. Acceptance','By using MyRoom, you agree to these terms.'],['2. Booking','MyRoom is an intermediary between guests and hotels. Advance payment confirms your reservation. The balance is paid at the hotel on arrival.'],['3. Online amount','The platform fee (~10%) is non-refundable except as per the cancellation policy.'],['4. Cancellation','2+ hours before check-in: advance refund eligible. Within 2 hours: non-refundable.'],['5. Governing law','Governed by the laws of India. Disputes are subject to Delhi courts.']] as [$h,$p])
      <div><h2 class="font-display font-semibold text-ink mb-1.5">{{ $h }}</h2><p class="text-muted text-sm leading-relaxed">{{ $p }}</p></div>
    @endforeach
  </div>
</div>
@endsection

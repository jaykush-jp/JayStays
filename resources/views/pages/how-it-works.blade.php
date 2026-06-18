@extends('layouts.app')
@section('content')
<div class="max-w-4xl mx-auto px-4 py-14">
  <div class="text-center mb-12" data-reveal>
    <p class="eyebrow justify-center mb-2">How it works</p>
    <h1 class="font-display text-4xl font-bold text-ink mb-3 tracking-tight">Book a room in four steps</h1>
    <p class="text-muted">Simple, transparent booking in minutes.</p>
  </div>
  <div class="space-y-5" data-reveal>
    @foreach([['01','search','Search & browse','Enter your city, date and time. Filter by stay type, price and rating. Browse verified hotels near you.'],['02','wallet','Pay advance online','Pay only ~10% of the room rate online to confirm your booking via Razorpay or PhonePe. Instant.'],['03','check-circle','Hotel confirms','The hotel reviews and accepts your booking. You get an SMS confirmation within minutes.'],['04','bed','Walk in & pay balance','Arrive with your Booking ID. Pay the remaining balance directly at the hotel front desk.']] as [$n,$ic,$t,$d])
      <div class="card p-7 flex gap-6 items-start">
        <div class="relative flex-shrink-0"><div class="w-14 h-14 bg-ink rounded-2xl flex items-center justify-center text-white"><x-icon name="{{ $ic }}" class="w-6 h-6"/></div><div class="absolute -top-2 -right-2 w-7 h-7 rounded-full bg-amber text-white text-xs font-bold font-mono flex items-center justify-center">{{ $n }}</div></div>
        <div><h2 class="font-display font-semibold text-ink text-lg mb-1.5">{{ $t }}</h2><p class="text-muted text-sm leading-relaxed">{{ $d }}</p></div>
      </div>
    @endforeach
  </div>
  <div class="text-center mt-10"><a href="{{ route('search') }}" class="btn btn-primary btn-xl inline-flex">Find hotels now <x-icon name="arrow-right" class="w-5 h-5"/></a></div>
</div>
@endsection

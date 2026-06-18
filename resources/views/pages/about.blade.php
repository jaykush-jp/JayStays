@extends('layouts.app')
@section('content')
<div class="max-w-4xl mx-auto px-4 py-14">
  <div class="text-center mb-12" data-reveal><p class="eyebrow justify-center mb-2">Our story</p><h1 class="font-display text-4xl font-bold text-ink mb-3 tracking-tight">About MyRoom</h1><p class="text-muted max-w-lg mx-auto">India's hourly hotel booking platform. Flexible stays, transparent pricing.</p></div>
  <div class="grid grid-cols-2 sm:grid-cols-4 gap-5 mb-12" data-reveal>
    @foreach([['500+','Hotels','building'],['50+','Cities','pin'],['50K+','Happy guests','user'],['24/7','Support','clock']] as [$v,$l,$ic])<div class="card p-6 text-center"><div class="w-10 h-10 rounded-xl bg-brand-50 text-iris flex items-center justify-center mx-auto mb-3"><x-icon name="{{ $ic }}" class="w-5 h-5"/></div><div class="font-display text-3xl font-bold text-ink mb-1 font-mono tnum">{{ $v }}</div><div class="text-muted text-sm">{{ $l }}</div></div>@endforeach
  </div>
  <div class="card p-8" data-reveal><h2 class="font-display text-2xl font-bold text-ink mb-4">Why we built MyRoom</h2><p class="text-muted leading-relaxed mb-4">MyRoom was founded to solve a simple problem: why pay for 24 hours when you only need three? We pioneered the hourly hotel model in India, making flexible, affordable stays accessible to everyone — business travellers, transit guests and explorers alike.</p><p class="text-muted leading-relaxed">Our transparent model means you pay only a small advance online to confirm your room, and settle the balance directly at the hotel. No hidden charges, no full prepayment.</p></div>
</div>
@endsection

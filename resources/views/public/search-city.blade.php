@extends('layouts.app')
@section('content')
@php $cityImgs=['Mumbai'=>'https://images.unsplash.com/photo-1570168007204-dfb528c6958f?w=1400&q=80','Delhi'=>'https://images.unsplash.com/photo-1587474260584-136574528ed5?w=1400&q=80','Bangalore'=>'https://images.unsplash.com/photo-1596176530529-78163a4f7af2?w=1400&q=80','Noida'=>'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=1400&q=80','Gurgaon'=>'https://images.unsplash.com/photo-1587474260584-136574528ed5?w=1400&q=80','Hyderabad'=>'https://images.unsplash.com/photo-1624053127670-ee1a7e6e571a?w=1400&q=80','Chennai'=>'https://images.unsplash.com/photo-1604408080693-4f0b1e77c1e9?w=1400&q=80','Kolkata'=>'https://images.unsplash.com/photo-1544461772-722f2fa3ec8c?w=1400&q=80','Pune'=>'https://images.unsplash.com/photo-1580804347220-46f3aff77a67?w=1400&q=80']; @endphp
<section class="relative h-72 lg:h-80 overflow-hidden">
  <img src="{{ $cityImgs[$cityName] ?? 'https://images.unsplash.com/photo-1587474260584-136574528ed5?w=1400&q=80' }}"
    alt="Hotels in {{ $cityName }}" class="w-full h-full object-cover" loading="eager"/>
  <div class="absolute inset-0" style="background:linear-gradient(to top,rgba(23,21,50,.85) 0%,rgba(23,21,50,.3) 60%,transparent 100%)"></div>
  <div class="absolute bottom-0 left-0 right-0 px-4 pb-8 max-w-7xl mx-auto">
    <a href="{{ route('cities') }}" class="inline-flex items-center gap-1.5 text-white/70 hover:text-white text-sm mb-3 transition-colors"><x-icon name="arrow-right" class="w-4 h-4 rotate-180"/>All cities</a>
    <h1 class="font-display font-bold text-4xl lg:text-5xl text-white mb-2 tracking-tight">Hotels in {{ $cityName }}</h1>
    <p class="text-white/75 flex items-center gap-2"><x-icon name="verified" class="w-4 h-4"/>{{ $hotels->total() }} verified hotels · hourly & overnight</p>
  </div>
</section>
<div class="bg-white border-b border-line py-3 px-4 sticky top-16 z-30">
  <div class="max-w-7xl mx-auto">
    <form method="GET" action="{{ route('search') }}" class="flex flex-wrap gap-2 items-center">
      <input type="hidden" name="city" value="{{ $cityName }}"/>
      <select name="type" class="form-select w-40 text-sm py-2.5">
        <option value="">Any type</option>
        <option value="hourly">Hourly</option>
        <option value="overnight">Overnight</option>
      </select>
      <input type="date" name="date" value="{{ date('Y-m-d') }}" class="form-input w-40 text-sm py-2.5 font-mono"/>
      <button class="btn btn-primary btn-sm"><x-icon name="search" class="w-4 h-4"/>Filter</button>
    </form>
  </div>
</div>
<div class="max-w-7xl mx-auto px-4 py-10">
  @if($hotels->isEmpty())
    <div class="card p-12 text-center"><div class="inline-flex w-16 h-16 rounded-2xl bg-brand-50 items-center justify-center mb-4"><x-icon name="building" class="w-8 h-8 text-iris"/></div><h2 class="font-display text-xl font-semibold text-ink mb-2">No hotels in {{ $cityName }} yet</h2><a href="{{ route('search') }}" class="btn btn-primary inline-flex mt-4">Browse all hotels</a></div>
  @else
    <div class="flex items-center justify-between mb-7 flex-wrap gap-3">
      <h2 class="font-display text-2xl font-bold text-ink tracking-tight"><span class="text-iris font-mono tnum">{{ $hotels->total() }}</span> hotels in {{ $cityName }}</h2>
      <div class="flex gap-2 flex-wrap">
        <span class="badge badge-success"><x-icon name="verified" class="w-3.5 h-3.5"/>Verified</span>
        <span class="badge badge-primary"><x-icon name="wallet" class="w-3.5 h-3.5"/>Pay 10% online</span>
      </div>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 mb-10">
      @foreach($hotels as $i => $hotel)
        <x-property-card :hotel="$hotel" :rank="$i"/>
      @endforeach
    </div>
    @if($hotels->hasPages())<div class="flex justify-center mb-10">{{ $hotels->links() }}</div>@endif
    <div class="card p-7">
      <h2 class="font-display text-xl font-semibold text-ink mb-3">Hourly hotels in {{ $cityName }}</h2>
      <p class="text-muted text-sm leading-relaxed mb-4">MyRoom offers {{ $hotels->total() }} verified hourly hotels in {{ $cityName }}. Pay only a small advance online — settle the room balance at the hotel on arrival.</p>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
        @foreach(['Verified hotels with real ratings','Pay only advance online','Instant SMS confirmation','No account needed'] as $f)
          <p class="text-sm text-ink/70 flex items-center gap-2"><x-icon name="check-circle" class="w-4 h-4 text-emerald-500"/>{{ $f }}</p>
        @endforeach
      </div>
    </div>
  @endif
</div>
@endsection

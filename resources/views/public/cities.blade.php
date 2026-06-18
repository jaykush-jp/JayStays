@extends('layouts.app')
@section('content')
<div class="max-w-7xl mx-auto px-4 py-12">
  <div class="text-center mb-10" data-reveal>
    <p class="eyebrow justify-center mb-2">Explore India</p>
    <h1 class="font-display font-bold text-4xl text-ink mb-2 tracking-tight">All cities</h1>
    <p class="text-muted">Find hourly hotels in {{ $cities->count() }} cities across India</p>
  </div>
  @php $cityImgs=['Mumbai'=>'https://images.unsplash.com/photo-1570168007204-dfb528c6958f?w=600&h=400&fit=crop','Delhi'=>'https://images.unsplash.com/photo-1587474260584-136574528ed5?w=600&h=400&fit=crop','Bangalore'=>'https://images.unsplash.com/photo-1596176530529-78163a4f7af2?w=600&h=400&fit=crop','Noida'=>'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=600&h=400&fit=crop','Gurgaon'=>'https://images.unsplash.com/photo-1587474260584-136574528ed5?w=600&h=400&fit=crop','Hyderabad'=>'https://images.unsplash.com/photo-1624053127670-ee1a7e6e571a?w=600&h=400&fit=crop','Chennai'=>'https://images.unsplash.com/photo-1604408080693-4f0b1e77c1e9?w=600&h=400&fit=crop','Kolkata'=>'https://images.unsplash.com/photo-1544461772-722f2fa3ec8c?w=600&h=400&fit=crop','Pune'=>'https://images.unsplash.com/photo-1580804347220-46f3aff77a67?w=600&h=400&fit=crop']; @endphp
  <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5" data-reveal>
    @foreach($cities as $city)
      <a href="{{ route('search.city', strtolower(str_replace(' ','-',$city->city))) }}" class="group card-hover overflow-hidden block">
        <div class="relative h-36 overflow-hidden">
          <img src="{{ $cityImgs[$city->city] ?? 'https://images.unsplash.com/photo-1587474260584-136574528ed5?w=600&h=400&fit=crop' }}"
            alt="Hotels in {{ $city->city }}" loading="lazy"
            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-[600ms]"/>
          <div class="absolute inset-0 bg-gradient-to-t from-ink/50 to-transparent"></div>
          <div class="absolute bottom-3 left-3 font-display font-semibold text-white text-lg">{{ $city->city }}</div>
        </div>
        <div class="p-4 flex items-center justify-between">
          <div class="text-muted text-xs flex items-center gap-1"><x-icon name="building" class="w-3.5 h-3.5"/>{{ $city->hotel_count }} hotels</div>
          <div class="flex items-center gap-1 text-xs font-semibold text-ink"><x-icon name="star" class="w-3.5 h-3.5 text-amber"/><span class="font-mono">{{ number_format($city->avg_rating ?? 4.2, 1) }}</span></div>
        </div>
      </a>
    @endforeach
  </div>
</div>
@endsection

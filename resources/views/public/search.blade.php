@extends('layouts.app')
@section('content')

{{-- Sticky search bar --}}
<div class="bg-white border-b border-line sticky top-16 z-30">
  <div class="max-w-7xl mx-auto px-4 py-3">
    <form method="GET" action="{{ route('search') }}" class="flex flex-col sm:flex-row gap-2 items-stretch sm:items-center">
      <div class="flex-[2] relative">
        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-muted pointer-events-none"><x-icon name="pin" class="w-4 h-4"/></span>
        <input name="city" value="{{ $city }}" placeholder="City, area or hotel name"
          class="w-full pl-10 pr-3 py-2.5 rounded-xl text-sm font-medium border-[1.5px] border-line bg-paper text-ink placeholder:text-muted/60 outline-none focus:border-iris focus:bg-white focus:ring-4 focus:ring-iris/12 transition-all"/>
      </div>
      <div class="flex-1 relative">
        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-muted pointer-events-none z-10"><x-icon name="calendar" class="w-4 h-4"/></span>
        <input name="date" type="date" value="{{ $date }}"
          class="w-full pl-10 pr-3 py-2.5 rounded-xl text-sm font-medium font-mono border-[1.5px] border-line bg-paper text-ink outline-none focus:border-iris focus:bg-white focus:ring-4 focus:ring-iris/12 transition-all cursor-pointer"/>
      </div>
      <div class="flex-1">
        <select name="type" class="w-full px-3 py-2.5 rounded-xl text-sm font-medium border-[1.5px] border-line bg-paper text-ink outline-none focus:border-iris focus:bg-white cursor-pointer transition-all">
          <option value="" @selected(!$stayType)>Any stay type</option>
          <option value="hourly" @selected($stayType==='hourly')>Hourly</option>
          <option value="overnight" @selected($stayType==='overnight')>Overnight</option>
        </select>
      </div>
      <button class="btn btn-primary px-8 flex-shrink-0"><x-icon name="search" class="w-4 h-4"/>Search</button>
    </form>
  </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-6">
  {{-- Mobile filter toggle --}}
  <div class="lg:hidden mb-4" x-data="{open:false}">
    <button @click="open=!open" class="btn btn-white w-full justify-between">
      <span class="flex items-center gap-2"><x-icon name="settings" class="w-4 h-4"/>Filters & sorting</span>
      <x-icon name="arrow-right" class="w-4 h-4 transition-transform rotate-90" ::class="open?'rotate-[270deg]':'rotate-90'"/>
    </button>
    <div x-show="open" x-collapse class="mt-3">
      @include('public.partials.filters')
    </div>
  </div>

  <div class="flex gap-6 items-start">
    <aside class="w-72 flex-shrink-0 hidden lg:block">
      @include('public.partials.filters')
    </aside>

    <main class="flex-1 min-w-0">
      <div class="flex items-center justify-between mb-5 flex-wrap gap-3">
        <div>
          <h1 class="font-display text-2xl font-bold text-ink tracking-tight">
            <span class="text-iris font-mono tnum">{{ $hotels->total() }}</span> {{ Str::plural('hotel',$hotels->total()) }}
            @if($city)<span class="text-muted font-normal text-lg">in</span> <span class="text-iris">{{ $city }}</span>@endif
          </h1>
          @if($stayType)<p class="text-muted text-sm mt-0.5">Showing {{ ucfirst($stayType) }} stays</p>@endif
        </div>
        <form method="GET" class="flex items-center gap-2">
          @foreach(request()->except(['sort','page']) as $k=>$v)<input type="hidden" name="{{ $k }}" value="{{ $v }}"/>@endforeach
          <label class="text-sm text-muted font-medium hidden sm:block">Sort</label>
          <select name="sort" onchange="this.form.submit()" class="px-3 py-2 rounded-xl text-sm font-medium border border-line bg-white text-ink outline-none focus:border-iris cursor-pointer">
            <option value="">Recommended</option>
            <option value="price_low" @selected(request('sort')==='price_low')>Price: low to high</option>
            <option value="price_high" @selected(request('sort')==='price_high')>Price: high to low</option>
            <option value="rating" @selected(request('sort')==='rating')>Top rated</option>
          </select>
        </form>
      </div>

      {{-- Active filter chips --}}
      @if($stayType || request('min_rating') || request('tags') || request('max_price'))
        <div class="flex flex-wrap gap-2 mb-5">
          @if($stayType)
            <a href="{{ route('search', request()->except('type','page')) }}" class="inline-flex items-center gap-1.5 bg-brand-50 text-iris text-xs font-semibold px-3 py-1.5 rounded-full hover:bg-brand-100 transition-colors">
              {{ ucfirst($stayType) }} <x-icon name="close" class="w-3 h-3"/>
            </a>
          @endif
          @if(request('min_rating'))
            <a href="{{ route('search', request()->except('min_rating','page')) }}" class="inline-flex items-center gap-1.5 bg-amber-soft text-amber-deep text-xs font-semibold px-3 py-1.5 rounded-full hover:opacity-80 transition-opacity">
              <x-icon name="star" class="w-3 h-3"/>{{ request('min_rating') }}+ <x-icon name="close" class="w-3 h-3"/>
            </a>
          @endif
          @if(request('tags'))
            <a href="{{ route('search', request()->except('tags','page')) }}" class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 text-xs font-semibold px-3 py-1.5 rounded-full hover:bg-emerald-100 transition-colors">
              {{ ucwords(str_replace('_',' ',request('tags'))) }} <x-icon name="close" class="w-3 h-3"/>
            </a>
          @endif
          <a href="{{ route('search', ['city'=>$city]) }}" class="text-xs text-muted font-semibold px-2 py-1.5 hover:text-red-500 transition-colors">Clear all</a>
        </div>
      @endif

      @if($hotels->isEmpty())
        <div class="card p-14 text-center">
          <div class="inline-flex w-16 h-16 rounded-2xl bg-brand-50 items-center justify-center mb-4"><x-icon name="search" class="w-8 h-8 text-iris"/></div>
          <h2 class="font-display text-xl font-semibold text-ink mb-2">No hotels found</h2>
          <p class="text-muted text-sm mb-6 max-w-sm mx-auto">We couldn't find hotels matching your filters. Try a different city or widen your search.</p>
          <a href="{{ route('search') }}" class="btn btn-primary inline-flex">Clear all filters</a>
        </div>
      @else
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
          @foreach($hotels as $i => $hotel)
            <x-property-card :hotel="$hotel" :rank="$i"/>
          @endforeach
        </div>
        @if($hotels->hasPages())
          <div class="mt-8 flex justify-center">{{ $hotels->appends(request()->query())->links() }}</div>
        @endif
      @endif
    </main>
  </div>
</div>
@endsection

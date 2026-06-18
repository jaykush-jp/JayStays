@extends('layouts.app')
@section('content')

{{-- ══════════════════════════════════════════════════════════════
     HERO — the booking moment, time-of-day gradient, glass search
══════════════════════════════════════════════════════════════ --}}
<section class="relative overflow-hidden" aria-label="Book a hotel by the hour">
  {{-- Deep midnight base + time-of-day wash --}}
  <div class="absolute inset-0 z-0" style="background:#171532"></div>
  <div class="absolute inset-0 z-0 ink-grid opacity-50"></div>
  <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none">
    <div class="absolute -top-40 -left-24 w-[34rem] h-[34rem] rounded-full opacity-30 animate-drift" style="background:radial-gradient(circle,#5B5BD6,transparent 65%)"></div>
    <div class="absolute top-10 right-0 w-[28rem] h-[28rem] rounded-full opacity-25 animate-drift" style="background:radial-gradient(circle,#FF8A3D,transparent 65%);animation-delay:-6s"></div>
    <div class="absolute -bottom-32 left-1/3 w-[26rem] h-[26rem] rounded-full opacity-20 animate-drift" style="background:radial-gradient(circle,#8585DF,transparent 65%);animation-delay:-3s"></div>
  </div>

  <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 w-full pt-16 pb-20 lg:pt-20 lg:pb-28">
    <div class="grid grid-cols-1 lg:grid-cols-[1.05fr_.95fr] gap-12 lg:gap-10 items-center">

      {{-- LEFT --}}
      <div>
        <div class="inline-flex items-center gap-2 bg-white/10 border border-white/15 text-white/90 text-xs font-medium px-3.5 py-1.5 rounded-full mb-7">
          <span class="relative flex h-2 w-2"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span><span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-400"></span></span>
          India's #1 hourly hotel platform
        </div>

        <h1 class="font-display font-bold text-white leading-[1.02] tracking-tight mb-6">
          <span class="block text-5xl sm:text-6xl lg:text-[4.5rem]">Hotels, billed</span>
          <span class="block text-5xl sm:text-6xl lg:text-[4.5rem]">by the <span class="relative inline-block">hour<svg class="absolute -bottom-2 left-0 w-full" height="10" viewBox="0 0 200 10" fill="none" preserveAspectRatio="none"><path d="M2 7C50 3 150 3 198 6" stroke="#FF8A3D" stroke-width="3.5" stroke-linecap="round"/></svg></span>.</span>
        </h1>

        <p class="text-white/65 text-lg leading-relaxed mb-8 max-w-md">
          Book a room for 3, 6 or 12 hours — or stay overnight — across 50+ cities. Pay a small advance online, settle the rest at the hotel. No account needed.
        </p>

        {{-- Search widget --}}
        <div x-data="searchBox({{ $cities->toJson() }})" class="max-w-xl">
          <form method="GET" action="{{ route('search') }}">
            <div class="bg-white rounded-2xl shadow-lift overflow-visible">
              <div class="flex flex-col sm:flex-row items-stretch">
                <div class="flex-[2] p-4 border-b sm:border-b-0 sm:border-r border-line relative" @click.stop>
                  <div class="text-[10px] font-semibold text-muted uppercase tracking-widest mb-1 flex items-center gap-1"><x-icon name="pin" class="w-3 h-3"/>Where</div>
                  <input name="city" type="text" x-model="city" @input="filter" @focus="filter" @keydown.escape="open=false" autocomplete="off"
                    placeholder="City, area or hotel"
                    class="w-full text-ink font-semibold outline-none bg-transparent text-sm placeholder:text-muted/60"/>
                  <div x-show="open" @click.outside="open=false" x-transition.opacity
                    class="absolute top-full left-0 mt-2 bg-white rounded-2xl shadow-lift border border-line z-50 w-72 max-h-64 overflow-y-auto p-1.5">
                    <template x-for="c in filtered.slice(0,8)" :key="c.city">
                      <button type="button" @click="pick(c.city)"
                        class="flex items-center justify-between w-full px-3 py-2.5 hover:bg-brand-50 rounded-xl text-sm text-left transition-colors">
                        <span class="flex items-center gap-2"><x-icon name="pin" class="w-4 h-4 text-iris"/><span class="font-semibold text-ink" x-text="c.city"></span></span>
                        <span class="text-muted text-xs font-mono" x-text="c.count+' hotels'"></span>
                      </button>
                    </template>
                    @if($cities->isEmpty())
                      @foreach(['Delhi','Noida','Mumbai','Bangalore','Gurgaon'] as $c)
                        <button type="button" @click="pick('{{ $c }}')" class="flex items-center gap-2 w-full px-3 py-2.5 hover:bg-brand-50 rounded-xl text-sm">
                          <x-icon name="pin" class="w-4 h-4 text-iris"/><span class="font-semibold text-ink">{{ $c }}</span>
                        </button>
                      @endforeach
                    @endif
                  </div>
                </div>
                <div class="flex-1 p-4 border-b sm:border-b-0 sm:border-r border-line">
                  <div class="text-[10px] font-semibold text-muted uppercase tracking-widest mb-1 flex items-center gap-1"><x-icon name="calendar" class="w-3 h-3"/>Date</div>
                  <input name="date" type="date" x-model="date" :min="new Date().toISOString().split('T')[0]"
                    class="w-full text-ink font-semibold outline-none bg-transparent text-sm cursor-pointer font-mono"/>
                </div>
                <div class="flex-1 p-4 border-b sm:border-b-0 sm:border-r border-line">
                  <div class="text-[10px] font-semibold text-muted uppercase tracking-widest mb-1 flex items-center gap-1"><x-icon name="clock" class="w-3 h-3"/>Time</div>
                  <select name="time" x-model="time" class="w-full text-ink font-semibold outline-none bg-transparent text-sm cursor-pointer font-mono">
                    <template x-for="t in times" :key="t.value"><option :value="t.value" x-text="t.label"></option></template>
                  </select>
                </div>
                <div class="p-2.5">
                  <button type="submit" class="btn btn-primary w-full sm:w-auto h-full !rounded-xl text-base font-semibold px-7">
                    <x-icon name="search" class="w-5 h-5"/><span class="sm:hidden lg:inline">Search</span>
                  </button>
                </div>
              </div>
            </div>
          </form>
        </div>

        {{-- Trust row --}}
        <div class="flex flex-wrap items-center gap-x-6 gap-y-2 mt-6">
          @foreach([['check-circle','No account needed'],['bolt','Instant confirmation'],['shield','Free cancellation']] as [$ic,$t])
            <div class="flex items-center gap-1.5 text-white/70 text-sm">
              <x-icon name="{{ $ic }}" class="w-4 h-4 text-emerald-400"/> {{ $t }}
            </div>
          @endforeach
        </div>
      </div>

      {{-- RIGHT: floating glass proof cards --}}
      <div class="hidden lg:block relative h-[480px]" aria-hidden="true">
        @if($featured->count() >= 1)
        <div class="glass absolute top-0 right-4 w-72 p-4 rounded-2xl shadow-lift animate-floaty">
          <div class="flex gap-3 items-start">
            <div class="w-14 h-14 rounded-xl overflow-hidden flex-shrink-0 bg-brand-100">
              @if($featured->first()?->cover_image)
                <img src="{{ $featured->first()->cover_image }}" class="w-full h-full object-cover" alt="" loading="lazy"/>
              @else<div class="w-full h-full flex items-center justify-center"><x-icon name="building" class="w-6 h-6 text-iris"/></div>@endif
            </div>
            <div class="min-w-0">
              <div class="font-display font-semibold text-ink text-sm leading-tight truncate">{{ $featured->first()?->name ?? 'The Hourly Inn' }}</div>
              <div class="text-muted text-xs mt-0.5 truncate flex items-center gap-1"><x-icon name="pin" class="w-3 h-3"/>{{ $featured->first()?->city ?? 'Noida' }}</div>
              <div class="flex items-center gap-1 mt-1"><x-icon name="star" class="w-3.5 h-3.5 text-amber"/><span class="font-bold text-ink text-xs font-mono">{{ $featured->first()?->avg_rating ?? '4.5' }}</span></div>
            </div>
          </div>
          <div class="grid grid-cols-3 gap-1.5 mt-3">
            @foreach([['3h', $featured->first()?->rooms->first()?->price_3hr ?? 299], ['6h', $featured->first()?->rooms->first()?->price_6hr ?? 499], ['12h', $featured->first()?->rooms->first()?->price_12hr ?? 799]] as [$lbl,$p])
              <div class="bg-brand-50 rounded-xl p-2 text-center">
                <div class="text-iris-deep font-bold text-sm font-mono tnum">₹{{ number_format($p) }}</div>
                <div class="text-muted text-[10px] font-mono">{{ $lbl }}</div>
              </div>
            @endforeach
          </div>
        </div>
        @endif

        <div class="glass absolute top-44 left-0 px-4 py-3 rounded-2xl shadow-soft animate-floaty" style="animation-delay:-2s">
          <div class="flex items-center gap-2.5">
            <div class="w-9 h-9 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600"><x-icon name="check" class="w-5 h-5"/></div>
            <div>
              <div class="text-ink font-bold text-sm font-mono tnum">{{ number_format($stats['bookings'] ?? 0) }}+ bookings</div>
              <div class="text-muted text-xs">this month</div>
            </div>
          </div>
        </div>

        @if($featured->count() >= 2)
        <div class="glass absolute bottom-6 right-0 w-60 p-4 rounded-2xl shadow-soft animate-floaty" style="animation-delay:-4s">
          <div class="flex gap-3 items-center">
            <div class="w-12 h-12 rounded-xl overflow-hidden flex-shrink-0 bg-brand-100">
              @if($featured->skip(1)->first()?->cover_image)
                <img src="{{ $featured->skip(1)->first()->cover_image }}" class="w-full h-full object-cover" alt="" loading="lazy"/>
              @else<div class="w-full h-full flex items-center justify-center"><x-icon name="building" class="w-5 h-5 text-iris"/></div>@endif
            </div>
            <div class="min-w-0">
              <div class="font-display font-semibold text-ink text-xs truncate">{{ $featured->skip(1)->first()?->name ?? 'City Suites' }}</div>
              <div class="text-muted text-xs">{{ $featured->skip(1)->first()?->city ?? 'Delhi' }}</div>
              <div class="text-iris font-bold text-sm mt-0.5 font-mono tnum">₹{{ number_format($featured->skip(1)->first()?->rooms->min('overnight_price') ?? 1299) }}<span class="text-muted text-[10px] font-sans">/night</span></div>
            </div>
          </div>
        </div>
        @endif

        <div class="glass absolute top-1/2 left-6 px-3.5 py-2.5 rounded-2xl shadow-soft animate-floaty" style="animation-delay:-1s">
          <div class="flex items-center gap-2">
            <x-icon name="star" class="w-5 h-5 text-amber"/>
            <div><div class="font-bold text-ink text-sm font-mono">4.7 avg</div><div class="text-muted text-[10px]">platform rating</div></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ══════════════ STATS BAR ══════════════ --}}
<div class="bg-white border-b border-line">
  <div class="max-w-7xl mx-auto px-4 py-5">
    <div class="grid grid-cols-2 md:grid-cols-5 gap-5">
      @foreach([
        ['building', number_format($stats['hotels'] ?? 500).'+', 'Verified hotels'],
        ['pin', ($stats['cities'] ?? 50).'+', 'Cities across India'],
        ['check-circle', number_format($stats['bookings'] ?? 50000).'+', 'Happy guests'],
        ['bolt', '2 min', 'Avg booking time'],
        ['wallet', '~10%', 'Paid online only'],
      ] as [$icon,$val,$lbl])
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 bg-brand-50 rounded-xl flex items-center justify-center text-iris flex-shrink-0"><x-icon name="{{ $icon }}" class="w-5 h-5"/></div>
          <div>
            <div class="font-display font-bold text-ink text-lg leading-tight font-mono tnum">{{ $val }}</div>
            <div class="text-muted text-xs">{{ $lbl }}</div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</div>

{{-- ══════════════ POPULAR CITIES ══════════════ --}}
<section class="py-16 bg-paper" aria-labelledby="cities-h">
  <div class="max-w-7xl mx-auto px-4">
    <div class="flex items-end justify-between mb-8" data-reveal>
      <div>
        <p class="eyebrow mb-2">Explore India</p>
        <h2 id="cities-h" class="font-display font-bold text-3xl sm:text-4xl text-ink tracking-tight">Top cities for short stays</h2>
      </div>
      <a href="{{ route('cities') }}" class="btn btn-ghost btn-sm hidden sm:inline-flex">All cities <x-icon name="arrow-right" class="w-4 h-4"/></a>
    </div>

    @php
      $cityImgs = [
        'Mumbai'=>'https://images.unsplash.com/photo-1570168007204-dfb528c6958f?w=400&h=400&fit=crop',
        'Delhi'=>'https://images.unsplash.com/photo-1587474260584-136574528ed5?w=400&h=400&fit=crop',
        'Bangalore'=>'https://images.unsplash.com/photo-1596176530529-78163a4f7af2?w=400&h=400&fit=crop',
        'Noida'=>'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=400&h=400&fit=crop',
        'Gurgaon'=>'https://images.unsplash.com/photo-1587474260584-136574528ed5?w=400&h=400&fit=crop',
        'Hyderabad'=>'https://images.unsplash.com/photo-1624053127670-ee1a7e6e571a?w=400&h=400&fit=crop',
        'Chennai'=>'https://images.unsplash.com/photo-1604408080693-4f0b1e77c1e9?w=400&h=400&fit=crop',
        'Kolkata'=>'https://images.unsplash.com/photo-1544461772-722f2fa3ec8c?w=400&h=400&fit=crop',
        'Pune'=>'https://images.unsplash.com/photo-1580804347220-46f3aff77a67?w=400&h=400&fit=crop',
      ];
    @endphp

    <div class="grid grid-cols-3 sm:grid-cols-5 lg:grid-cols-9 gap-3" data-reveal>
      @foreach($cities->take(8) as $city)
        <a href="{{ route('search.city', strtolower(str_replace(' ','-',$city->city))) }}" class="group flex flex-col items-center gap-2.5">
          <div class="relative w-full aspect-square rounded-2xl overflow-hidden shadow-soft">
            <img src="{{ $cityImgs[$city->city] ?? 'https://images.unsplash.com/photo-1587474260584-136574528ed5?w=400&h=400&fit=crop' }}"
              alt="Hotels in {{ $city->city }}" loading="lazy"
              class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-[600ms]"/>
            <div class="absolute inset-0 bg-gradient-to-t from-ink/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <div class="absolute inset-0 ring-0 group-hover:ring-2 ring-iris ring-offset-2 ring-offset-paper rounded-2xl transition-all"></div>
          </div>
          <div class="text-center">
            <div class="text-xs font-semibold text-ink group-hover:text-iris transition-colors">{{ $city->city }}</div>
            <div class="text-[10px] text-muted font-mono">{{ $city->count }} hotels</div>
          </div>
        </a>
      @endforeach
      <a href="{{ route('cities') }}" class="group flex flex-col items-center gap-2.5">
        <div class="w-full aspect-square rounded-2xl bg-brand-50 border-2 border-dashed border-brand-200 flex items-center justify-center group-hover:bg-brand-100 group-hover:border-iris transition-all">
          <x-icon name="plus" class="w-7 h-7 text-iris"/>
        </div>
        <div class="text-xs font-semibold text-muted group-hover:text-iris transition-colors">More</div>
      </a>
    </div>
  </div>
</section>

{{-- ══════════════ FEATURED HOTELS ══════════════ --}}
<section class="py-16 bg-white" aria-labelledby="featured-h">
  <div class="max-w-7xl mx-auto px-4">
    <div class="flex items-end justify-between mb-8" data-reveal>
      <div>
        <p class="eyebrow mb-2">Handpicked</p>
        <h2 id="featured-h" class="font-display font-bold text-3xl sm:text-4xl text-ink tracking-tight">Featured properties</h2>
      </div>
      <a href="{{ route('search') }}" class="btn btn-ghost btn-sm hidden sm:inline-flex">View all <x-icon name="arrow-right" class="w-4 h-4"/></a>
    </div>
    @if($featured->isEmpty())
      <div class="card p-12 text-center text-muted">No featured properties yet. <a href="{{ route('search') }}" class="text-iris font-semibold">Browse all hotels</a>.</div>
    @else
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5" data-reveal>
        @foreach($featured as $i => $hotel)
          <x-property-card :hotel="$hotel" :rank="$i"/>
        @endforeach
      </div>
    @endif
    <div class="text-center mt-10">
      <a href="{{ route('search') }}" class="btn btn-primary btn-lg inline-flex">Browse all hotels <x-icon name="arrow-right" class="w-4 h-4"/></a>
    </div>
  </div>
</section>

{{-- ══════════════ HOW IT WORKS — dark, time-themed ══════════════ --}}
<section class="relative py-20 overflow-hidden bg-ink">
  <div class="absolute inset-0 ink-grid opacity-40"></div>
  <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[40rem] h-[20rem] rounded-full opacity-25 pointer-events-none" style="background:radial-gradient(circle,#5B5BD6,transparent 70%)"></div>
  <div class="relative max-w-6xl mx-auto px-4">
    <div class="text-center mb-14" data-reveal>
      <p class="eyebrow justify-center mb-2" style="color:#A9A9E9">How it works</p>
      <h2 class="font-display font-bold text-4xl text-white mb-3 tracking-tight">Four steps. Under two minutes.</h2>
      <p class="text-white/55 max-w-lg mx-auto">Pay a small advance online to lock the room. Settle the rest at the hotel. Always transparent.</p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-5 relative" data-reveal>
      <div class="hidden md:block absolute top-9 left-[12.5%] right-[12.5%] h-px" style="background:linear-gradient(90deg,transparent,rgba(169,169,233,.4),rgba(169,169,233,.4),transparent)"></div>
      @foreach([
        ['search','Search','Enter city, date and time. Filter by price, area and stay length.'],
        ['wallet','Pay advance','Pay only ~10% online via Razorpay or PhonePe. Your room is locked.'],
        ['check-circle','Hotel confirms','The hotel reviews and confirms within minutes. You get an SMS.'],
        ['bed','Walk in','Show your Booking ID. Pay the balance at the front desk.'],
      ] as $i => [$icon,$title,$desc])
        <div class="text-center relative">
          <div class="w-18 h-18 w-[4.5rem] h-[4.5rem] rounded-2xl bg-white/8 border border-white/15 flex items-center justify-center mx-auto mb-5 relative">
            <x-icon name="{{ $icon }}" class="w-7 h-7 text-white"/>
            <div class="absolute -top-2 -right-2 w-6 h-6 rounded-full bg-amber font-mono font-bold text-white text-xs flex items-center justify-center">{{ $i+1 }}</div>
          </div>
          <h3 class="text-white font-display font-semibold text-lg mb-2">{{ $title }}</h3>
          <p class="text-white/55 text-sm leading-relaxed">{{ $desc }}</p>
        </div>
      @endforeach
    </div>
    <div class="text-center mt-12" data-reveal>
      <a href="{{ route('page.how-it-works') }}" class="btn btn-white btn-lg inline-flex">See the full guide <x-icon name="arrow-right" class="w-4 h-4"/></a>
    </div>
  </div>
</section>

{{-- ══════════════ TRANSPARENT PRICING ══════════════ --}}
<section class="py-16 bg-paper">
  <div class="max-w-5xl mx-auto px-4">
    <div class="text-center mb-12" data-reveal>
      <p class="eyebrow justify-center mb-2">Pricing</p>
      <h2 class="font-display font-bold text-3xl sm:text-4xl text-ink mb-2 tracking-tight">What you see is what you pay</h2>
      <p class="text-muted">No hidden charges. No full prepayment. Ever.</p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 items-stretch" data-reveal>
      @foreach([
        ['tag','Room rate','The full price set by the hotel — e.g. ₹500 for 2 hours.',false],
        ['wallet','You pay online','Just ~10% — e.g. ₹50 — to confirm your room instantly.',true],
        ['building','Pay at hotel','The remaining balance — e.g. ₹450 — by cash or card on arrival.',false],
      ] as $idx => [$icon,$t,$d,$hl])
        <div class="relative rounded-2xl p-7 text-center {{ $hl ? 'bg-ink text-white shadow-lift md:-translate-y-3' : 'bg-white border border-line' }}">
          @if($hl)<span class="absolute top-4 right-4 badge bg-amber text-white text-[10px]">You pay this</span>@endif
          <div class="w-12 h-12 rounded-xl flex items-center justify-center mx-auto mb-4 {{ $hl ? 'bg-white/10 text-white' : 'bg-brand-50 text-iris' }}"><x-icon name="{{ $icon }}" class="w-6 h-6"/></div>
          <h3 class="font-display font-semibold text-lg mb-2 {{ $hl ? 'text-white' : 'text-ink' }}">{{ $t }}</h3>
          <p class="text-sm leading-relaxed {{ $hl ? 'text-white/60' : 'text-muted' }}">{{ $d }}</p>
        </div>
      @endforeach
    </div>
  </div>
</section>

{{-- ══════════════ OFFERS ══════════════ --}}
@if($offers->isNotEmpty())
<section class="py-16 bg-white">
  <div class="max-w-7xl mx-auto px-4">
    <div class="flex items-end justify-between mb-8" data-reveal>
      <div>
        <p class="eyebrow mb-2" style="color:#E5701F">Deals</p>
        <h2 class="font-display font-bold text-3xl sm:text-4xl text-ink tracking-tight">Exclusive offers</h2>
      </div>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4" data-reveal>
      @foreach($offers as $offer)
        <div class="relative card-hover p-5 overflow-hidden" x-data="{copied:false}">
          <div class="absolute -right-6 -top-6 w-20 h-20 rounded-full bg-amber-soft"></div>
          <div class="relative">
            <div class="flex items-center gap-1.5 text-amber-deep mb-3"><x-icon name="gift" class="w-5 h-5"/><span class="text-xs font-semibold uppercase tracking-wide">Limited</span></div>
            <div class="font-display font-bold text-3xl text-ink mb-1 font-mono tnum">
              {{ $offer->type==='percentage' ? $offer->discount.'%' : '₹'.$offer->discount }}<span class="text-base text-muted font-sans"> off</span>
            </div>
            <div class="font-semibold text-ink mb-3">{{ $offer->title }}</div>
            <button type="button" @click="navigator.clipboard.writeText('{{ $offer->code }}');copied=true;setTimeout(()=>copied=false,1500)"
              class="inline-flex items-center gap-2 bg-brand-50 hover:bg-brand-100 text-iris-deep font-mono font-semibold text-xs px-3 py-1.5 rounded-lg tracking-widest transition-colors mb-2">
              <x-icon name="tag" class="w-3.5 h-3.5"/><span x-text="copied ? 'Copied!' : '{{ $offer->code }}'"></span>
            </button>
            <p class="text-muted text-xs">{{ ucfirst($offer->stay_type) }} · {{ $offer->valid_to?->format('d M Y') ?? 'No expiry' }}</p>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- ══════════════ REVIEWS ══════════════ --}}
<section class="py-16 bg-paper">
  <div class="max-w-7xl mx-auto px-4">
    <div class="text-center mb-12" data-reveal>
      <p class="eyebrow justify-center mb-2">Loved by travellers</p>
      <h2 class="font-display font-bold text-3xl sm:text-4xl text-ink tracking-tight">What guests say</h2>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5" data-reveal>
      @foreach([
        ['Rahul S.','Delhi',5,'Booked a room between flights at Aerocity. Paid just ₹89 online. Clean room, friendly staff. This is now my go-to for every layover.'],
        ['Priya M.','Mumbai',5,'Needed a day-use room near Andheri for an interview. Found one in minutes and zero advance hassle at the hotel. Brilliant concept.'],
        ['Arjun K.','Bangalore',4,'Business traveller here. Booked a quiet room near MG Road to work. Far better than a café — the hotel confirmed within 20 minutes.'],
      ] as [$name,$city,$rating,$review])
        <figure class="card p-6">
          <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-full bg-ink flex items-center justify-center font-display font-bold text-white text-sm">{{ substr($name,0,1) }}</div>
              <figcaption><div class="font-semibold text-ink text-sm">{{ $name }}</div><div class="text-muted text-xs flex items-center gap-1"><x-icon name="pin" class="w-3 h-3"/>{{ $city }}</div></figcaption>
            </div>
            <div class="flex gap-0.5">@for($s=0;$s<$rating;$s++)<x-icon name="star" class="w-4 h-4 text-amber"/>@endfor</div>
          </div>
          <blockquote class="text-muted text-sm leading-relaxed">“{{ $review }}”</blockquote>
        </figure>
      @endforeach
    </div>
  </div>
</section>

{{-- ══════════════ FAQ ══════════════ --}}
<section class="py-16 bg-white">
  <div class="max-w-3xl mx-auto px-4">
    <div class="text-center mb-10" data-reveal>
      <p class="eyebrow justify-center mb-2">Questions</p>
      <h2 class="font-display font-bold text-3xl sm:text-4xl text-ink tracking-tight">Quick answers</h2>
    </div>
    <div class="space-y-2.5" x-data="{open:0}" data-reveal>
      @foreach([
        ['How does hourly hotel booking work on MyRoom?','Search by city and time, choose a room, fill your name and phone, pay the advance online, get an SMS, then walk in with your Booking ID and pay the balance at the hotel.'],
        ['Do I need to create an account to book?','No. You can book completely without logging in — just your name and a 10-digit mobile number.'],
        ['What do I pay online vs. at the hotel?','You pay only ~10% of the room rate online (the platform fee). The remaining balance is paid directly at the hotel on arrival.'],
        ['Does the hotel need to confirm my booking?','Yes. After payment the hotel is notified and confirms — usually within 30 minutes. If rejected, your advance is fully refunded.'],
        ['Is my payment secure?','Yes. We use Razorpay and PhonePe — both RBI-approved and PCI-DSS compliant, with 256-bit SSL encryption.'],
      ] as $i => [$q,$a])
        <div class="card overflow-hidden">
          <button @click="open===@js($i)?open=null:open=@js($i)"
            class="w-full flex justify-between items-center gap-3 px-5 py-4 text-left text-sm font-semibold text-ink hover:bg-paper transition-colors"
            :aria-expanded="open===@js($i)">
            <span>{{ $q }}</span>
            <span class="w-7 h-7 rounded-full bg-brand-50 flex items-center justify-center flex-shrink-0 transition-transform duration-300 text-iris" :class="open===@js($i)?'rotate-45':''"><x-icon name="plus" class="w-4 h-4"/></span>
          </button>
          <div x-show="open===@js($i)" x-collapse>
            <div class="px-5 pb-4 pt-1 text-muted text-sm leading-relaxed">{{ $a }}</div>
          </div>
        </div>
      @endforeach
    </div>
    <div class="text-center mt-6"><a href="{{ route('page.faq') }}" class="text-iris font-semibold text-sm hover:underline inline-flex items-center gap-1">View all FAQs <x-icon name="arrow-right" class="w-4 h-4"/></a></div>
  </div>
</section>

{{-- ══════════════ FINAL CTA ══════════════ --}}
<section class="relative py-20 overflow-hidden bg-ink">
  <div class="absolute inset-0 ink-grid opacity-40"></div>
  <div class="absolute -top-20 right-1/4 w-72 h-72 rounded-full opacity-25 pointer-events-none animate-drift" style="background:radial-gradient(circle,#FF8A3D,transparent 70%)"></div>
  <div class="absolute -bottom-20 left-1/4 w-72 h-72 rounded-full opacity-25 pointer-events-none animate-drift" style="background:radial-gradient(circle,#5B5BD6,transparent 70%);animation-delay:-5s"></div>
  <div class="relative max-w-3xl mx-auto px-4 text-center" data-reveal>
    <div class="inline-flex w-14 h-14 rounded-2xl bg-white/10 items-center justify-center mb-5"><x-icon name="clock" class="w-7 h-7 text-white"/></div>
    <h2 class="font-display font-bold text-4xl lg:text-5xl text-white mb-4 tracking-tight">Your next room is an hour away</h2>
    <p class="text-white/60 text-lg mb-8 max-w-xl mx-auto">Join {{ number_format($stats['bookings'] ?? 50000) }}+ guests who book in two minutes — no account needed.</p>
    <div class="flex flex-col sm:flex-row gap-3 justify-center">
      <a href="{{ route('search') }}" class="btn btn-accent btn-xl justify-center"><x-icon name="search" class="w-5 h-5"/>Find hotels now</a>
      <a href="{{ route('hotel.register') }}" class="btn btn-white btn-xl justify-center"><x-icon name="building" class="w-5 h-5"/>List your hotel</a>
    </div>
  </div>
</section>

@endsection

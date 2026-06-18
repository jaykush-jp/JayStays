@props(['hotel','rank'=>0])
@php
  $minH  = $hotel->rooms->whereNotNull('hourly_price')->min('hourly_price');
  $minO  = $hotel->rooms->whereNotNull('overnight_price')->min('overnight_price');
  $min   = collect([$minH,$minO])->filter()->min() ?? 0;
  $offer = $hotel->relationLoaded('offers') && $hotel->offers->isNotEmpty();
  $tiers = [];
  $first = $hotel->rooms->first();
  if ($first) {
    if ($first->price_3hr)  $tiers['3h']  = $first->price_3hr;
    if ($first->price_6hr)  $tiers['6h']  = $first->price_6hr;
    if ($first->price_12hr) $tiers['12h'] = $first->price_12hr;
  }
@endphp

<article class="card-hover group overflow-hidden" itemscope itemtype="https://schema.org/LodgingBusiness">
  <a href="{{ route('hotel.show', $hotel->slug) }}" class="block" itemprop="url">
    {{-- Image --}}
    <div class="relative h-48 overflow-hidden rounded-t-[1.25rem]">
      @if($hotel->cover_image)
        <img src="{{ $hotel->cover_image }}" alt="{{ $hotel->name }} — hotel in {{ $hotel->city }}" itemprop="image"
          class="w-full h-full object-cover group-hover:scale-[1.06] transition-transform duration-[600ms] ease-out" loading="lazy"/>
      @else
        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-ink to-ink-soft">
          <x-icon name="building" class="w-12 h-12 text-white/30"/>
        </div>
      @endif
      <div class="absolute inset-0 bg-gradient-to-t from-ink/55 via-transparent to-transparent"></div>

      {{-- Top badges --}}
      <div class="absolute top-3 left-3 flex gap-1.5 flex-wrap">
        @if($hotel->listing_priority === 'top')
          <span class="badge bg-white/95 text-ink shadow-soft"><x-icon name="star" class="w-3 h-3 text-amber"/>Top rated</span>
        @endif
        @if($offer)
          <span class="badge bg-amber text-white shadow-soft"><x-icon name="tag" class="w-3 h-3"/>Offer</span>
        @endif
        @if($rank < 2 && $hotel->total_reviews > 0)
          <span class="badge bg-emerald-500 text-white shadow-soft"><x-icon name="trending" class="w-3 h-3"/>Most booked</span>
        @endif
      </div>

      {{-- bottom-left feature tags --}}
      @if($hotel->couple_friendly || $hotel->accepts_local_id)
        <div class="absolute bottom-3 left-3 flex gap-1.5">
          @if($hotel->couple_friendly)
            <span class="text-[10px] glass text-ink px-2 py-0.5 rounded-full font-semibold">Couple friendly</span>
          @endif
          @if($hotel->accepts_local_id)
            <span class="text-[10px] glass text-ink px-2 py-0.5 rounded-full font-semibold">Local ID OK</span>
          @endif
        </div>
      @endif

      {{-- rating chip --}}
      <div class="absolute bottom-3 right-3 flex items-center gap-1 glass px-2 py-1 rounded-lg"
           itemprop="aggregateRating" itemscope itemtype="https://schema.org/AggregateRating">
        <x-icon name="star" class="w-3.5 h-3.5 text-amber"/>
        <span class="font-bold text-ink text-xs font-mono" itemprop="ratingValue">{{ $hotel->avg_rating }}</span>
        <span class="text-muted text-[10px]" itemprop="reviewCount">({{ $hotel->total_reviews }})</span>
      </div>
    </div>

    {{-- Body --}}
    <div class="p-4">
      <h3 class="font-display font-semibold text-ink text-[15px] leading-snug mb-1 group-hover:text-iris transition-colors" itemprop="name">
        {{ $hotel->name }}
      </h3>
      <p class="text-muted text-xs mb-3 flex items-center gap-1" itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">
        <x-icon name="pin" class="w-3.5 h-3.5 text-muted/70"/>
        <span itemprop="addressLocality">{{ $hotel->area ? $hotel->area.', ' : '' }}{{ $hotel->city }}</span>
      </p>

      {{-- Signature: hourly time-tier pills (mono numerals) --}}
      @if(!empty($tiers))
        <div class="flex gap-1.5 mb-3 flex-wrap">
          @foreach($tiers as $label => $price)
            <span class="inline-flex items-center gap-1 text-[11px] bg-brand-50 text-iris-deep font-semibold px-2 py-1 rounded-lg">
              <span class="font-mono">{{ $label }}</span>
              <span class="w-px h-3 bg-brand-200"></span>
              <span class="font-mono tnum">₹{{ number_format($price) }}</span>
            </span>
          @endforeach
        </div>
      @endif

      {{-- Footer --}}
      <div class="flex items-end justify-between pt-3 border-t border-line">
        <div class="flex items-center gap-1.5 text-xs text-muted">
          <x-icon name="clock" class="w-3.5 h-3.5"/> Instant confirm
        </div>
        <div class="text-right">
          <span class="text-[11px] text-muted">from </span>
          <span class="text-iris font-display font-bold text-lg font-mono tnum">₹{{ number_format($min) }}</span>
        </div>
      </div>
    </div>
  </a>
</article>

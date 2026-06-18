@extends('layouts.app')
@section('content')
<div class="max-w-6xl mx-auto px-4 py-7">
  <a href="{{ route('search', ['city'=>$hotel->city]) }}" class="inline-flex items-center gap-1.5 text-muted hover:text-iris text-sm mb-5 transition-colors">
    <x-icon name="arrow-right" class="w-4 h-4 rotate-180"/> Hotels in {{ $hotel->city }}
  </a>

  {{-- Header --}}
  <div class="flex flex-wrap items-start justify-between gap-4 mb-5">
    <div>
      <div class="flex flex-wrap items-center gap-2 mb-3">
        @if($hotel->listing_priority === 'top')
          <span class="badge badge-purple"><x-icon name="star" class="w-3.5 h-3.5"/>Top pick</span>
        @endif
        @if($hotel->couple_friendly)
          <span class="badge badge-success"><x-icon name="heart" class="w-3.5 h-3.5"/>Couple friendly</span>
        @endif
        @if($hotel->accepts_local_id)
          <span class="badge badge-gray"><x-icon name="shield" class="w-3.5 h-3.5"/>Local ID accepted</span>
        @endif
      </div>
      <h1 class="font-display font-bold text-3xl lg:text-4xl text-ink mb-2 tracking-tight">{{ $hotel->name }}</h1>
      <div class="flex flex-wrap items-center gap-4 text-sm text-muted">
        <span class="flex items-center gap-1.5"><x-icon name="pin" class="w-4 h-4"/>{{ $hotel->address }}</span>
        @if($hotel->avg_rating > 0)
          <span class="flex items-center gap-1 font-semibold text-ink">
            <x-icon name="star" class="w-4 h-4 text-amber"/>
            <span class="font-mono">{{ $hotel->avg_rating }}</span>
            <span class="text-muted font-normal">({{ $hotel->total_reviews }} reviews)</span>
          </span>
        @endif
      </div>
    </div>
    <div class="text-right">
      <div class="text-xs text-muted font-medium mb-1">Starting from</div>
      <div class="font-display font-bold text-3xl text-iris font-mono tnum">₹{{ number_format($minPrice) }}</div>
      <div class="text-muted text-xs mt-0.5">per hour / night</div>
    </div>
  </div>

  {{-- Gallery --}}
  <div class="rounded-3xl overflow-hidden mb-8 relative h-64 lg:h-[420px] group">
    @if($hotel->cover_image)
      <img src="{{ $hotel->cover_image }}" alt="{{ $hotel->name }} — {{ $hotel->city }}" class="w-full h-full object-cover group-hover:scale-[1.04] transition-transform duration-[800ms]" loading="eager"/>
    @else
      <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-ink to-ink-soft"><x-icon name="building" class="w-20 h-20 text-white/30"/></div>
    @endif
    <div class="absolute inset-0 bg-gradient-to-t from-ink/40 to-transparent pointer-events-none"></div>
    <button id="wishlist-btn" onclick="toggleWishlist({{ $hotel->id }})"
      class="absolute top-4 right-4 w-11 h-11 rounded-full glass shadow-soft flex items-center justify-center transition-all hover:scale-110"
      title="{{ $isWishlisted ? 'Remove from wishlist' : 'Save hotel' }}" aria-label="Save hotel">
      <x-icon name="heart" class="w-5 h-5 {{ $isWishlisted ? 'text-red-500 fill-red-500' : 'text-ink' }}"/>
    </button>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-7">
    {{-- LEFT --}}
    <div class="lg:col-span-2 space-y-5">
      <div class="card p-6">
        <h2 class="font-display font-semibold text-xl text-ink mb-3">About this property</h2>
        <p class="text-muted text-sm leading-relaxed">{{ $hotel->description ?? 'A comfortable hotel offering hourly and overnight stays. Verified by MyRoom.' }}</p>
      </div>

      @if(!empty($hotel->amenities))
        <div class="card p-6">
          <h2 class="font-display font-semibold text-xl text-ink mb-4">Amenities</h2>
          @php
            $amenityIcons = ['AC'=>'snowflake','WiFi'=>'wifi','TV'=>'tv','Hot Water'=>'coffee','Parking'=>'car','Restaurant'=>'coffee','Bar'=>'coffee','Gym'=>'trending','Spa'=>'sparkle','Pool'=>'star','Laundry'=>'check','Room Service'=>'bell','Conference Room'=>'building'];
          @endphp
          <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
            @foreach($hotel->amenities as $amenity)
              <div class="flex items-center gap-2.5 bg-paper rounded-xl px-3 py-2.5 text-sm font-medium text-ink/80">
                <x-icon name="{{ $amenityIcons[$amenity] ?? 'check' }}" class="w-4 h-4 text-iris"/>
                <span>{{ $amenity }}</span>
              </div>
            @endforeach
          </div>
        </div>
      @endif

      @if($hotel->offers->isNotEmpty())
        <div class="bg-amber-soft border border-amber/20 rounded-3xl p-5">
          <h2 class="font-display font-semibold text-ink mb-3 flex items-center gap-2"><x-icon name="gift" class="w-5 h-5 text-amber-deep"/>Offers available</h2>
          <div class="flex flex-wrap gap-3">
            @foreach($hotel->offers as $offer)
              <div class="bg-white rounded-xl px-4 py-3 border border-amber/15 shadow-soft">
                <div class="font-display font-bold text-amber-deep text-lg font-mono tnum">
                  {{ $offer->type === 'percentage' ? $offer->discount.'% off' : '₹'.$offer->discount.' off' }}
                </div>
                <div class="font-mono font-semibold text-ink text-xs tracking-widest mt-0.5">{{ $offer->code }}</div>
                <div class="text-muted text-xs mt-1">{{ $offer->title }}</div>
              </div>
            @endforeach
          </div>
        </div>
      @endif

      @if($hotel->reviews->isNotEmpty())
        <div class="card p-6">
          <h2 class="font-display font-semibold text-xl text-ink mb-4">Guest reviews</h2>
          <div class="space-y-4">
            @foreach($hotel->reviews->take(5) as $r)
              <div class="pb-4 border-b border-line last:border-0">
                <div class="flex items-center justify-between mb-2">
                  <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 bg-ink rounded-full flex items-center justify-center font-display font-semibold text-white text-sm">
                      {{ strtoupper(substr($r->customer?->name ?? 'G', 0, 1)) }}
                    </div>
                    <div>
                      <div class="font-semibold text-ink text-sm">{{ $r->customer?->name ?? 'Guest' }}</div>
                      <div class="text-muted text-xs">{{ $r->created_at->diffForHumans() }}</div>
                    </div>
                  </div>
                  <div class="flex gap-0.5">@for($s=0;$s<$r->rating;$s++)<x-icon name="star" class="w-3.5 h-3.5 text-amber"/>@endfor</div>
                </div>
                <p class="text-muted text-sm leading-relaxed">{{ $r->comment ?? 'Great experience!' }}</p>
                @if($r->hotel_reply)
                  <div class="mt-2 bg-paper rounded-xl p-3 text-xs text-ink/70"><strong class="text-ink">Hotel:</strong> {{ $r->hotel_reply }}</div>
                @endif
              </div>
            @endforeach
          </div>
        </div>
      @endif

      @if($related->isNotEmpty())
        <div>
          <h2 class="font-display font-semibold text-xl text-ink mb-4">Similar hotels in {{ $hotel->city }}</h2>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @foreach($related as $i => $r)
              <x-property-card :hotel="$r" :rank="$i"/>
            @endforeach
          </div>
        </div>
      @endif
    </div>

    {{-- RIGHT: booking widget --}}
    <div>
      <div class="card p-6 sticky top-20">
        <h2 class="font-display font-semibold text-xl text-ink mb-5">Choose your room</h2>

        <div class="space-y-3">
          @forelse($hotel->rooms as $room)
            <a href="{{ route('booking.create', [$hotel, $room]) }}"
              class="block border-[1.5px] border-line hover:border-iris rounded-2xl p-4 transition-all group hover:bg-brand-50">
              <div class="flex justify-between items-start mb-2">
                <div>
                  <div class="font-semibold text-ink text-sm group-hover:text-iris transition-colors">{{ $room->name }}</div>
                  <div class="text-xs text-muted capitalize mt-0.5 flex items-center gap-1">
                    <x-icon name="user" class="w-3 h-3"/>
                    {{ $room->stay_type === 'both' ? 'Hourly & overnight' : ucfirst($room->stay_type) }} · {{ $room->capacity }} guests
                  </div>
                </div>
                <x-icon name="arrow-up-right" class="w-4 h-4 text-muted group-hover:text-iris transition-colors"/>
              </div>

              @php
                $priceTiers = [];
                if ($room->price_3hr)  $priceTiers['3h']  = $room->price_3hr;
                if ($room->price_6hr)  $priceTiers['6h']  = $room->price_6hr;
                if ($room->price_12hr) $priceTiers['12h'] = $room->price_12hr;
              @endphp

              @if(!empty($priceTiers))
                <div class="flex gap-1.5 flex-wrap mt-2">
                  @foreach($priceTiers as $tierLabel => $tierPrice)
                    <span class="inline-flex items-center gap-1 text-[11px] bg-brand-50 text-iris-deep font-semibold px-2 py-1 rounded-lg">
                      <span class="font-mono">{{ $tierLabel }}</span><span class="w-px h-3 bg-brand-200"></span><span class="font-mono tnum">₹{{ number_format($tierPrice) }}</span>
                    </span>
                  @endforeach
                </div>
              @elseif($room->hourly_price || $room->overnight_price)
                <div class="flex gap-2 mt-2">
                  @if($room->hourly_price)<span class="text-xs bg-brand-50 text-iris-deep font-semibold px-2 py-1 rounded-lg font-mono tnum">₹{{ number_format($room->hourly_price) }}/hr</span>@endif
                  @if($room->overnight_price)<span class="text-xs bg-emerald-50 text-emerald-700 font-semibold px-2 py-1 rounded-lg font-mono tnum">₹{{ number_format($room->overnight_price) }}/night</span>@endif
                </div>
              @endif
            </a>
          @empty
            <p class="text-muted text-sm text-center py-4">No rooms available right now.</p>
          @endforelse
        </div>

        <div class="mt-5 bg-paper rounded-xl p-4 text-sm space-y-2.5">
          <div class="flex justify-between items-center">
            <span class="text-muted">You pay online</span>
            <span class="font-semibold text-iris">~10% only</span>
          </div>
          <div class="flex justify-between items-center">
            <span class="text-muted">Pay at hotel</span>
            <span class="font-semibold text-ink">Remaining balance</span>
          </div>
          <div class="border-t border-line pt-2.5">
            <p class="text-xs text-muted">No full advance. Book with just a small platform fee.</p>
          </div>
        </div>

        @if($hotel->rooms->isNotEmpty())
          <a href="{{ route('booking.create', [$hotel, $hotel->rooms->first()]) }}" class="btn btn-primary w-full btn-lg justify-center mt-4">
            Book now <x-icon name="arrow-right" class="w-4 h-4"/>
          </a>
        @endif
        <p class="text-xs text-center text-muted mt-3 flex items-center justify-center gap-1.5"><x-icon name="lock" class="w-3.5 h-3.5"/>Secure · No account needed · Instant SMS</p>
      </div>
    </div>
  </div>
</div>
@endsection
@push('scripts')
<script>
async function toggleWishlist(id) {
  const btn = document.getElementById('wishlist-btn');
  const res = await fetch(`/hotel/${id}/wishlist`, {
    method: 'POST',
    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Accept': 'application/json' }
  });
  if (res.status === 401) { window.location.href = '/login'; return; }
  const d = await res.json();
  if (d.success) {
    const icon = btn.querySelector('svg');
    if (d.wishlisted) { icon.classList.add('text-red-500','fill-red-500'); icon.classList.remove('text-ink'); }
    else { icon.classList.remove('text-red-500','fill-red-500'); icon.classList.add('text-ink'); }
  }
}
</script>
@endpush

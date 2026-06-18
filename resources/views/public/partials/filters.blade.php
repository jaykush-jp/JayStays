<div class="card p-5 lg:sticky lg:top-32" x-data="{ price: {{ request('max_price', 5000) }} }">
  <div class="flex items-center justify-between mb-5">
    <h2 class="font-display font-semibold text-ink flex items-center gap-2">
      <x-icon name="settings" class="w-4 h-4 text-iris"/> Filters
    </h2>
    <a href="{{ route('search', ['city'=>$city]) }}" class="text-xs text-iris font-semibold hover:underline">Clear all</a>
  </div>

  <form method="GET" action="{{ route('search') }}" id="filter-form">
    <input type="hidden" name="city" value="{{ $city }}"/>

    {{-- STAY TYPE --}}
    <div class="mb-6">
      <div class="text-xs font-semibold text-muted uppercase tracking-wider mb-3">Stay type</div>
      <div class="grid grid-cols-3 gap-2">
        @php $types = ['' => ['grid','Any'], 'hourly' => ['bolt','Hourly'], 'overnight' => ['moon','Night']]; @endphp
        @foreach($types as $val => $opt)
          <a href="{{ route('search', array_merge(request()->except('type','page'), ['type'=>$val])) }}"
            class="flex flex-col items-center gap-1.5 py-3 rounded-xl border-[1.5px] transition-all
              {{ $stayType == $val ? 'border-iris bg-brand-50 text-iris' : 'border-line text-muted hover:border-brand-200 hover:bg-paper' }}">
            <x-icon name="{{ $opt[0] }}" class="w-5 h-5"/>
            <span class="text-xs font-semibold">{{ $opt[1] }}</span>
          </a>
        @endforeach
      </div>
    </div>

    {{-- PRICE RANGE --}}
    <div class="mb-6">
      <div class="flex items-center justify-between mb-3">
        <div class="text-xs font-semibold text-muted uppercase tracking-wider">Max price</div>
        <div class="text-sm font-bold text-iris font-mono tnum">₹<span x-text="price"></span></div>
      </div>
      <input type="range" name="max_price" min="100" max="5000" step="100" x-model="price"
        @change="document.getElementById('filter-form').submit()"
        class="w-full h-2 rounded-full appearance-none cursor-pointer"
        style="background:linear-gradient(to right,#5B5BD6 0%,#5B5BD6 var(--pct,50%),#ECE9F2 var(--pct,50%),#ECE9F2 100%)"
        x-init="$el.style.setProperty('--pct', ((price-100)/(5000-100)*100)+'%')"
        @input="$el.style.setProperty('--pct', ((price-100)/(5000-100)*100)+'%')"/>
      <div class="flex justify-between text-xs text-muted mt-1.5 font-mono">
        <span>₹100</span><span>₹5,000+</span>
      </div>
    </div>

    {{-- RATING --}}
    <div class="mb-6">
      <div class="text-xs font-semibold text-muted uppercase tracking-wider mb-3">Guest rating</div>
      <div class="grid grid-cols-4 gap-1.5">
        @foreach([0=>'Any', 3=>'3★+', 4=>'4★+', 4.5=>'4.5★'] as $val => $lbl)
          <a href="{{ route('search', array_merge(request()->except('min_rating','page'), ['min_rating'=>$val])) }}"
            class="py-2 rounded-xl text-xs font-semibold text-center border-[1.5px] transition-all
              {{ (request('min_rating',0)) == $val ? 'border-amber bg-amber-soft text-amber-deep' : 'border-line text-muted hover:border-amber/50' }}">
            {{ $lbl }}
          </a>
        @endforeach
      </div>
    </div>

    {{-- AMENITIES --}}
    <div class="mb-6">
      <div class="text-xs font-semibold text-muted uppercase tracking-wider mb-3">Popular amenities</div>
      <div class="space-y-1">
        @php $amenities = [['wifi','Free WiFi','wifi'],['car','Parking','parking'],['snowflake','AC rooms','ac'],['coffee','Restaurant','restaurant'],['star','Swimming pool','pool']]; @endphp
        @foreach($amenities as [$icon, $label, $key])
          <label class="flex items-center gap-2.5 text-sm text-ink/80 cursor-pointer group py-1.5 px-1 rounded-lg hover:bg-paper transition-colors">
            <input type="checkbox" class="w-4 h-4 rounded cursor-pointer"
              onchange="window.location='{{ route('search', array_merge(request()->all(), ['amenity'=>$key])) }}'"
              @checked(request('amenity')===$key)/>
            <x-icon name="{{ $icon }}" class="w-4 h-4 text-muted"/>
            <span class="group-hover:text-ink transition-colors">{{ $label }}</span>
          </label>
        @endforeach
      </div>
    </div>

    {{-- TAGS --}}
    <div class="mb-2">
      <div class="text-xs font-semibold text-muted uppercase tracking-wider mb-3">Property tags</div>
      <div class="space-y-1">
        @php $tags = [['heart','Couple friendly','couple_friendly'],['shield','Accepts local ID','local_id'],['user','Family friendly','family'],['bolt','Instant booking','instant']]; @endphp
        @foreach($tags as [$icon, $label, $key])
          <label class="flex items-center gap-2.5 text-sm text-ink/80 cursor-pointer group py-1.5 px-1 rounded-lg hover:bg-paper transition-colors">
            <input type="checkbox" class="w-4 h-4 rounded cursor-pointer"
              onchange="window.location='{{ route('search', array_merge(request()->all(), ['tags'=>$key])) }}'"
              @checked(request('tags')===$key)/>
            <x-icon name="{{ $icon }}" class="w-4 h-4 text-muted"/>
            <span class="group-hover:text-ink transition-colors">{{ $label }}</span>
          </label>
        @endforeach
      </div>
    </div>
  </form>

  <div class="mt-6 bg-ink rounded-xl p-4">
    <div class="text-sm font-semibold text-white mb-1 flex items-center gap-1.5"><x-icon name="info" class="w-4 h-4 text-brand-300"/>Pay less upfront</div>
    <p class="text-xs text-white/55 leading-relaxed">Pay only ~10% online to confirm. Settle the rest at the hotel.</p>
  </div>
</div>

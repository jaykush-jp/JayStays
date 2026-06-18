<footer class="relative bg-ink text-white pt-16 pb-8 mt-20 overflow-hidden">
  {{-- ambient time-of-day glow --}}
  <div class="absolute inset-0 ink-grid opacity-40"></div>
  <div class="absolute -top-24 right-1/4 w-72 h-72 rounded-full opacity-20 pointer-events-none animate-drift"
       style="background:radial-gradient(circle,#5B5BD6,transparent 70%)"></div>

  <div class="relative max-w-7xl mx-auto px-4">
    {{-- CTA strip --}}
    <div class="card bg-ink-soft/60 border-white/10 rounded-3xl p-8 sm:p-10 mb-14 flex flex-col md:flex-row items-center justify-between gap-6">
      <div>
        <h3 class="font-display font-bold text-2xl sm:text-3xl text-white mb-1.5">Need a room in the next hour?</h3>
        <p class="text-white/60 text-sm">Instant confirmation. Pay a small advance. Settle the rest at the hotel.</p>
      </div>
      <a href="{{ route('search') }}" class="btn btn-accent btn-lg shrink-0">Find rooms now <x-icon name="arrow-right" class="w-4 h-4"/></a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-10 mb-12">
      <div class="lg:col-span-2">
        <div class="flex items-center gap-2.5 mb-4">
          <span class="w-9 h-9 rounded-xl bg-white/10 flex items-center justify-center"><x-icon name="clock" class="w-5 h-5 text-white"/></span>
          <span class="font-display font-bold text-xl">My<span class="text-brand-300">Room</span></span>
        </div>
        <p class="text-white/55 text-sm leading-relaxed max-w-xs mb-5">India's hourly hotel booking platform. Book by the hour or overnight, pay advance online, settle the rest at the hotel.</p>
        <div class="flex gap-2 flex-wrap">
          <span class="badge bg-white/10 text-white/80"><x-icon name="bolt" class="w-3.5 h-3.5"/>Hourly stays</span>
          <span class="badge bg-white/10 text-white/80"><x-icon name="moon" class="w-3.5 h-3.5"/>Overnight</span>
          <span class="badge bg-white/10 text-white/80"><x-icon name="pin" class="w-3.5 h-3.5"/>50+ cities</span>
        </div>
      </div>
      <nav>
        <h3 class="text-xs font-bold uppercase tracking-widest text-white/40 mb-4">Product</h3>
        <ul class="space-y-2.5">
          @foreach([['Find Rooms','search'],['All Cities','cities'],['Track Booking','booking.track'],['How It Works','page.how-it-works'],['FAQ','page.faq']] as [$l,$r])
            <li><a href="{{ route($r) }}" class="text-sm text-white/55 hover:text-white transition-colors">{{ $l }}</a></li>
          @endforeach
        </ul>
      </nav>
      <nav>
        <h3 class="text-xs font-bold uppercase tracking-widest text-white/40 mb-4">Top Cities</h3>
        <ul class="space-y-2.5">
          @foreach(['Delhi','Mumbai','Noida','Bangalore','Gurgaon','Hyderabad'] as $city)
            <li><a href="{{ route('search.city', strtolower($city)) }}" class="text-sm text-white/55 hover:text-white transition-colors">{{ $city }}</a></li>
          @endforeach
        </ul>
      </nav>
      <nav>
        <h3 class="text-xs font-bold uppercase tracking-widest text-white/40 mb-4">Company</h3>
        <ul class="space-y-2.5">
          @foreach([['About','page.about'],['Contact','page.contact'],['Terms','page.terms'],['Privacy','page.privacy'],['List Hotel','hotel.register']] as [$l,$r])
            <li><a href="{{ route($r) }}" class="text-sm text-white/55 hover:text-white transition-colors">{{ $l }}</a></li>
          @endforeach
        </ul>
      </nav>
    </div>

    <div class="border-t border-white/10 pt-6 flex flex-col sm:flex-row items-center justify-between gap-3">
      <p class="text-sm text-white/40">© {{ date('Y') }} MyRoom. Made in India.</p>
      <div class="flex items-center gap-4 text-white/40">
        <a href="#" class="hover:text-white transition-colors" aria-label="X / Twitter"><svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M18.9 2H22l-7.3 8.3L23.3 22h-6.8l-5.3-7-6.1 7H2l7.8-8.9L1 2h7l4.8 6.4L18.9 2Zm-2.4 18h1.9L7.6 4H5.6l10.9 16Z"/></svg></a>
        <a href="#" class="hover:text-white transition-colors" aria-label="Instagram"><svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg></a>
        <a href="#" class="hover:text-white transition-colors" aria-label="LinkedIn"><svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M4.98 3.5A2.5 2.5 0 1 0 5 8.5a2.5 2.5 0 0 0 0-5ZM3 9h4v12H3V9Zm6 0h3.8v1.7h.05c.53-1 1.83-2.05 3.77-2.05C20.4 8.65 21 10.9 21 13.8V21h-4v-6.4c0-1.53-.03-3.5-2.13-3.5-2.13 0-2.46 1.66-2.46 3.38V21H9V9Z"/></svg></a>
      </div>
    </div>
  </div>
</footer>

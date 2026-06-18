<header role="banner" class="sticky top-0 z-50 bg-paper/85 backdrop-blur-xl border-b border-line" x-data="{open:false, scrolled:false}"
  @scroll.window="scrolled = window.scrollY > 8" :class="scrolled ? 'shadow-soft' : ''">
  <div class="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between">

    {{-- Logomark: a clock face — the time thesis --}}
    <a href="{{ route('home') }}" class="flex items-center gap-2.5 flex-shrink-0 group">
      <span class="relative w-9 h-9 rounded-xl bg-ink flex items-center justify-center overflow-hidden">
        <span class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-300"
              style="background:radial-gradient(circle at 30% 20%,#5B5BD6,transparent 70%)"></span>
        <x-icon name="clock" class="w-5 h-5 text-white relative"/>
      </span>
      <span class="font-display font-bold text-xl text-ink tracking-tight">My<span class="text-iris">Room</span></span>
    </a>

    <nav class="hidden lg:flex items-center gap-1">
      <a href="{{ route('home') }}"              class="nav-link {{ request()->routeIs('home') ? 'nav-active' : '' }}">Home</a>
      <a href="{{ route('search') }}"            class="nav-link {{ request()->routeIs('search') ? 'nav-active' : '' }}">Find Rooms</a>
      <a href="{{ route('cities') }}"            class="nav-link {{ request()->routeIs('cities') ? 'nav-active' : '' }}">Cities</a>
      <a href="{{ route('page.how-it-works') }}" class="nav-link">How It Works</a>
    </nav>

    <div class="flex items-center gap-2">
      @auth
        @php $unread = auth()->user()->unreadNotifications()->count(); @endphp
        <a href="{{ auth()->user()->isCustomer() ? route('customer.notifications') : '#' }}"
           class="relative p-2 rounded-xl text-muted hover:text-iris hover:bg-brand-50 transition-colors hidden sm:block" aria-label="Notifications">
          <x-icon name="bell" class="w-5 h-5"/>
          @if($unread > 0)<span class="notif-badge">{{ $unread > 9 ? '9+' : $unread }}</span>@endif
        </a>
        @if(auth()->user()->isAdmin())
          <a href="{{ route('admin.dashboard') }}" class="btn btn-ink btn-sm hidden sm:inline-flex">Admin Panel</a>
        @elseif(auth()->user()->isHotelOwner())
          <a href="{{ route('hotel.dashboard') }}" class="btn btn-ink btn-sm hidden sm:inline-flex">Hotel Panel</a>
        @else
          <a href="{{ route('customer.dashboard') }}" class="btn btn-white btn-sm hidden sm:inline-flex">My Bookings</a>
        @endif
        <form method="POST" action="{{ route('logout') }}" class="hidden sm:block">@csrf
          <button class="btn btn-ghost btn-sm" aria-label="Log out"><x-icon name="logout" class="w-4 h-4"/></button>
        </form>
      @else
        <a href="{{ route('booking.track') }}" class="text-sm text-muted hover:text-iris hidden sm:inline font-medium transition-colors px-2">Track booking</a>
        <a href="{{ route('login') }}"           class="btn btn-white btn-sm">Log in</a>
        <a href="{{ route('hotel.register') }}"  class="btn btn-primary btn-sm hidden sm:inline-flex">List your hotel</a>
      @endauth
      <button @click="open=!open" class="lg:hidden p-2 rounded-xl text-ink hover:bg-brand-50" aria-label="Menu">
        <x-icon name="menu" class="w-5 h-5" x-show="!open"/>
        <x-icon name="close" class="w-5 h-5" x-show="open"/>
      </button>
    </div>
  </div>

  <nav x-show="open" x-collapse class="lg:hidden border-t border-line bg-white">
    <div class="max-w-7xl mx-auto px-4 py-3 space-y-1">
      <a href="{{ route('home') }}"              class="mobile-nav-link"><x-icon name="bolt" class="w-4 h-4"/>Home</a>
      <a href="{{ route('search') }}"            class="mobile-nav-link"><x-icon name="search" class="w-4 h-4"/>Find Rooms</a>
      <a href="{{ route('cities') }}"            class="mobile-nav-link"><x-icon name="pin" class="w-4 h-4"/>All Cities</a>
      <a href="{{ route('page.how-it-works') }}" class="mobile-nav-link"><x-icon name="info" class="w-4 h-4"/>How It Works</a>
      <a href="{{ route('booking.track') }}"     class="mobile-nav-link"><x-icon name="doc" class="w-4 h-4"/>Track Booking</a>
      @auth
        @if(auth()->user()->isCustomer())<a href="{{ route('customer.dashboard') }}" class="mobile-nav-link"><x-icon name="grid" class="w-4 h-4"/>My Dashboard</a>
        @elseif(auth()->user()->isHotelOwner())<a href="{{ route('hotel.dashboard') }}" class="mobile-nav-link"><x-icon name="building" class="w-4 h-4"/>Hotel Panel</a>
        @else<a href="{{ route('admin.dashboard') }}" class="mobile-nav-link"><x-icon name="settings" class="w-4 h-4"/>Admin Panel</a>@endif
        <form method="POST" action="{{ route('logout') }}">@csrf<button class="mobile-nav-link w-full text-left text-red-500"><x-icon name="logout" class="w-4 h-4"/>Logout</button></form>
      @else
        <a href="{{ route('login') }}"          class="mobile-nav-link font-semibold text-iris"><x-icon name="user" class="w-4 h-4"/>Log in / Sign up</a>
        <a href="{{ route('hotel.register') }}" class="mobile-nav-link"><x-icon name="building" class="w-4 h-4"/>List Your Hotel</a>
      @endauth
    </div>
  </nav>
</header>

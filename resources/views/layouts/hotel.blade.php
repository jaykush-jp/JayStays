<!DOCTYPE html>
<html lang="en">
<head>
  <title>@yield('title','Hotel Panel') · MyRoom</title>
  @include('layouts.partials.portal-head')
</head>
<body class="bg-paper" x-data="{mobileNav:false}">
<div class="flex h-screen overflow-hidden">
  <aside class="w-64 bg-ink flex-col flex-shrink-0 hidden lg:flex">
    <div class="px-5 py-5 border-b border-white/10 flex items-center gap-3">
      <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center"><x-icon name="building" class="w-5 h-5 text-white"/></div>
      <div class="min-w-0">
        <div class="text-white font-semibold text-sm truncate max-w-[140px]">{{ auth()->user()->name }}</div>
        <div class="text-white/45 text-xs">Hotel partner</div>
      </div>
    </div>
    <div class="px-4 pt-4 pb-1 text-xs font-bold text-white/35 uppercase tracking-widest">Extranet</div>
    <nav class="flex-1 px-3 pb-4 space-y-1 overflow-y-auto">
      @php $nav = [['hotel.dashboard','grid','Dashboard'],['hotel.bookings','doc','Bookings'],['hotel.properties','building','Properties'],['hotel.reviews','star','Reviews'],['hotel.revenue','chart','Revenue']]; @endphp
      @foreach($nav as [$r,$ic,$l])
        <a href="{{ route($r) }}" class="side-link {{ request()->routeIs($r) ? 'active' : '' }}"><x-icon name="{{ $ic }}" class="w-[18px] h-[18px]"/>{{ $l }}</a>
      @endforeach
    </nav>
    <div class="px-4 py-4 border-t border-white/10">
      <form method="POST" action="{{ route('logout') }}">@csrf<button class="side-link w-full text-white/45 hover:text-red-300"><x-icon name="logout" class="w-[18px] h-[18px]"/>Logout</button></form>
    </div>
  </aside>

  <div class="flex-1 flex flex-col overflow-y-auto">
    <header class="bg-white/85 backdrop-blur-xl border-b border-line px-5 lg:px-6 py-4 flex items-center justify-between sticky top-0 z-10">
      <div class="flex items-center gap-3">
        <button @click="mobileNav=!mobileNav" class="lg:hidden p-1.5 rounded-lg text-ink hover:bg-brand-50"><x-icon name="menu" class="w-5 h-5"/></button>
        <div>
          <h1 class="font-display font-semibold text-ink text-lg">@yield('title')</h1>
          <p class="text-xs text-muted">@yield('subtitle','')</p>
        </div>
      </div>
      <a href="{{ route('home') }}" class="text-sm text-muted hover:text-iris transition-colors flex items-center gap-1.5"><x-icon name="globe" class="w-4 h-4"/>View site</a>
    </header>
    <div x-show="mobileNav" x-collapse class="lg:hidden bg-ink">
      <nav class="px-3 py-3 space-y-1">
        @foreach($nav as [$r,$ic,$l])
          <a href="{{ route($r) }}" class="side-link {{ request()->routeIs($r) ? 'active' : '' }}"><x-icon name="{{ $ic }}" class="w-[18px] h-[18px]"/>{{ $l }}</a>
        @endforeach
        <form method="POST" action="{{ route('logout') }}">@csrf<button class="side-link w-full text-white/45"><x-icon name="logout" class="w-[18px] h-[18px]"/>Logout</button></form>
      </nav>
    </div>
    @if(session('success'))<div class="mx-6 mt-4"><div class="alert-success flex items-center gap-2"><x-icon name="check-circle" class="w-5 h-5"/>{{ session('success') }}</div></div>@endif
    @if(session('error'))<div class="mx-6 mt-4"><div class="alert-error flex items-center gap-2"><x-icon name="info" class="w-5 h-5"/>{{ session('error') }}</div></div>@endif
    <main class="flex-1 p-5 lg:p-6">@yield('content')</main>
  </div>
</div>
@stack('scripts')
</body>
</html>

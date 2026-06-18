@extends('layouts.customer')
@section('title','My Wishlist')
@section('content')
<div class="flex items-center justify-between mb-5">
  <h2 class="font-display text-xl font-semibold text-ink">Saved hotels ({{ $wishlists->total() }})</h2>
</div>
@if($wishlists->isEmpty())
  <div class="card p-12 text-center"><div class="inline-flex w-16 h-16 rounded-2xl bg-brand-50 items-center justify-center mb-4"><x-icon name="heart" class="w-8 h-8 text-iris"/></div><h3 class="font-display text-xl font-semibold text-ink mb-2">No saved hotels</h3><p class="text-muted text-sm mb-6">Browse hotels and tap the heart to save them here.</p><a href="{{ route('search') }}" class="btn btn-primary inline-flex">Find hotels</a></div>
@else
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
    @foreach($wishlists as $w)
      @if($w->hotel)
        <div class="relative"><x-property-card :hotel="$w->hotel" :rank="0"/>
          <form method="POST" action="{{ route('customer.wishlist.toggle',$w->hotel) }}" class="absolute top-3 right-3 z-10">@csrf @method('DELETE')
            <button class="w-9 h-9 glass rounded-full shadow-soft flex items-center justify-center hover:scale-110 transition-transform" title="Remove"><x-icon name="heart" class="w-4 h-4 text-red-500 fill-red-500"/></button>
          </form>
        </div>
      @endif
    @endforeach
  </div>
  @if($wishlists->hasPages())<div class="mt-4 flex justify-center">{{ $wishlists->links() }}</div>@endif
@endif
@endsection

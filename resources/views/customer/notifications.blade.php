@extends('layouts.customer')
@section('title','Notifications')
@section('content')
<div class="flex items-center justify-between mb-5">
  <h2 class="font-display text-xl font-semibold text-ink">Notifications <span class="text-muted font-normal text-base">({{ auth()->user()->unreadNotifications()->count() }} unread)</span></h2>
  @if(auth()->user()->unreadNotifications()->count() > 0)
    <form method="POST" action="{{ route('customer.notifications.read-all') }}">@csrf<button class="btn btn-sm btn-white">Mark all read</button></form>
  @endif
</div>
<div class="space-y-2.5">
  @forelse($notifications as $n)
    <div class="card p-4 {{ $n->isUnread() ? 'border-brand-200 bg-brand-50/40' : '' }}">
      <div class="flex items-start gap-3">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 {{ $n->isUnread() ? 'bg-brand-100 text-iris' : 'bg-paper text-muted' }}">
          <x-icon name="{{ match($n->type) { 'booking_accepted','booking_confirmed'=>'check-circle','booking_rejected','booking_cancelled'=>'close','booking_created'=>'doc','welcome'=>'sparkle',default=>'bell' } }}" class="w-5 h-5"/>
        </div>
        <div class="flex-1 min-w-0">
          <div class="flex items-center justify-between gap-2 flex-wrap">
            <div class="font-semibold text-sm {{ $n->isUnread() ? 'text-iris' : 'text-ink' }}">{{ $n->title }}</div>
            <span class="text-xs text-muted flex-shrink-0">{{ $n->created_at->diffForHumans() }}</span>
          </div>
          <div class="text-muted text-sm mt-0.5">{{ $n->message }}</div>
          @if($n->isUnread())
            <form method="POST" action="{{ route('customer.notifications.read',$n->id) }}" class="mt-2">@csrf
              <button class="text-xs text-iris hover:underline">Mark as read</button>
            </form>
          @endif
        </div>
        @if($n->isUnread())<div class="w-2 h-2 bg-iris rounded-full flex-shrink-0 mt-2"></div>@endif
      </div>
    </div>
  @empty
    <div class="card p-12 text-center"><div class="inline-flex w-16 h-16 rounded-2xl bg-brand-50 items-center justify-center mb-4"><x-icon name="bell" class="w-8 h-8 text-iris"/></div><h3 class="font-display text-xl font-semibold text-ink mb-2">No notifications</h3><p class="text-muted text-sm">You'll see booking updates and alerts here.</p></div>
  @endforelse
</div>
@endsection

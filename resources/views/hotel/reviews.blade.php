@extends('layouts.hotel')
@section('title','Customer Reviews')
@section('content')
<div class="flex items-center justify-between mb-5">
  <h2 class="font-display text-xl font-semibold text-ink">Reviews ({{ $reviews->total() }})</h2>
</div>
<div class="space-y-4">
  @forelse($reviews as $r)
    <div class="card p-5">
      <div class="flex items-start justify-between gap-4 mb-3">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-full bg-ink flex items-center justify-center font-display font-semibold text-white">{{ strtoupper(substr($r->customer?->name??'G',0,1)) }}</div>
          <div><div class="font-semibold text-ink text-sm">{{ $r->customer?->name ?? 'Guest' }}</div><div class="text-muted text-xs">{{ $r->hotel?->name }} · {{ $r->created_at->format('d M Y') }}</div></div>
        </div>
        <div class="flex gap-0.5">@for($s=0;$s<$r->rating;$s++)<x-icon name="star" class="w-4 h-4 text-amber"/>@endfor</div>
      </div>
      <p class="text-ink/80 text-sm leading-relaxed mb-3">{{ $r->comment ?: 'No comment.' }}</p>
      @if($r->hotel_reply)
        <div class="bg-paper rounded-xl p-3 text-xs text-ink/70 mb-1"><strong class="text-ink">Your reply:</strong> {{ $r->hotel_reply }}<span class="text-muted ml-2">· {{ $r->hotel_replied_at?->format('d M Y') }}</span></div>
      @else
        <details class="mt-2">
          <summary class="text-iris text-xs font-semibold cursor-pointer hover:underline">Reply to this review</summary>
          <form method="POST" action="{{ route('hotel.reviews.reply',$r->id) }}" class="mt-2 flex gap-2">@csrf
            <textarea name="reply" rows="2" class="form-textarea flex-1 text-sm" placeholder="Thank the guest or address their concern..." required></textarea>
            <button class="btn btn-primary btn-sm flex-shrink-0 self-start">Post reply</button>
          </form>
        </details>
      @endif
    </div>
  @empty
    <div class="card p-12 text-center"><div class="inline-flex w-16 h-16 rounded-2xl bg-brand-50 items-center justify-center mb-3"><x-icon name="star" class="w-8 h-8 text-iris"/></div><h3 class="font-display text-xl font-semibold text-ink mb-2">No reviews yet</h3><p class="text-muted text-sm">Guest reviews will appear here after completed stays.</p></div>
  @endforelse
</div>
@if($reviews->hasPages())<div class="mt-4 flex justify-center">{{ $reviews->links() }}</div>@endif
@endsection

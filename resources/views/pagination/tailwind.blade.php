@if($paginator->hasPages())
<nav role="navigation" aria-label="Pagination" class="flex flex-col sm:flex-row items-center justify-between gap-3">
  <p class="text-sm text-muted">Showing <strong class="text-ink font-mono">{{ $paginator->firstItem() }}–{{ $paginator->lastItem() }}</strong> of <strong class="text-ink font-mono">{{ $paginator->total() }}</strong></p>
  <div class="flex items-center gap-1.5">
    @if($paginator->onFirstPage())
      <span class="w-9 h-9 flex items-center justify-center rounded-xl text-line bg-paper text-sm cursor-not-allowed">‹</span>
    @else
      <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="w-9 h-9 flex items-center justify-center rounded-xl border border-line text-ink/70 hover:border-iris hover:text-iris transition-all text-sm">‹</a>
    @endif
    @foreach($elements as $element)
      @if(is_string($element))<span class="w-9 h-9 flex items-center justify-center text-muted text-sm">…</span>@endif
      @if(is_array($element))
        @foreach($element as $page => $url)
          @if($page == $paginator->currentPage())
            <span class="w-9 h-9 flex items-center justify-center rounded-xl bg-iris text-white font-semibold text-sm font-mono shadow-soft">{{ $page }}</span>
          @else
            <a href="{{ $url }}" class="w-9 h-9 flex items-center justify-center rounded-xl border border-line text-ink/70 hover:border-iris hover:text-iris transition-all text-sm font-medium font-mono">{{ $page }}</a>
          @endif
        @endforeach
      @endif
    @endforeach
    @if($paginator->hasMorePages())
      <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="w-9 h-9 flex items-center justify-center rounded-xl border border-line text-ink/70 hover:border-iris hover:text-iris transition-all text-sm">›</a>
    @else
      <span class="w-9 h-9 flex items-center justify-center rounded-xl text-line bg-paper text-sm cursor-not-allowed">›</span>
    @endif
  </div>
</nav>
@endif

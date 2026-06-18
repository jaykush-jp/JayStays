@extends('layouts.hotel')
@section('title','Revenue & Earnings')
@section('content')
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-7">
  @foreach([['Total revenue','₹'.number_format($revenue['total']),'wallet'],['This month','₹'.number_format($revenue['month']),'calendar'],['This week','₹'.number_format($revenue['week']),'chart']] as [$l,$v,$ic])
    <div class="card p-5"><div class="w-10 h-10 rounded-xl bg-brand-50 text-iris flex items-center justify-center mb-3"><x-icon name="{{ $ic }}" class="w-5 h-5"/></div><div class="font-display font-bold text-2xl text-ink font-mono tnum">{{ $v }}</div><div class="text-xs font-medium text-muted uppercase tracking-wide mt-1">{{ $l }}</div></div>
  @endforeach
</div>
<div class="card p-6">
  <h2 class="font-display font-semibold text-ink mb-5">Revenue — last 30 days</h2>
  @if($chart->isEmpty())
    <div class="text-center py-8 text-muted">No completed bookings yet.</div>
  @else
    @php $maxRev = $chart->max('revenue') ?: 1; @endphp
    <div class="space-y-2.5">
      @foreach($chart as $day)
        <div class="flex items-center gap-3 text-sm">
          <div class="w-14 text-right text-muted text-xs flex-shrink-0 font-mono">{{ \Carbon\Carbon::parse($day->date)->format('d M') }}</div>
          <div class="flex-1 bg-paper rounded-full h-7 relative overflow-hidden">
            <div class="absolute left-0 top-0 h-full rounded-full flex items-center pl-3 bg-iris transition-all" style="width:{{ max(8,round(($day->revenue/$maxRev)*100)) }}%">
              @if($day->revenue>0)<span class="text-white text-xs font-bold whitespace-nowrap font-mono">₹{{ number_format($day->revenue) }}</span>@endif
            </div>
          </div>
          <div class="w-8 text-muted text-xs text-right flex-shrink-0 font-mono">{{ $day->cnt }}</div>
        </div>
      @endforeach
    </div>
  @endif
</div>
@endsection

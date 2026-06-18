@extends('layouts.admin')
@section('title','Dashboard Overview')
@section('content')

<div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-7">
  @foreach([
    ['Active hotels',$stats['active_hotels'],'building'],
    ['Pending approval',$stats['pending_hotels'],'clock'],
    ['All bookings',$stats['total_bookings'],'doc'],
    ['Confirmed',$stats['confirmed'],'check-circle'],
    ['Commission','₹'.number_format($stats['commission']),'wallet'],
  ] as [$l,$v,$ic])
    <div class="card p-5">
      <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-3 bg-brand-50 text-iris"><x-icon name="{{ $ic }}" class="w-5 h-5"/></div>
      <div class="text-2xl font-bold text-ink font-mono tnum">{{ $v }}</div>
      <div class="text-xs font-medium text-muted uppercase tracking-wide mt-1">{{ $l }}</div>
    </div>
  @endforeach
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-7">
  <div class="card p-6 lg:col-span-2">
    <div class="flex items-center justify-between mb-5">
      <h2 class="font-display font-semibold text-ink">Revenue — last 7 days</h2>
    </div>
    @if($revenueChart->isEmpty())
      <div class="text-center py-8 text-muted text-sm">No completed bookings yet.</div>
    @else
      @php $maxRev = $revenueChart->max('revenue') ?: 1; @endphp
      <div class="space-y-3">
        @foreach($revenueChart as $day)
          <div class="flex items-center gap-3 text-sm">
            <div class="w-14 text-right text-muted text-xs flex-shrink-0 font-mono">{{ \Carbon\Carbon::parse($day->date)->format('d M') }}</div>
            <div class="flex-1 bg-paper rounded-full h-8 relative overflow-hidden">
              <div class="absolute left-0 top-0 h-full rounded-full flex items-center pl-3 bg-iris transition-all" style="width:{{ max(8,round(($day->revenue/$maxRev)*100)) }}%">
                @if($day->revenue>0)<span class="text-white text-xs font-bold whitespace-nowrap font-mono">₹{{ number_format($day->revenue) }}</span>@endif
              </div>
            </div>
            <div class="w-6 text-muted text-xs text-right flex-shrink-0 font-mono">{{ $day->cnt }}</div>
          </div>
        @endforeach
      </div>
    @endif
  </div>
  <div class="card p-6">
    <h2 class="font-display font-semibold text-ink mb-4">Quick actions</h2>
    <div class="space-y-2">
      @foreach([
        [route('admin.hotels').'?status=pending','clock','Pending hotels'],
        [route('admin.bookings').'?status=pending','doc','Pending bookings'],
        [route('admin.offers'),'gift','Manage offers'],
        [route('admin.users'),'user','Manage users'],
        [route('admin.settings'),'settings','Settings'],
        [route('admin.reports'),'chart','Reports'],
      ] as [$url,$ic,$l])
        <a href="{{ $url }}" class="flex items-center gap-3 p-3 rounded-xl transition-all hover:bg-brand-50 group">
          <span class="w-9 h-9 rounded-lg bg-brand-50 text-iris flex items-center justify-center flex-shrink-0 group-hover:bg-white"><x-icon name="{{ $ic }}" class="w-[18px] h-[18px]"/></span>
          <span class="text-sm font-medium flex-1 text-ink">{{ $l }}</span>
          <x-icon name="arrow-right" class="w-4 h-4 text-muted"/>
        </a>
      @endforeach
    </div>
  </div>
</div>

@if($pendingHotels->isNotEmpty())
<div class="card overflow-hidden mb-6">
  <div class="flex items-center justify-between px-6 py-4 border-b border-amber/20 bg-amber-soft">
    <h2 class="font-semibold text-amber-deep flex items-center gap-2"><x-icon name="clock" class="w-5 h-5"/>Hotels awaiting approval ({{ $pendingHotels->count() }})</h2>
    <a href="{{ route('admin.hotels','?status=pending') }}" class="text-iris text-sm font-semibold hover:underline">View all</a>
  </div>
  <div class="overflow-x-auto">
    <table class="data-table">
      <thead><tr><th>Hotel name</th><th>Owner</th><th>City</th><th>Submitted</th><th>Actions</th></tr></thead>
      <tbody>
        @foreach($pendingHotels as $h)
          <tr>
            <td class="font-semibold text-ink">{{ $h->name }}</td>
            <td class="text-muted text-xs">{{ $h->owner?->name }}<br>{{ $h->owner?->email }}</td>
            <td>{{ $h->city }}</td>
            <td class="text-muted text-xs">{{ $h->created_at->diffForHumans() }}</td>
            <td class="flex gap-2 items-center">
              <form method="POST" action="{{ route('admin.hotels.approve',$h->id) }}">@csrf
                <button class="btn btn-sm bg-emerald-600 text-white hover:bg-emerald-700"><x-icon name="check" class="w-3.5 h-3.5"/>Approve</button>
              </form>
              <button onclick="document.getElementById('reject-{{ $h->id }}').classList.toggle('hidden')" class="btn btn-sm border border-red-200 text-red-600 hover:bg-red-50"><x-icon name="close" class="w-3.5 h-3.5"/>Reject</button>
            </td>
          </tr>
          <tr id="reject-{{ $h->id }}" class="hidden bg-red-50">
            <td colspan="5" class="p-3">
              <form method="POST" action="{{ route('admin.hotels.reject',$h->id) }}" class="flex gap-2">@csrf
                <input type="text" name="reason" placeholder="Reason for rejection..." class="form-input flex-1 text-sm py-2" required/>
                <button class="btn btn-sm bg-red-600 text-white">Confirm reject</button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endif

<div class="card overflow-hidden">
  <div class="flex items-center justify-between px-6 py-4 border-b border-line">
    <h2 class="font-display font-semibold text-ink">Recent bookings</h2>
    <a href="{{ route('admin.bookings') }}" class="text-iris text-sm font-semibold hover:underline">View all</a>
  </div>
  <div class="overflow-x-auto">
    <table class="data-table">
      <thead><tr><th>Ref</th><th>Guest</th><th>Hotel</th><th>Check-in</th><th>Advance paid</th><th>Balance</th><th>Status</th></tr></thead>
      <tbody>
        @forelse($recentBookings as $b)
          <tr>
            <td class="font-mono font-semibold text-iris text-xs">{{ $b->booking_ref }}</td>
            <td><div class="font-semibold text-ink text-sm">{{ $b->guest_name }}</div><div class="text-muted text-xs font-mono">{{ $b->guest_phone }}</div></td>
            <td class="text-ink/70 text-xs max-w-[120px] truncate">{{ $b->hotel?->name }}</td>
            <td class="text-muted text-xs whitespace-nowrap">{{ $b->checkin_at?->format('d M, h:i A') }}</td>
            <td class="font-bold text-emerald-600 font-mono">₹{{ number_format($b->advance_amount) }}</td>
            <td class="font-bold text-iris font-mono">₹{{ number_format($b->balance_amount) }}</td>
            <td><span class="status-{{ $b->status }}">{{ $b->status_label }}</span></td>
          </tr>
        @empty
          <tr><td colspan="7" class="text-center py-8 text-muted">No bookings yet.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection

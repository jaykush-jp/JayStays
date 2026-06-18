@extends('layouts.hotel')
@section('title','Booking Management')
@section('content')
<div class="space-y-5">
  <div class="flex items-center justify-between flex-wrap gap-3">
    <div>
      <h2 class="font-display text-xl font-semibold text-ink">Bookings</h2>
      <p class="text-sm text-muted">{{ $bookings->total() }} total · accept or reject incoming requests</p>
    </div>
    <form method="GET" action="{{ route('hotel.bookings') }}" class="flex gap-2 flex-wrap">
      <select name="status" onchange="this.form.submit()" class="form-select w-auto text-sm py-2">
        <option value="">All status</option>
        @foreach(['pending'=>'Pending','confirmed'=>'Confirmed','checked_in'=>'Checked in','completed'=>'Completed','rejected'=>'Rejected','cancelled'=>'Cancelled'] as $v=>$l)
          <option value="{{ $v }}" @selected(request('status')===$v)>{{ $l }}</option>
        @endforeach
      </select>
      <input type="date" name="date" value="{{ request('date') }}" onchange="this.form.submit()" class="form-input w-auto text-sm py-2 font-mono"/>
    </form>
  </div>

  @php $pendingCount = $bookings->where('status','pending')->count(); @endphp
  @if($pendingCount > 0)
    <div class="bg-amber-soft border border-amber/20 rounded-2xl p-4 flex items-center gap-3">
      <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-amber-deep flex-shrink-0"><x-icon name="clock" class="w-5 h-5"/></div>
      <div>
        <div class="font-semibold text-amber-deep">{{ $pendingCount }} booking{{ $pendingCount>1?'s':'' }} awaiting your response</div>
        <div class="text-amber-deep/80 text-sm">Guests are waiting. Accept or reject each booking below. Unanswered bookings may be auto-cancelled.</div>
      </div>
    </div>
  @endif

  <div class="space-y-4">
    @forelse($bookings as $b)
      <div class="card p-0 overflow-hidden {{ $b->isPending() ? 'border-amber/40 ring-1 ring-amber/15' : '' }}">
        <div class="p-5">
          <div class="flex items-start justify-between flex-wrap gap-4 mb-4">
            <div>
              <div class="flex items-center gap-2.5 mb-1 flex-wrap">
                <span class="font-mono font-semibold text-iris text-sm">{{ $b->booking_ref }}</span>
                <span class="status-{{ $b->status }}">{{ $b->status_label }}</span>
                @if($b->isPending())
                  <span class="text-xs text-amber-deep font-semibold bg-amber-soft px-2 py-0.5 rounded-full flex items-center gap-1"><x-icon name="clock" class="w-3 h-3"/>Waiting {{ $b->created_at->diffForHumans() }}</span>
                @endif
              </div>
              <h3 class="font-semibold text-ink">{{ $b->guest_name }}</h3>
              <div class="flex items-center gap-3 text-sm text-muted mt-0.5">
                <span class="flex items-center gap-1"><x-icon name="phone" class="w-3.5 h-3.5"/>{{ $b->guest_phone }}</span>
                @if($b->guest_email)<span class="flex items-center gap-1"><x-icon name="mail" class="w-3.5 h-3.5"/>{{ $b->guest_email }}</span>@endif
              </div>
            </div>
            <div class="text-right">
              <div class="font-bold text-ink text-lg font-mono tnum">₹{{ number_format($b->room_rate) }}</div>
              <div class="text-xs text-emerald-600 font-semibold font-mono">Advance ₹{{ number_format($b->advance_amount) }}</div>
              <div class="text-xs text-amber-deep font-semibold font-mono">At hotel ₹{{ number_format($b->balance_amount) }}</div>
            </div>
          </div>

          <div class="grid grid-cols-2 md:grid-cols-4 gap-3 bg-paper rounded-xl p-3 mb-4">
            <div><div class="text-xs text-muted font-medium uppercase tracking-wide">Room</div><div class="font-semibold text-ink text-sm">{{ $b->room?->name }}</div></div>
            <div><div class="text-xs text-muted font-medium uppercase tracking-wide">Stay type</div><div class="font-semibold text-ink text-sm">{{ $b->stay_type === 'hourly' ? $b->hours.' hours' : 'Overnight' }}</div></div>
            <div><div class="text-xs text-muted font-medium uppercase tracking-wide">Check-in</div><div class="font-semibold text-ink text-sm">{{ $b->checkin_at?->format('d M Y, h:i A') }}</div></div>
            <div><div class="text-xs text-muted font-medium uppercase tracking-wide">Check-out</div><div class="font-semibold text-ink text-sm">{{ $b->checkout_at?->format('d M Y, h:i A') ?? '—' }}</div></div>
          </div>

          @if($b->special_requests)
            <div class="bg-brand-50 rounded-xl p-3 mb-4 text-sm text-iris-deep"><strong>Special request:</strong> {{ $b->special_requests }}</div>
          @endif

          <div class="flex flex-wrap gap-2">
            @if($b->isPending())
              <button onclick="document.getElementById('accept-{{ $b->id }}').classList.toggle('hidden')" class="btn btn-primary btn-sm"><x-icon name="check" class="w-3.5 h-3.5"/>Accept booking</button>
              <button onclick="document.getElementById('reject-{{ $b->id }}').classList.toggle('hidden')" class="btn btn-sm border border-red-200 text-red-600 hover:bg-red-50"><x-icon name="close" class="w-3.5 h-3.5"/>Reject</button>
            @elseif($b->status === 'confirmed')
              <form method="POST" action="{{ route('hotel.bookings.checkin', $b->id) }}">@csrf<button class="btn btn-primary btn-sm"><x-icon name="check" class="w-3.5 h-3.5"/>Mark checked in</button></form>
            @elseif($b->status === 'checked_in')
              <form method="POST" action="{{ route('hotel.bookings.complete', $b->id) }}">@csrf<button class="btn btn-sm bg-emerald-600 text-white hover:bg-emerald-700"><x-icon name="check-circle" class="w-3.5 h-3.5"/>Mark completed</button></form>
              <form method="POST" action="{{ route('hotel.bookings.noshow', $b->id) }}">@csrf<button class="btn btn-sm btn-white">No show</button></form>
            @endif
            <a href="{{ route('hotel.bookings.show', $b->id) }}" class="btn btn-sm btn-white">View details</a>
          </div>

          <div id="accept-{{ $b->id }}" class="hidden mt-4 bg-emerald-50 rounded-xl p-4 border border-emerald-200">
            <p class="text-sm font-semibold text-emerald-800 mb-2">Accepting booking for {{ $b->guest_name }}</p>
            <form method="POST" action="{{ route('hotel.bookings.accept', $b->id) }}" class="flex gap-2 flex-wrap">@csrf
              <input type="text" name="hotel_notes" placeholder="Optional note to guest..." class="form-input flex-1 text-sm py-2"/>
              <button type="submit" class="btn btn-sm bg-emerald-600 text-white hover:bg-emerald-700 flex-shrink-0">Confirm accept</button>
              <button type="button" onclick="document.getElementById('accept-{{ $b->id }}').classList.add('hidden')" class="btn btn-sm btn-white">Cancel</button>
            </form>
          </div>

          <div id="reject-{{ $b->id }}" class="hidden mt-4 bg-red-50 rounded-xl p-4 border border-red-200">
            <p class="text-sm font-semibold text-red-800 mb-2">Reason for rejection (required):</p>
            <form method="POST" action="{{ route('hotel.bookings.reject', $b->id) }}" class="flex gap-2 flex-wrap">@csrf
              <input type="text" name="reason" placeholder="Room unavailable / fully booked..." class="form-input flex-1 text-sm py-2" required/>
              <button type="submit" class="btn btn-sm bg-red-600 text-white hover:bg-red-700 flex-shrink-0">Confirm reject</button>
              <button type="button" onclick="document.getElementById('reject-{{ $b->id }}').classList.add('hidden')" class="btn btn-sm btn-white">Cancel</button>
            </form>
            <p class="text-xs text-red-600 mt-2 flex items-center gap-1"><x-icon name="info" class="w-3.5 h-3.5"/>The customer will be notified and the advance refunded automatically.</p>
          </div>
        </div>
      </div>
    @empty
      <div class="card p-12 text-center">
        <div class="inline-flex w-16 h-16 rounded-2xl bg-brand-50 items-center justify-center mb-3"><x-icon name="doc" class="w-8 h-8 text-iris"/></div>
        <h3 class="font-display text-xl font-semibold text-ink mb-2">No bookings found</h3>
        <p class="text-muted">Bookings from customers will appear here.</p>
      </div>
    @endforelse
  </div>

  @if($bookings->hasPages())
    <div class="flex justify-center">{{ $bookings->appends(request()->query())->links() }}</div>
  @endif
</div>
@endsection

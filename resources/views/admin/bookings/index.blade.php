@extends('layouts.admin')
@section('title','All Bookings')
@section('content')
<div class="flex flex-wrap items-center justify-between gap-3 mb-5">
  <h2 class="font-display text-xl font-semibold text-ink">Bookings ({{ $bookings->total() }})</h2>
  <form method="GET" class="flex gap-2 flex-wrap">
    <select name="status" onchange="this.form.submit()" class="form-select w-auto text-sm py-2">
      <option value="">All status</option>
      @foreach(['pending','confirmed','completed','rejected','cancelled','checked_in','no_show'] as $s)
        <option value="{{ $s }}" @selected(request('status')===$s)>{{ ucwords(str_replace('_',' ',$s)) }}</option>
      @endforeach
    </select>
    <input name="search" value="{{ request('search') }}" placeholder="Search name, phone, ref..." class="form-input w-48 text-sm py-2"/>
    <button class="btn btn-sm btn-ink">Search</button>
  </form>
</div>
<div class="card overflow-hidden">
  <div class="overflow-x-auto">
    <table class="data-table">
      <thead><tr><th>Booking ID</th><th>Guest</th><th>Hotel</th><th>Type</th><th>Check-in</th><th>Online paid</th><th>Balance</th><th>Status</th></tr></thead>
      <tbody>
        @forelse($bookings as $b)
          <tr>
            <td><span class="font-mono font-semibold text-iris text-xs">{{ $b->booking_ref }}</span></td>
            <td><div class="font-semibold text-ink text-sm">{{ $b->guest_name }}</div><div class="text-muted text-xs font-mono">{{ $b->guest_phone }}</div></td>
            <td class="text-ink/70 text-xs max-w-[120px] truncate">{{ $b->hotel?->name }}</td>
            <td>@if($b->stay_type==='hourly')<span class="badge badge-primary font-mono">{{ $b->hours }}h</span>@else<span class="badge badge-orange">Night</span>@endif</td>
            <td class="text-muted text-xs whitespace-nowrap">{{ $b->checkin_at?->format('d M, h:i A') }}</td>
            <td class="font-bold text-emerald-600 font-mono">₹{{ number_format($b->advance_amount) }}</td>
            <td class="font-bold text-iris font-mono">₹{{ number_format($b->balance_amount) }}</td>
            <td><span class="status-{{ $b->status }}">{{ $b->status_label }}</span></td>
          </tr>
        @empty
          <tr><td colspan="8" class="text-center py-10 text-muted">No bookings found.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
<div class="mt-4 flex justify-center">{{ $bookings->appends(request()->query())->links() }}</div>
@endsection

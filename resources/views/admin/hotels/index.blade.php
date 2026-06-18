@extends('layouts.admin')
@section('title','Hotel Partners')
@section('content')
<div class="flex flex-wrap items-center justify-between gap-3 mb-5">
  <h2 class="font-display text-xl font-semibold text-ink">Hotel partners</h2>
  <form method="GET" class="flex gap-2 flex-wrap">
    <select name="status" onchange="this.form.submit()" class="form-select w-auto text-sm py-2">
      <option value="">All status</option>
      @foreach(['pending'=>'Pending','active'=>'Active','rejected'=>'Rejected','inactive'=>'Inactive'] as $v=>$l)
        <option value="{{ $v }}" @selected(request('status')===$v)>{{ $l }}</option>
      @endforeach
    </select>
    <input name="search" value="{{ request('search') }}" placeholder="Search hotel or city..." class="form-input w-48 text-sm py-2"/>
    <button class="btn btn-sm btn-ink">Search</button>
  </form>
</div>
<div class="card overflow-hidden">
  <div class="overflow-x-auto">
    <table class="data-table">
      <thead><tr><th>Hotel</th><th>Owner</th><th>City</th><th>Commission</th><th>Rooms</th><th>Rating</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
        @forelse($hotels as $h)
          <tr>
            <td>
              <div class="font-semibold text-ink">{{ $h->name }}</div>
              <div class="text-muted text-xs">{{ $h->address }}</div>
            </td>
            <td><div class="text-sm text-ink/80">{{ $h->owner?->name }}</div><div class="text-muted text-xs">{{ $h->owner?->email }}</div></td>
            <td class="text-ink/70 text-sm">{{ $h->city }}</td>
            <td class="font-semibold text-sm font-mono">{{ $h->commission_percent ?? 10 }}%</td>
            <td class="text-ink/70 text-sm font-mono">{{ $h->rooms_count ?? 0 }}</td>
            <td><span class="inline-flex items-center gap-1"><x-icon name="star" class="w-3.5 h-3.5 text-amber"/><span class="font-mono">{{ $h->avg_rating }}</span><span class="text-muted text-xs">({{ $h->total_reviews }})</span></span></td>
            <td><span class="status-{{ $h->status }}">{{ ucfirst($h->status) }}</span></td>
            <td>
              <div class="flex gap-1.5 flex-wrap items-center">
                @if($h->status === 'pending')
                  <form method="POST" action="{{ route('admin.hotels.approve',$h->id) }}">@csrf<button class="btn btn-sm bg-emerald-600 text-white"><x-icon name="check" class="w-3.5 h-3.5"/></button></form>
                  <button onclick="document.getElementById('rj-{{ $h->id }}').classList.toggle('hidden')" class="btn btn-sm border border-red-200 text-red-600"><x-icon name="close" class="w-3.5 h-3.5"/></button>
                @else
                  <form method="POST" action="{{ route('admin.hotels.update',$h->id) }}">@csrf @method('PATCH')
                    <select name="status" onchange="this.form.submit()" class="text-xs border border-line rounded-lg px-2 py-1 bg-white">
                      @foreach(['active','inactive','rejected'] as $s)
                        <option value="{{ $s }}" @selected($h->status===$s)>{{ ucfirst($s) }}</option>
                      @endforeach
                    </select>
                  </form>
                @endif
                <form method="POST" action="{{ route('admin.hotels.update',$h->id) }}">@csrf @method('PATCH')
                  <label class="flex items-center gap-1 cursor-pointer text-xs text-muted">
                    <input type="checkbox" name="is_featured" value="1" @checked($h->is_featured) onchange="this.form.submit()"/>Featured
                  </label>
                </form>
              </div>
              <div id="rj-{{ $h->id }}" class="hidden mt-2">
                <form method="POST" action="{{ route('admin.hotels.reject',$h->id) }}" class="flex gap-1">@csrf
                  <input type="text" name="reason" placeholder="Reason..." class="form-input text-xs py-1 flex-1" required/>
                  <button class="btn btn-sm border border-red-200 text-red-600">Send</button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr><td colspan="8" class="text-center py-10 text-muted">No hotels found.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
<div class="mt-4 flex justify-center">{{ $hotels->appends(request()->query())->links() }}</div>
@endsection

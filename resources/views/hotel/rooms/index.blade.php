@extends('layouts.hotel')
@section('title', 'Rooms — '.$hotel->name)
@section('content')
<div class="flex items-center justify-between mb-5">
  <div>
    <a href="{{ route('hotel.properties') }}" class="text-muted hover:text-iris text-sm transition-colors inline-flex items-center gap-1.5"><x-icon name="arrow-right" class="w-4 h-4 rotate-180"/>Properties</a>
    <h2 class="font-display text-xl font-semibold text-ink mt-1">{{ $hotel->name }}</h2>
  </div>
</div>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
  <div>
    <div class="card p-5">
      <h3 class="font-display font-semibold text-ink mb-4">Add new room</h3>
      <form method="POST" action="{{ route('hotel.rooms.store',$hotel->id) }}" class="space-y-3">@csrf
        <div><label class="form-label">Room name *</label><input type="text" name="name" class="form-input" placeholder="Deluxe Room" required/></div>
        <div><label class="form-label">Stay type *</label>
          <select name="stay_type" class="form-select">
            <option value="both">Both</option><option value="hourly">Hourly only</option><option value="overnight">Overnight only</option>
          </select>
        </div>
        <div class="grid grid-cols-2 gap-2">
          <div><label class="form-label">3h price</label><input type="number" name="price_3hr" class="form-input font-mono" placeholder="599"/></div>
          <div><label class="form-label">6h price</label><input type="number" name="price_6hr" class="form-input font-mono" placeholder="999"/></div>
          <div><label class="form-label">12h price</label><input type="number" name="price_12hr" class="form-input font-mono" placeholder="1499"/></div>
          <div><label class="form-label">Night price</label><input type="number" name="overnight_price" class="form-input font-mono" placeholder="1999"/></div>
        </div>
        <div><label class="form-label">Hourly rate</label><input type="number" name="hourly_price" class="form-input font-mono" placeholder="299"/></div>
        <div class="grid grid-cols-2 gap-2">
          <div><label class="form-label">Min hours</label><input type="number" name="min_hours" value="2" class="form-input font-mono"/></div>
          <div><label class="form-label">Capacity</label><input type="number" name="capacity" value="2" class="form-input font-mono"/></div>
        </div>
        <button class="btn btn-primary w-full justify-center"><x-icon name="plus" class="w-4 h-4"/>Add room</button>
      </form>
    </div>
  </div>
  <div class="lg:col-span-2 space-y-3">
    @forelse($rooms as $room)
      <div class="card p-5">
        <div class="flex items-start justify-between gap-4">
          <div>
            <div class="font-semibold text-ink">{{ $room->name }}</div>
            <div class="text-muted text-xs mt-0.5">{{ ucfirst($room->stay_type) }} · {{ $room->capacity }} guests</div>
            @if($room->price_3hr||$room->price_6hr||$room->price_12hr)
              <div class="flex gap-2 mt-2 flex-wrap">
                @foreach(array_filter(['3h'=>$room->price_3hr,'6h'=>$room->price_6hr,'12h'=>$room->price_12hr]) as $l=>$p)
                  <span class="badge badge-primary font-mono">₹{{ number_format($p) }}/{{ $l }}</span>
                @endforeach
                @if($room->overnight_price)<span class="badge badge-success font-mono">₹{{ number_format($room->overnight_price) }}/night</span>@endif
              </div>
            @endif
          </div>
          <div class="flex gap-2 flex-shrink-0">
            <a href="{{ route('hotel.availability',$room->hotel_id, $room->id) }}" class="btn btn-sm btn-white"><x-icon name="calendar" class="w-3.5 h-3.5"/>Availability</a>
            <form method="POST" action="{{ route('hotel.rooms.destroy',[$hotel->id,$room->id]) }}">@csrf @method('DELETE')
              <button onclick="return confirm('Delete this room?')" class="btn btn-sm border border-red-200 text-red-500">Delete</button>
            </form>
          </div>
        </div>
      </div>
    @empty
      <div class="card p-10 text-center text-muted"><div class="inline-flex w-14 h-14 rounded-2xl bg-brand-50 items-center justify-center mb-3"><x-icon name="bed" class="w-7 h-7 text-iris"/></div><p>No rooms added yet. Use the form to add your first room.</p></div>
    @endforelse
  </div>
</div>
@endsection

@extends('layouts.hotel')
@section('title', $hotel ? 'Edit Property' : 'Add New Property')
@section('content')
<div class="max-w-3xl mx-auto">
  <a href="{{ route('hotel.properties') }}" class="inline-flex items-center gap-1.5 text-muted hover:text-iris text-sm mb-5 transition-colors"><x-icon name="arrow-right" class="w-4 h-4 rotate-180"/>Back to properties</a>
  <div class="card p-7">
    <h2 class="font-display text-xl font-semibold text-ink mb-6">{{ $hotel ? 'Edit property details' : 'Add new property' }}</h2>
    <form method="POST" action="{{ $hotel ? route('hotel.properties.update',$hotel->id) : route('hotel.properties.store') }}" class="space-y-5">
      @csrf @if($hotel) @method('PUT') @endif
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="sm:col-span-2"><label class="form-label">Hotel name *</label><input type="text" name="name" value="{{ old('name',$hotel?->name) }}" class="form-input" required placeholder="The Hourly Inn"/>@error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror</div>
        <div><label class="form-label">City *</label><input type="text" name="city" value="{{ old('city',$hotel?->city) }}" class="form-input" placeholder="Delhi, Mumbai..." required/>@error('city')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror</div>
        <div><label class="form-label">Area</label><input type="text" name="area" value="{{ old('area',$hotel?->area) }}" class="form-input" placeholder="Connaught Place"/></div>
        <div class="sm:col-span-2"><label class="form-label">Full address *</label><textarea name="address" rows="2" class="form-textarea" required>{{ old('address',$hotel?->address) }}</textarea></div>
        <div class="sm:col-span-2"><label class="form-label">Description</label><textarea name="description" rows="3" class="form-textarea">{{ old('description',$hotel?->description) }}</textarea></div>
        <div><label class="form-label">Star rating</label>
          <select name="star_rating" class="form-select">
            @foreach([1,2,3,4,5] as $s)<option value="{{ $s }}" @selected($hotel?->star_rating===$s)>{{ $s }} star{{ $s>1?'s':'' }}</option>@endforeach
          </select>
        </div>
        <div><label class="form-label">Cover image URL</label><input type="url" name="cover_image" value="{{ old('cover_image',$hotel?->cover_image) }}" class="form-input" placeholder="https://..."/></div>
        <div class="sm:col-span-2">
          <label class="form-label">Amenities</label>
          <div class="flex flex-wrap gap-2 mt-1">
            @foreach(['AC','WiFi','TV','Hot Water','Parking','Restaurant','Bar','Gym','Spa','Pool','Laundry','Room Service','Conference Room'] as $a)
              <label class="flex items-center gap-1.5 text-sm bg-paper border border-line rounded-xl px-3 py-2 cursor-pointer has-[:checked]:bg-brand-50 has-[:checked]:border-iris has-[:checked]:text-iris transition-colors">
                <input type="checkbox" name="amenities[]" value="{{ $a }}" @checked($hotel && in_array($a,$hotel->amenities??[]))/>
                {{ $a }}
              </label>
            @endforeach
          </div>
        </div>
        <div>
          <label class="form-label">Options</label>
          <div class="space-y-2 mt-1">
            <label class="flex items-center gap-2 text-sm cursor-pointer"><input type="checkbox" name="couple_friendly" value="1" @checked(!$hotel || $hotel->couple_friendly)/><x-icon name="heart" class="w-4 h-4 text-iris"/>Couple friendly</label>
            <label class="flex items-center gap-2 text-sm cursor-pointer"><input type="checkbox" name="accepts_local_id" value="1" @checked(!$hotel || $hotel->accepts_local_id)/><x-icon name="shield" class="w-4 h-4 text-iris"/>Accepts local ID</label>
          </div>
        </div>
      </div>
      @if($hotel && $hotel->status === 'pending')
        <div class="alert-warning flex items-center gap-2"><x-icon name="clock" class="w-5 h-5"/>Your property is under admin review. Changes will be visible after approval.</div>
      @endif
      <button type="submit" class="btn btn-primary btn-lg">{{ $hotel ? 'Save changes' : 'Submit for approval' }}</button>
    </form>
  </div>
</div>
@endsection

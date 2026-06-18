@extends('layouts.admin')
@section('title','Platform Settings')
@section('content')
<div class="max-w-3xl mx-auto">
  <form method="POST" action="{{ route('admin.settings.update') }}">@csrf
    @foreach($settings as $group => $items)
      <div class="card p-6 mb-5">
        <h2 class="font-display font-semibold text-ink mb-4 uppercase tracking-wide text-sm border-b border-line pb-3">{{ ucfirst($group) }}</h2>
        <div class="space-y-4">
          @foreach($items as $s)
            <div>
              <label class="form-label" for="s{{ $s->key }}">{{ $s->label ?? ucwords(str_replace('_',' ',$s->key)) }}</label>
              @if($s->type === 'boolean')
                <select id="s{{ $s->key }}" name="{{ $s->key }}" class="form-select">
                  <option value="1" @selected($s->value=='1')>Enabled</option>
                  <option value="0" @selected($s->value!='1')>Disabled</option>
                </select>
              @else
                <input type="{{ $s->type==='number'?'number':'text' }}" id="s{{ $s->key }}" name="{{ $s->key }}" value="{{ $s->value }}" class="form-input {{ $s->type==='number'?'font-mono':'' }}"/>
              @endif
            </div>
          @endforeach
        </div>
      </div>
    @endforeach
    <button class="btn btn-primary btn-lg">Save all settings</button>
  </form>
</div>
@endsection

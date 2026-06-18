@extends('layouts.admin')
@section('title','Reports & Analytics')
@section('content')
<div class="flex items-center justify-between mb-5 flex-wrap gap-3">
  <h2 class="font-display text-xl font-semibold text-ink">Reports</h2>
  <form method="GET" class="flex gap-2">
    <input type="date" name="from" value="{{ $from }}" class="form-input text-sm py-2 font-mono"/>
    <input type="date" name="to" value="{{ $to }}" class="form-input text-sm py-2 font-mono"/>
    <button class="btn btn-primary btn-sm">Generate</button>
  </form>
</div>
<div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-7">
  @foreach([['Total revenue','₹'.number_format($data['total_revenue']),'wallet'],['Commission','₹'.number_format($data['total_commission']),'trending'],['Total bookings',$data['total_bookings'],'doc'],['Completed',$data['completed'],'check-circle'],['Cancelled',$data['cancelled'],'close']] as [$l,$v,$ic])
  <div class="card p-5"><div class="w-10 h-10 rounded-xl bg-brand-50 text-iris flex items-center justify-center mb-3"><x-icon name="{{ $ic }}" class="w-5 h-5"/></div><div class="text-2xl font-bold text-ink font-mono tnum">{{ $v }}</div><div class="text-xs font-medium text-muted uppercase tracking-wide mt-1">{{ $l }}</div></div>
  @endforeach
</div>
@if($data['city_breakdown']->isNotEmpty())
<div class="card overflow-hidden">
  <div class="px-6 py-4 border-b border-line"><h2 class="font-display font-semibold text-ink">Revenue by city</h2></div>
  <table class="data-table">
    <thead><tr><th>City</th><th>Bookings</th><th>Revenue</th></tr></thead>
    <tbody>
      @foreach($data['city_breakdown'] as $row)
        <tr><td class="font-semibold text-ink">{{ $row->city }}</td><td class="text-muted font-mono">{{ $row->cnt }}</td><td class="font-bold text-iris font-mono">₹{{ number_format($row->rev) }}</td></tr>
      @endforeach
    </tbody>
  </table>
</div>
@endif
@endsection

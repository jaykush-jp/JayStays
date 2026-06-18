@extends('layouts.admin')
@section('title','Offers & Promos')
@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
  <div class="card p-5">
    <h3 class="font-display font-semibold text-ink mb-4">Create new offer</h3>
    <form method="POST" action="{{ route('admin.offers.store') }}" class="space-y-3">@csrf
      <div><label class="form-label">Title *</label><input type="text" name="title" class="form-input" required placeholder="Weekend Special"/></div>
      <div><label class="form-label">Promo code *</label><input type="text" name="code" class="form-input uppercase font-mono tracking-widest" required placeholder="WEEKEND20"/></div>
      <div class="grid grid-cols-2 gap-2">
        <div><label class="form-label">Type *</label><select name="type" class="form-select"><option value="percentage">% off</option><option value="fixed">₹ off</option></select></div>
        <div><label class="form-label">Discount *</label><input type="number" name="discount" class="form-input font-mono" required placeholder="20"/></div>
      </div>
      <div class="grid grid-cols-2 gap-2">
        <div><label class="form-label">Min amount</label><input type="number" name="min_amount" value="0" class="form-input font-mono"/></div>
        <div><label class="form-label">Max discount</label><input type="number" name="max_discount" class="form-input font-mono" placeholder="300"/></div>
      </div>
      <div><label class="form-label">Stay type</label><select name="stay_type" class="form-select"><option value="both">Both</option><option value="hourly">Hourly</option><option value="overnight">Overnight</option></select></div>
      <div class="grid grid-cols-2 gap-2">
        <div><label class="form-label">Valid from</label><input type="date" name="valid_from" class="form-input font-mono"/></div>
        <div><label class="form-label">Valid to</label><input type="date" name="valid_to" class="form-input font-mono"/></div>
      </div>
      <div><label class="form-label">Usage limit</label><input type="number" name="usage_limit" class="form-input font-mono" placeholder="Unlimited"/></div>
      <button class="btn btn-primary w-full justify-center"><x-icon name="plus" class="w-4 h-4"/>Create offer</button>
    </form>
  </div>
  <div class="lg:col-span-2 card overflow-hidden">
    <div class="overflow-x-auto">
      <table class="data-table">
        <thead><tr><th>Code</th><th>Title</th><th>Discount</th><th>Used</th><th>Valid</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
          @forelse($offers as $o)
            <tr>
              <td class="font-mono font-semibold text-iris text-xs">{{ $o->code }}</td>
              <td class="text-ink/80 text-sm">{{ $o->title }}</td>
              <td class="font-bold text-sm font-mono">{{ $o->type==='percentage'?$o->discount.'%':'₹'.$o->discount }}</td>
              <td class="text-muted text-sm font-mono">{{ $o->used_count }}{{ $o->usage_limit?' / '.$o->usage_limit:'' }}</td>
              <td class="text-muted text-xs">{{ $o->valid_to?->format('d M Y') ?? 'No expiry' }}</td>
              <td><span class="badge {{ $o->is_active?'badge-success':'badge-gray' }}">{{ $o->is_active?'Active':'Off' }}</span></td>
              <td class="flex gap-1.5 items-center">
                <form method="POST" action="{{ route('admin.offers.update',$o->id) }}">@csrf @method('PATCH')<input type="hidden" name="is_active" value="{{ $o->is_active?0:1 }}"/><button class="btn btn-sm btn-white text-xs">{{ $o->is_active?'Disable':'Enable' }}</button></form>
                <form method="POST" action="{{ route('admin.offers.destroy',$o->id) }}" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="btn btn-sm border border-red-200 text-red-500 text-xs">Del</button></form>
              </td>
            </tr>
          @empty
            <tr><td colspan="7" class="text-center py-8 text-muted">No offers yet.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="p-4">{{ $offers->links() }}</div>
  </div>
</div>
@endsection

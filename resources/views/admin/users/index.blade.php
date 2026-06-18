@extends('layouts.admin')
@section('title','User Management')
@section('content')
<div class="flex flex-wrap items-center justify-between gap-3 mb-5">
  <h2 class="font-display text-xl font-semibold text-ink">Users ({{ $users->total() }})</h2>
  <div class="flex gap-2 flex-wrap">
    <form method="GET" class="flex gap-2">
      <select name="role" onchange="this.form.submit()" class="form-select w-auto text-sm py-2"><option value="">All roles</option><option value="customer" @selected(request('role')==='customer')>Customers</option><option value="hotel_owner" @selected(request('role')==='hotel_owner')>Hotel owners</option></select>
      <input name="search" value="{{ request('search') }}" placeholder="Search..." class="form-input w-48 text-sm py-2"/>
      <button class="btn btn-sm btn-ink">Search</button>
    </form>
    <button onclick="document.getElementById('add-owner-form').classList.toggle('hidden')" class="btn btn-sm btn-primary"><x-icon name="plus" class="w-4 h-4"/>Add hotel owner</button>
  </div>
</div>
<div id="add-owner-form" class="hidden card p-5 mb-4">
  <h3 class="font-display font-semibold text-ink mb-3">Create hotel owner account</h3>
  <form method="POST" action="{{ route('admin.users.hotel-owner') }}" class="flex flex-wrap gap-3">@csrf
    <input type="text" name="name" placeholder="Name" class="form-input w-40 text-sm py-2" required/>
    <input type="email" name="email" placeholder="Email" class="form-input w-48 text-sm py-2" required/>
    <input type="tel" name="phone" placeholder="Phone (10 digits)" class="form-input w-40 text-sm py-2" maxlength="10"/>
    <input type="password" name="password" placeholder="Password" class="form-input w-36 text-sm py-2" required minlength="6"/>
    <button class="btn btn-primary btn-sm">Create account</button>
  </form>
</div>
<div class="card overflow-hidden">
  <div class="overflow-x-auto">
    <table class="data-table">
      <thead><tr><th>Name</th><th>Contact</th><th>Role</th><th>Joined</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
        @forelse($users as $u)
          <tr>
            <td><div class="font-semibold text-ink">{{ $u->name }}</div></td>
            <td><div class="text-ink/70 text-xs">{{ $u->email }}</div><div class="text-muted text-xs font-mono">{{ $u->phone }}</div></td>
            <td><span class="badge {{ $u->role==='hotel_owner'?'badge-purple':'badge-gray' }}">{{ ucwords(str_replace('_',' ',$u->role)) }}</span></td>
            <td class="text-muted text-xs">{{ $u->created_at->format('d M Y') }}</td>
            <td><span class="status-{{ $u->status }}">{{ ucfirst($u->status) }}</span></td>
            <td>
              <form method="POST" action="{{ route('admin.users.status',$u->id) }}">@csrf @method('PATCH')
                <select name="status" onchange="this.form.submit()" class="text-xs border border-line rounded-lg px-2 py-1 bg-white">
                  @foreach(['active','inactive','banned'] as $s)<option value="{{ $s }}" @selected($u->status===$s)>{{ ucfirst($s) }}</option>@endforeach
                </select>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="6" class="text-center py-10 text-muted">No users found.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
<div class="mt-4 flex justify-center">{{ $users->appends(request()->query())->links() }}</div>
@endsection

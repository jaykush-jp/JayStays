@extends('layouts.app')
@section('content')
<div class="min-h-[80vh] flex items-center justify-center px-4 py-12 bg-paper">
  <div class="card p-8 max-w-md w-full text-center">
    <div class="w-16 h-16 bg-brand-50 rounded-2xl flex items-center justify-center mx-auto mb-5"><x-icon name="wallet" class="w-8 h-8 text-iris"/></div>
    <h1 class="font-display text-2xl font-bold text-ink mb-2">Complete payment</h1>
    <p class="text-muted text-sm mb-5">Booking <span class="font-mono">#{{ $booking->booking_ref }}</span><br>Pay the online amount to confirm your room.</p>

    <div class="bg-paper rounded-2xl p-5 mb-6 text-left space-y-2.5 text-sm">
      @foreach(['Hotel'=>$booking->hotel->name,'Room'=>$booking->room->name,'Check-in'=>$booking->checkin_at->format('d M Y, h:i A'),'Guest'=>$booking->guest_name] as $l=>$v)
        <div class="flex justify-between"><span class="text-muted">{{ $l }}</span><span class="font-semibold text-ink text-right">{{ $v }}</span></div>
      @endforeach
      <div class="border-t border-line pt-2.5 flex justify-between font-bold text-base">
        <span class="text-iris">Pay now</span>
        <span class="text-iris font-mono tnum">₹{{ number_format($booking->advance_amount) }}</span>
      </div>
    </div>

    @if(!empty($orderData['demo']))
      <div class="alert-warning mb-5 text-left text-sm">
        <strong class="flex items-center gap-1.5"><x-icon name="info" class="w-4 h-4"/>Test mode</strong>
        Razorpay keys aren't configured yet, so this is a simulated payment. Add real keys in <code class="bg-amber-100 px-1 rounded">.env</code> to enable live payments.
      </div>
      <button id="demo-pay-btn" onclick="confirmDemoPayment({{ $booking->id }})" class="btn btn-primary w-full btn-lg justify-center mb-3">
        Simulate payment & confirm <x-icon name="arrow-right" class="w-4 h-4"/>
      </button>
    @else
      <button onclick="initRazorpay({{ Illuminate\Support\Js::from($orderData) }}, {{ $booking->id }})" class="btn btn-primary w-full btn-lg justify-center mb-3">
        Pay ₹{{ number_format($booking->advance_amount) }} securely <x-icon name="arrow-right" class="w-4 h-4"/>
      </button>
    @endif

    <p class="text-xs text-muted flex items-center justify-center gap-1.5"><x-icon name="lock" class="w-3.5 h-3.5"/>256-bit SSL · No card details stored</p>
  </div>
</div>
@endsection
@push('scripts')
@if(empty($orderData['demo']))
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
@endif
<script>
async function confirmDemoPayment(bookingId) {
  const btn = document.getElementById('demo-pay-btn');
  btn.disabled = true; btn.textContent = 'Processing...';
  try {
    const res = await fetch('{{ route('payment.demo.confirm') }}', {
      method: 'POST',
      headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
      body: JSON.stringify({ booking_id: bookingId })
    });
    const d = await res.json();
    if (d.success) window.location.href = d.redirect;
    else { alert(d.message || 'Something went wrong.'); btn.disabled = false; btn.textContent = 'Simulate payment & confirm'; }
  } catch (e) {
    alert('Network error. Try again.'); btn.disabled = false; btn.textContent = 'Simulate payment & confirm';
  }
}
</script>
@endpush

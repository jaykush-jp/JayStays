@extends('layouts.app')
@section('content')
<div class="max-w-3xl mx-auto px-4 py-14">
  <div class="text-center mb-10"><p class="eyebrow justify-center mb-2">Help center</p><h1 class="font-display text-4xl font-bold text-ink mb-2 tracking-tight">Frequently asked questions</h1></div>
  <div class="space-y-2.5" x-data="{open:0}">
    @foreach([['How does hourly booking work?','Search, select a hotel, fill your details, pay the advance, get an SMS, walk in and pay the balance at the hotel.'],['Do I need an account?','No — just your name and mobile number.'],['What do I pay online?','Around 10% of the room rate (the platform fee). The rest is paid at the hotel.'],['Does the hotel confirm my booking?','Yes, after payment. The hotel is notified instantly and usually confirms within 30 minutes.'],['What if the hotel rejects?','You get a full refund of the advance within 5–7 business days.'],['Can I cancel?','Yes — up to 2 hours before check-in for an advance refund.'],['What ID do I need at the hotel?','Any government photo ID: Aadhaar, PAN, Passport or Voter ID.'],['How do I list my hotel?','Click "List your hotel" in the nav, register and add your property. Admin reviews within 24 hours.']] as $i=>[$q,$a])
      <div class="card overflow-hidden">
        <button @click="open===@js($i)?open=null:open=@js($i)" class="w-full flex justify-between items-center gap-3 px-5 py-4 text-left text-sm font-semibold text-ink hover:bg-paper transition-colors" :aria-expanded="open===@js($i)">
          <span>{{ $q }}</span>
          <span class="w-7 h-7 rounded-full bg-brand-50 flex items-center justify-center text-iris flex-shrink-0 transition-transform duration-300" :class="open===@js($i)?'rotate-45':''"><x-icon name="plus" class="w-4 h-4"/></span>
        </button>
        <div x-show="open===@js($i)" x-collapse><div class="px-5 pb-4 pt-1 text-muted text-sm leading-relaxed">{{ $a }}</div></div>
      </div>
    @endforeach
  </div>
  <div class="card p-7 mt-8 text-center bg-ink">
    <h2 class="font-display font-semibold text-white text-lg mb-2">Still have questions?</h2>
    <p class="text-white/55 text-sm mb-5">Our support team is available 24/7.</p>
    <a href="{{ route('page.contact') }}" class="btn btn-white inline-flex">Contact support <x-icon name="arrow-right" class="w-4 h-4"/></a>
  </div>
</div>
@endsection

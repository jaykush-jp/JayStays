@extends('layouts.app')
@section('content')
<div class="min-h-[85vh] grid lg:grid-cols-2">
  {{-- LEFT: brand panel --}}
  <div class="relative hidden lg:flex flex-col justify-between p-12 bg-ink overflow-hidden">
    <div class="absolute inset-0 ink-grid opacity-40"></div>
    <div class="absolute -bottom-24 -left-10 w-96 h-96 rounded-full opacity-25 animate-drift" style="background:radial-gradient(circle,#5B5BD6,transparent 65%)"></div>
    <div class="absolute top-10 right-0 w-80 h-80 rounded-full opacity-20 animate-drift" style="background:radial-gradient(circle,#FF8A3D,transparent 65%);animation-delay:-5s"></div>
    <a href="{{ route('home') }}" class="relative flex items-center gap-2.5">
      <span class="w-9 h-9 rounded-xl bg-white/10 flex items-center justify-center"><x-icon name="clock" class="w-5 h-5 text-white"/></span>
      <span class="font-display font-bold text-xl text-white">My<span class="text-brand-300">Room</span></span>
    </a>
    <div class="relative">
      <h2 class="font-display font-bold text-4xl text-white leading-tight mb-4">Your room is<br>an hour away.</h2>
      <p class="text-white/60 max-w-sm mb-8">Book hotels by the hour or overnight across 50+ cities. Pay a small advance, settle the rest at the hotel.</p>
      <div class="space-y-3">
        @foreach([['bolt','Instant confirmation, every time'],['shield','Free cancellation up to 2h before'],['wallet','Pay just ~10% online to lock the room']] as [$ic,$t])
          <div class="flex items-center gap-3 text-white/80 text-sm"><span class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center"><x-icon name="{{ $ic }}" class="w-4 h-4"/></span>{{ $t }}</div>
        @endforeach
      </div>
    </div>
    <div class="relative text-white/40 text-xs">© {{ date('Y') }} MyRoom. Made in India.</div>
  </div>

  {{-- RIGHT: form --}}
  <div class="flex items-center justify-center px-4 py-12 bg-paper">
    <div class="w-full max-w-md">
      <div class="text-center mb-8 lg:hidden">
        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 mb-4">
          <span class="w-10 h-10 rounded-xl bg-ink flex items-center justify-center"><x-icon name="clock" class="w-5 h-5 text-white"/></span>
          <span class="font-display font-bold text-2xl text-ink">My<span class="text-iris">Room</span></span>
        </a>
      </div>
      <div class="mb-7">
        <h1 class="font-display text-2xl font-bold text-ink">Welcome back</h1>
        <p class="text-muted text-sm mt-1.5">Sign in with mobile OTP or email</p>
      </div>
      @if(session('error'))<div class="alert-error mb-5">{{ session('error') }}</div>@endif
      <div class="card p-8">
        <div x-data="{tab:'otp'}">
          <div class="flex rounded-xl border border-line p-1 mb-6">
            <button @click="tab='otp'" :class="tab==='otp'?'bg-ink text-white':'text-muted hover:text-ink'" class="flex-1 py-2 rounded-lg font-semibold text-sm transition-all flex items-center justify-center gap-1.5"><x-icon name="phone" class="w-4 h-4"/>OTP login</button>
            <button @click="tab='email'" :class="tab==='email'?'bg-ink text-white':'text-muted hover:text-ink'" class="flex-1 py-2 rounded-lg font-semibold text-sm transition-all flex items-center justify-center gap-1.5"><x-icon name="mail" class="w-4 h-4"/>Email login</button>
          </div>

          <div x-show="tab==='otp'">
            @if(!session('otp_sent'))
              <form method="POST" action="{{ route('auth.otp.send') }}">@csrf
                <div class="mb-4">
                  <label class="form-label" for="phone">Mobile number</label>
                  <div class="flex gap-2">
                    <div class="flex-shrink-0 bg-paper border border-line rounded-xl px-3 flex items-center font-semibold text-muted text-sm font-mono h-[50px]">+91</div>
                    <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" placeholder="10-digit mobile" maxlength="10" class="form-input flex-1" required autocomplete="tel"/>
                  </div>
                </div>
                <button class="btn btn-primary w-full justify-center btn-lg">Send OTP</button>
              </form>
            @else
              <div class="alert-info mb-5">OTP sent to +91 {{ session('otp_phone') }}
                @if(session('__dev_otp'))<br><strong>Dev OTP: {{ session('__dev_otp') }}</strong>@endif
              </div>
              @if(session('otp_error'))<div class="alert-error mb-3">{{ session('otp_error') }}</div>@endif
              <form method="POST" action="{{ route('auth.otp.verify') }}">@csrf
                <input type="hidden" name="phone" value="{{ session('otp_phone') }}"/>
                <div class="mb-5">
                  <label class="form-label">Enter 6-digit OTP</label>
                  <div class="flex gap-2 justify-center">
                    @for($i=0;$i<6;$i++)
                      <input type="text" name="otp_d{{ $i }}" id="otp-{{ $i }}" maxlength="1" inputmode="numeric"
                        oninput="this.value=this.value.replace(/\D/,'');if(this.value&&{{ $i }}<5)document.getElementById('otp-{{ $i+1 }}').focus()"
                        onkeydown="if(event.key==='Backspace'&&!this.value&&{{ $i }}>0)document.getElementById('otp-{{ $i-1 }}').focus()"
                        class="w-11 h-14 text-center text-xl font-bold font-mono border-[1.5px] border-line rounded-xl focus:border-iris focus:ring-4 focus:ring-iris/12 outline-none transition-all"/>
                    @endfor
                  </div>
                </div>
                <button class="btn btn-primary w-full justify-center btn-lg">Verify & sign in <x-icon name="arrow-right" class="w-4 h-4"/></button>
              </form>
              <div class="text-center mt-3"><a href="{{ route('login') }}" class="text-muted text-sm hover:text-iris">Use a different number</a></div>
            @endif
          </div>

          <div x-show="tab==='email'">
            <form method="POST" action="{{ route('auth.email.login') }}" class="space-y-4">@csrf
              <div><label class="form-label" for="email">Email</label><input type="email" id="email" name="email" value="{{ old('email') }}" class="form-input" required autocomplete="email"/></div>
              <div><label class="form-label" for="password">Password</label><input type="password" id="password" name="password" class="form-input" required/></div>
              <button class="btn btn-primary w-full justify-center btn-lg">Sign in <x-icon name="arrow-right" class="w-4 h-4"/></button>
            </form>
          </div>

          <div class="relative my-5">
            <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-line"></div></div>
            <div class="relative flex justify-center"><span class="bg-white px-4 text-sm text-muted">or</span></div>
          </div>
          <a href="{{ route('auth.google') }}" class="btn btn-white w-full justify-center gap-3 btn-lg">
            <svg width="18" height="18" viewBox="0 0 48 48"><path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/><path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/><path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/><path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/></svg>
            Continue with Google
          </a>
        </div>
      </div>
      <div class="flex items-center justify-center gap-4 mt-5 text-sm">
        <a href="{{ route('register') }}" class="text-muted hover:text-iris">New? Register</a>
        <span class="text-line">·</span>
        <a href="{{ route('hotel.register') }}" class="text-muted hover:text-iris">List your hotel</a>
      </div>
    </div>
  </div>
</div>
@endsection

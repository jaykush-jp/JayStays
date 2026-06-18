<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1.0"/>

  {{-- ───────────────────────── SEO / Social ───────────────────────── --}}
  <title>{{ $seo?->get('title') ?? 'MyRoom — Book Hotels by the Hour' }}</title>
  <meta name="description" content="{{ $seo?->get('description') ?? 'Book hotels by the hour or overnight across 50+ Indian cities. Pay a small advance, settle the rest at the hotel. Instant confirmation, no account needed.' }}"/>
  <meta name="keywords" content="{{ $seo?->get('keywords') }}"/>
  <meta name="robots" content="{{ $seo?->get('robots','index,follow') }}"/>
  <meta name="theme-color" content="#171532"/>
  <meta name="author" content="MyRoom"/>
  <link rel="canonical" href="{{ $seo?->get('canonical', request()->url()) }}"/>
  @if(config('seo.google_verification'))<meta name="google-site-verification" content="{{ config('seo.google_verification') }}"/>@endif

  {{-- Open Graph --}}
  <meta property="og:site_name"   content="MyRoom"/>
  <meta property="og:locale"      content="en_IN"/>
  <meta property="og:type"        content="{{ $seo?->get('og_type','website') }}"/>
  <meta property="og:title"       content="{{ $seo?->get('title') }}"/>
  <meta property="og:description" content="{{ $seo?->get('description') }}"/>
  <meta property="og:image"       content="{{ $seo?->get('og_image') }}"/>
  <meta property="og:image:width" content="1200"/>
  <meta property="og:image:height" content="630"/>
  <meta property="og:url"         content="{{ $seo?->get('canonical', request()->url()) }}"/>

  {{-- Twitter / X --}}
  <meta name="twitter:card"        content="summary_large_image"/>
  <meta name="twitter:title"       content="{{ $seo?->get('title') }}"/>
  <meta name="twitter:description" content="{{ $seo?->get('description') }}"/>
  <meta name="twitter:image"       content="{{ $seo?->get('og_image') }}"/>
  <meta name="twitter:site"        content="@myroom"/>

  <meta name="csrf-token" content="{{ csrf_token() }}"/>

  {{-- Fonts: Space Grotesk (display) + Inter (body) + JetBrains Mono (data). --}}
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
  <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet"/>

  <script src="https://cdn.tailwindcss.com"></script>
  <script>
  tailwind.config = {
    theme: {
      extend: {
        colors: {
          brand:   { DEFAULT:'#5B5BD6', 50:'#F2F2FD', 100:'#E6E6FA', 200:'#C9C9F2', 300:'#A9A9E9', 400:'#8585DF', 600:'#4B47C4', 700:'#3E3AA8', 800:'#171532', 900:'#100E26', light:'#F2F2FD', mid:'#E6E6FA', dark:'#171532' },
          primary: { DEFAULT:'#5B5BD6', 50:'#F2F2FD', 100:'#E6E6FA', 200:'#C9C9F2', 300:'#A9A9E9', 400:'#8585DF', 600:'#4B47C4', 700:'#3E3AA8', 800:'#171532', 900:'#100E26' },
          ink:     { DEFAULT:'#171532', soft:'#2A2752', 700:'#3A3766' },
          iris:    { DEFAULT:'#5B5BD6', deep:'#4B47C4' },
          amber:   { DEFAULT:'#FF8A3D', soft:'#FFF1E7', deep:'#E5701F' },
          accent:  { DEFAULT:'#FF8A3D', light:'#FFF1E7' },
          paper:   '#FBFAF8',
          line:    '#ECE9F2',
          muted:   '#6B6880',
        },
        fontFamily: {
          sans:    ['Inter','ui-sans-serif','sans-serif'],
          display: ['"Space Grotesk"','Inter','sans-serif'],
          mono:    ['"JetBrains Mono"','ui-monospace','monospace'],
        },
        boxShadow: {
          'soft':  '0 1px 2px rgba(23,21,50,.04), 0 8px 24px -12px rgba(23,21,50,.12)',
          'lift':  '0 20px 48px -20px rgba(23,21,50,.28)',
          'glow':  '0 8px 30px -8px rgba(91,91,214,.45)',
        },
        keyframes: {
          floaty:  { '0%,100%':{transform:'translateY(0)'}, '50%':{transform:'translateY(-14px)'} },
          drift:   { '0%,100%':{transform:'translate(0,0) scale(1)'}, '50%':{transform:'translate(20px,-20px) scale(1.06)'} },
        },
        animation: {
          floaty:  'floaty 7s ease-in-out infinite',
          drift:   'drift 16s ease-in-out infinite',
        },
      }
    }
  }
  </script>
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.3/dist/cdn.min.js"></script>

  {!! $seo?->renderSchemas() !!}

  <style>
    *,*::before,*::after{box-sizing:border-box}
    html{-webkit-font-smoothing:antialiased;text-rendering:optimizeLegibility}
    svg{flex-shrink:0}
    body{font-family:'Inter',sans-serif;background:#FBFAF8;color:#171532;margin:0}
    .font-display{font-family:'Space Grotesk',sans-serif}
    .font-mono{font-family:'JetBrains Mono',ui-monospace,monospace}
    .tnum{font-variant-numeric:tabular-nums}

    /* Buttons */
    .btn{display:inline-flex;align-items:center;justify-content:center;gap:.5rem;font-weight:600;border-radius:.75rem;cursor:pointer;text-decoration:none;border:none;transition:transform .18s cubic-bezier(.34,1.56,.64,1),box-shadow .2s,background .2s,color .2s,border-color .2s;white-space:nowrap;position:relative;font-family:'Inter',sans-serif}
    .btn:active{transform:scale(.97)}
    .btn-primary{background:#5B5BD6;color:#fff;padding:.6875rem 1.5rem;font-size:.9375rem;box-shadow:0 8px 22px -10px rgba(91,91,214,.7)}
    .btn-primary:hover{background:#4B47C4;box-shadow:0 14px 30px -10px rgba(91,91,214,.75);transform:translateY(-2px)}
    .btn-accent{background:#FF8A3D;color:#fff;padding:.6875rem 1.5rem;font-size:.9375rem;box-shadow:0 8px 22px -10px rgba(255,138,61,.7)}
    .btn-accent:hover{background:#E5701F;transform:translateY(-2px)}
    .btn-ink{background:#171532;color:#fff;padding:.6875rem 1.5rem;font-size:.9375rem}
    .btn-ink:hover{background:#2A2752;transform:translateY(-2px)}
    .btn-white{background:#fff;color:#171532;border:1.5px solid #ECE9F2;padding:.6875rem 1.5rem;font-size:.9375rem}
    .btn-white:hover{border-color:#A9A9E9;color:#4B47C4;box-shadow:0 8px 20px -12px rgba(23,21,50,.25)}
    .btn-outline{background:transparent;color:#5B5BD6;border:1.5px solid #5B5BD6;padding:.6875rem 1.5rem;font-size:.9375rem}
    .btn-outline:hover{background:#5B5BD6;color:#fff}
    .btn-ghost{background:transparent;color:#6B6880;padding:.5625rem 1rem;border:1px solid transparent;font-size:.9375rem}
    .btn-ghost:hover{background:#F2F2FD;color:#171532}
    .btn-sm{padding:.4375rem .875rem;font-size:.8125rem;border-radius:.625rem}
    .btn-lg{padding:.875rem 1.75rem;font-size:1rem;border-radius:.875rem}
    .btn-xl{padding:1.0625rem 2rem;font-size:1.0625rem;border-radius:1rem}

    /* Cards */
    .card{background:#fff;border-radius:1.25rem;border:1px solid #ECE9F2;box-shadow:0 1px 2px rgba(23,21,50,.04),0 8px 24px -16px rgba(23,21,50,.14)}
    .card-hover{background:#fff;border-radius:1.25rem;border:1px solid #ECE9F2;box-shadow:0 1px 2px rgba(23,21,50,.04),0 8px 24px -16px rgba(23,21,50,.14);transition:transform .3s cubic-bezier(.22,1,.36,1),box-shadow .3s,border-color .3s}
    .card-hover:hover{box-shadow:0 28px 56px -24px rgba(23,21,50,.32);transform:translateY(-6px);border-color:#C9C9F2}
    .glass{background:rgba(255,255,255,.9);backdrop-filter:blur(18px);-webkit-backdrop-filter:blur(18px);border:1px solid rgba(255,255,255,.6)}

    /* Forms */
    .form-label{display:block;font-size:.75rem;font-weight:600;color:#171532;margin-bottom:.4rem;letter-spacing:.02em}
    .form-input,.form-select,.form-textarea{width:100%;border:1.5px solid #ECE9F2;border-radius:.75rem;padding:.8125rem 1rem;font-size:.9375rem;color:#171532;outline:none;background:#fff;font-family:'Inter',sans-serif;transition:border-color .2s,box-shadow .2s}
    .form-input:focus,.form-select:focus,.form-textarea:focus{border-color:#5B5BD6;box-shadow:0 0 0 4px rgba(91,91,214,.12)}
    .form-select{cursor:pointer}
    .form-textarea{resize:none}

    /* Badges */
    .badge{display:inline-flex;align-items:center;gap:.3rem;padding:.25rem .625rem;border-radius:9999px;font-size:.75rem;font-weight:600;line-height:1.3}
    .badge-primary{background:#E6E6FA;color:#3E3AA8}
    .badge-success{background:#DCFCE7;color:#15803D}
    .badge-warning{background:#FEF3C7;color:#B45309}
    .badge-danger{background:#FEE2E2;color:#B91C1C}
    .badge-gray{background:#F3F2F7;color:#3A3766}
    .badge-orange,.badge-night{background:#FFF1E7;color:#E5701F}
    .badge-purple{background:#F2F2FD;color:#4B47C4}

    /* Status pills */
    .status-pending{background:#FEF3C7;color:#B45309}
    .status-confirmed,.status-accepted,.status-active{background:#DCFCE7;color:#15803D}
    .status-rejected,.status-cancelled,.status-banned,.status-no_show{background:#FEE2E2;color:#B91C1C}
    .status-completed,.status-checked_in{background:#E0E7FF;color:#3730A3}
    .status-inactive{background:#F3F2F7;color:#3A3766}
    .status-pending,.status-confirmed,.status-accepted,.status-active,.status-rejected,.status-cancelled,
    .status-banned,.status-no_show,.status-completed,.status-checked_in,.status-inactive{
      padding:.25rem .625rem;border-radius:9999px;font-size:.75rem;font-weight:600;white-space:nowrap;display:inline-flex;align-items:center;gap:.3rem}

    /* Nav */
    .nav-link{padding:.5rem .8rem;border-radius:.625rem;font-size:.9375rem;font-weight:500;color:#3A3766;text-decoration:none;transition:all .2s}
    .nav-link:hover,.nav-active{color:#5B5BD6;background:#F2F2FD}
    .mobile-nav-link{display:flex;align-items:center;gap:.6rem;padding:.75rem 1rem;font-size:.9375rem;font-weight:500;color:#3A3766;text-decoration:none;border-radius:.75rem;transition:all .2s}
    .mobile-nav-link:hover{background:#F2F2FD;color:#5B5BD6}

    /* Table */
    .data-table{width:100%;border-collapse:collapse;font-size:.875rem}
    .data-table th{background:#FBFAF8;padding:.875rem 1rem;text-align:left;font-size:.7rem;font-weight:700;color:#6B6880;text-transform:uppercase;letter-spacing:.06em;white-space:nowrap}
    .data-table td{padding:.9375rem 1rem;border-top:1px solid #F3F2F7}
    .data-table tr:hover td{background:#FBFAF8}

    /* Alerts */
    .alert-success{background:#F0FDF4;border:1px solid #BBF7D0;color:#15803D;border-radius:.875rem;padding:.875rem 1rem;font-size:.9375rem}
    .alert-error{background:#FEF2F2;border:1px solid #FECACA;color:#B91C1C;border-radius:.875rem;padding:.875rem 1rem;font-size:.9375rem}
    .alert-info{background:#F2F2FD;border:1px solid #C9C9F2;color:#3E3AA8;border-radius:.875rem;padding:.875rem 1rem;font-size:.9375rem}
    .alert-warning{background:#FFFBEB;border:1px solid #FDE68A;color:#B45309;border-radius:.875rem;padding:.875rem 1rem;font-size:.9375rem}

    .notif-badge{position:absolute;top:-4px;right:-4px;background:#FF8A3D;color:#fff;font-size:.625rem;font-weight:700;padding:0 .3rem;border-radius:9999px;min-width:16px;height:16px;display:flex;align-items:center;justify-content:center}

    /* Section eyebrow */
    .eyebrow{display:inline-flex;align-items:center;gap:.45rem;font-size:.75rem;font-weight:600;letter-spacing:.08em;text-transform:uppercase;color:#5B5BD6}
    .eyebrow::before{content:"";width:1.4rem;height:1.5px;background:#5B5BD6;border-radius:2px}

    .ink-grid{background-image:linear-gradient(rgba(255,255,255,.06) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.06) 1px,transparent 1px);background-size:48px 48px}

    /* Scroll reveal */
    [data-reveal]{opacity:0;transform:translateY(22px);transition:opacity .7s cubic-bezier(.22,1,.36,1),transform .7s cubic-bezier(.22,1,.36,1)}
    [data-reveal].in{opacity:1;transform:none}

    ::-webkit-scrollbar{width:8px;height:8px}
    ::-webkit-scrollbar-thumb{background:#C9C9F2;border-radius:8px}
    ::-webkit-scrollbar-thumb:hover{background:#5B5BD6}
    *:focus-visible{outline:2px solid #5B5BD6;outline-offset:2px}
    input[type="date"]::-webkit-calendar-picker-indicator,input[type="datetime-local"]::-webkit-calendar-picker-indicator{cursor:pointer;opacity:.55;filter:invert(31%) sepia(78%) saturate(2200%) hue-rotate(229deg)}
    input[type="checkbox"]{accent-color:#5B5BD6}
    input[type="range"]::-webkit-slider-thumb{-webkit-appearance:none;width:18px;height:18px;border-radius:50%;background:#5B5BD6;cursor:pointer;box-shadow:0 2px 6px rgba(91,91,214,.5);border:2px solid #fff}
    input[type="range"]::-moz-range-thumb{width:18px;height:18px;border-radius:50%;background:#5B5BD6;cursor:pointer;border:2px solid #fff}
    .page-fade{animation:pf .5s cubic-bezier(.22,1,.36,1) both}
    @keyframes pf{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:none}}
    [x-collapse]{overflow:hidden;transition:height .25s ease}

    @media (prefers-reduced-motion: reduce){
      *,*::before,*::after{animation-duration:.001ms!important;animation-iteration-count:1!important;transition-duration:.001ms!important;scroll-behavior:auto!important}
      [data-reveal]{opacity:1;transform:none}
    }
    @media print{nav,footer,.no-print{display:none!important}}
  </style>
  @stack('head')
</head>
<body class="min-h-screen flex flex-col" x-data>

  {{-- Global Toast --}}
  <div x-data="{msgs:[]}" x-on:toast.window="msgs.push($event.detail);setTimeout(()=>msgs.shift(),4000)"
    class="fixed top-5 right-5 z-[9999] flex flex-col gap-2 pointer-events-none">
    <template x-for="(m,i) in msgs" :key="i">
      <div class="pointer-events-auto flex items-center gap-3 px-5 py-3.5 rounded-2xl shadow-lift text-sm font-semibold text-white"
        :class="m.type==='error'?'bg-red-600':'bg-ink'"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0">
        <span x-text="m.msg"></span>
      </div>
    </template>
  </div>

  @include('components.navbar')

  @if(session('success'))<div class="max-w-7xl mx-auto px-4 pt-4 w-full"><div class="alert-success flex items-center gap-2"><x-icon name="check-circle" class="w-5 h-5"/> {{ session('success') }}</div></div>@endif
  @if(session('error'))<div class="max-w-7xl mx-auto px-4 pt-4 w-full"><div class="alert-error flex items-center gap-2"><x-icon name="info" class="w-5 h-5"/> {{ session('error') }}</div></div>@endif

  <main class="flex-1 page-fade">@yield('content')</main>

  @include('components.footer')

  <script>
  // Scroll reveal — reduced-motion aware
  (function(){
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
    const io = new IntersectionObserver((entries)=>{
      entries.forEach(e=>{ if(e.isIntersecting){ e.target.classList.add('in'); io.unobserve(e.target); }});
    },{threshold:.12, rootMargin:'0px 0px -40px 0px'});
    const run = ()=>document.querySelectorAll('[data-reveal]:not(.in)').forEach(el=>io.observe(el));
    document.addEventListener('DOMContentLoaded', run); run();
  })();

  document.addEventListener('alpine:init', () => {
    Alpine.data('searchBox', (cities=[]) => ({
      city:'', open:false, filtered:[],
      date:new Date().toISOString().split('T')[0], time:'17:00', type:'hourly',
      get times(){const s=[];for(let h=6;h<24;h++){const p=h<12?'AM':'PM',h12=h>12?h-12:h===0?12:h;s.push({value:`${String(h).padStart(2,'0')}:00`,label:`${h12}:00 ${p}`})}return s},
      filter(){if(!this.city.trim()){this.filtered=cities.slice(0,8);this.open=!!cities.length;return}this.filtered=cities.filter(c=>c.city.toLowerCase().includes(this.city.toLowerCase()));this.open=!!this.filtered.length},
      pick(c){this.city=c;this.open=false},
    }));
    Alpine.data('otpInput',()=>({
      phone:'',step:'phone',sending:false,resendTimer:0,digits:['','','','','',''],
      get otp(){return this.digits.join('')},
      startTimer(){this.resendTimer=30;const t=setInterval(()=>{if(--this.resendTimer<=0)clearInterval(t)},1000)},
      onInput(i){if(this.digits[i]&&i<5)this.$nextTick(()=>document.getElementById('otp-'+(i+1))?.focus())},
      onBack(i){if(!this.digits[i]&&i>0)document.getElementById('otp-'+(i-1))?.focus()},
    }));
    Alpine.data('bookingCalc',(hourly=0,overnight=0,pct=10)=>({
      stayType:'hourly',hours:2,discount:0,offerCode:'',offerApplied:null,offerError:'',payType:'partial',
      get roomCost(){return this.stayType==='hourly'?hourly*this.hours:overnight},
      get net(){return Math.max(0,this.roomCost-this.discount)},
      get advance(){return this.payType==='full'?this.net:Math.round(this.net*pct/100)},
      get balance(){return Math.max(0,this.net-this.advance)},
      async applyOffer(){this.offerError='';try{const r=await fetch('/offers/check',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content},body:JSON.stringify({code:this.offerCode,amount:this.roomCost,stay_type:this.stayType})});const d=await r.json();if(d.success){this.discount=d.discount;this.offerApplied=d.offer}else{this.offerError=d.message;this.discount=0}}catch{this.offerError='Unable to apply. Try again.'}},
    }));
  });
  window.initRazorpay=function(opts,bookingId){
    // Map backend orderData fields to the names Razorpay checkout.js expects.
    // (Backend sends key_id / guest_* ; Razorpay needs key / prefill.*)
    const options={
      key:        opts.key || opts.key_id,
      amount:     opts.amount,
      currency:   opts.currency || 'INR',
      name:       'MyRoom',
      description: opts.booking_ref ? ('Booking '+opts.booking_ref) : 'Hotel booking',
      order_id:   opts.order_id,
      prefill:{
        name:    opts.guest_name  || '',
        email:   opts.guest_email || '',
        contact: opts.guest_phone || '',
      },
      theme:{ color:'#5B5BD6' },
      handler:async function(r){
        const res=await fetch('/payment/razorpay/verify',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content},body:JSON.stringify({...r,booking_id:bookingId})});
        const d=await res.json();
        if(d.success)window.location.href=d.redirect;else alert('Payment verification failed. Contact support with Booking ID.');
      }
    };
    if(!options.key){alert('Payment configuration error: Razorpay key is missing. Please add RAZORPAY_KEY_ID to your .env file.');return;}
    const rzp=new Razorpay(options);
    rzp.on('payment.failed',function(resp){alert('Payment failed: '+(resp.error&&resp.error.description?resp.error.description:'Please try again.'));});
    rzp.open();
  };
  </script>
  @stack('scripts')
</body>
</html>

<meta charset="UTF-8"/><meta name="viewport" content="width=device-width,initial-scale=1.0"/>
<meta name="robots" content="noindex,nofollow"/>
<meta name="csrf-token" content="{{ csrf_token() }}"/>
<link rel="preconnect" href="https://fonts.googleapis.com"/><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com"></script>
<script>
tailwind.config={theme:{extend:{
  colors:{
    brand:{DEFAULT:'#5B5BD6',50:'#F2F2FD',100:'#E6E6FA',200:'#C9C9F2',300:'#A9A9E9',400:'#8585DF',600:'#4B47C4',700:'#3E3AA8',800:'#171532',900:'#100E26'},
    primary:{DEFAULT:'#5B5BD6',50:'#F2F2FD',100:'#E6E6FA',200:'#C9C9F2',300:'#A9A9E9',400:'#8585DF',600:'#4B47C4',700:'#3E3AA8',800:'#171532',900:'#100E26'},
    ink:{DEFAULT:'#171532',soft:'#2A2752',700:'#3A3766'},iris:{DEFAULT:'#5B5BD6',deep:'#4B47C4'},
    amber:{DEFAULT:'#FF8A3D',soft:'#FFF1E7',deep:'#E5701F'},accent:{DEFAULT:'#FF8A3D',light:'#FFF1E7'},
    paper:'#FBFAF8',line:'#ECE9F2',muted:'#6B6880'},
  fontFamily:{sans:['Inter','sans-serif'],display:['"Space Grotesk"','sans-serif'],mono:['"JetBrains Mono"','ui-monospace','monospace']},
  boxShadow:{soft:'0 1px 2px rgba(23,21,50,.04),0 8px 24px -16px rgba(23,21,50,.14)'}
}}}
</script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.3/dist/cdn.min.js"></script>
<style>
  svg{flex-shrink:0}
    body{font-family:'Inter',sans-serif;background:#FBFAF8;color:#171532}
  .font-display{font-family:'Space Grotesk',sans-serif}.font-mono{font-family:'JetBrains Mono',ui-monospace,monospace}.tnum{font-variant-numeric:tabular-nums}
  .card{background:#fff;border-radius:1.25rem;border:1px solid #ECE9F2;box-shadow:0 1px 2px rgba(23,21,50,.04),0 8px 24px -16px rgba(23,21,50,.14)}
  .card-hover{background:#fff;border-radius:1.25rem;border:1px solid #ECE9F2;box-shadow:0 1px 2px rgba(23,21,50,.04),0 8px 24px -16px rgba(23,21,50,.14);transition:transform .3s,box-shadow .3s,border-color .3s}
  .card-hover:hover{box-shadow:0 28px 56px -24px rgba(23,21,50,.32);transform:translateY(-4px);border-color:#C9C9F2}
  .form-label{display:block;font-size:.75rem;font-weight:600;color:#171532;margin-bottom:.4rem}
  .form-input,.form-select,.form-textarea{width:100%;border:1.5px solid #ECE9F2;border-radius:.75rem;padding:.75rem 1rem;font-size:.9375rem;color:#171532;outline:none;background:#fff;transition:border-color .2s,box-shadow .2s;font-family:'Inter',sans-serif}
  .form-input:focus,.form-select:focus,.form-textarea:focus{border-color:#5B5BD6;box-shadow:0 0 0 4px rgba(91,91,214,.12)}.form-select{cursor:pointer}.form-textarea{resize:none}
  .btn{display:inline-flex;align-items:center;justify-content:center;gap:.5rem;font-weight:600;border-radius:.75rem;cursor:pointer;text-decoration:none;border:none;transition:transform .18s,box-shadow .2s,background .2s,color .2s;font-family:'Inter',sans-serif;white-space:nowrap}
  .btn:active{transform:scale(.97)}
  .btn-primary{background:#5B5BD6;color:#fff;padding:.6875rem 1.5rem;font-size:.9375rem;box-shadow:0 8px 22px -10px rgba(91,91,214,.7)}.btn-primary:hover{background:#4B47C4;transform:translateY(-2px)}
  .btn-accent{background:#FF8A3D;color:#fff;padding:.6875rem 1.5rem;font-size:.9375rem}.btn-accent:hover{background:#E5701F}
  .btn-ink{background:#171532;color:#fff;padding:.6875rem 1.5rem;font-size:.9375rem}.btn-ink:hover{background:#2A2752}
  .btn-white{background:#fff;color:#171532;border:1.5px solid #ECE9F2;padding:.6875rem 1.5rem;font-size:.9375rem}.btn-white:hover{border-color:#A9A9E9;color:#4B47C4}
  .btn-outline{background:transparent;color:#5B5BD6;border:1.5px solid #5B5BD6;padding:.6875rem 1.5rem;font-size:.9375rem}.btn-outline:hover{background:#5B5BD6;color:#fff}
  .btn-ghost{background:transparent;color:#6B6880;padding:.5625rem 1rem;border:1px solid transparent;font-size:.9375rem}.btn-ghost:hover{background:#F2F2FD;color:#171532}
  .btn-sm{padding:.4375rem .875rem;font-size:.8125rem;border-radius:.625rem}.btn-lg{padding:.875rem 1.75rem;font-size:1rem}
  .badge{display:inline-flex;align-items:center;gap:.3rem;padding:.25rem .625rem;border-radius:9999px;font-size:.75rem;font-weight:600}
  .badge-primary{background:#E6E6FA;color:#3E3AA8}.badge-success{background:#DCFCE7;color:#15803D}.badge-warning{background:#FEF3C7;color:#B45309}.badge-danger{background:#FEE2E2;color:#B91C1C}.badge-gray{background:#F3F2F7;color:#3A3766}.badge-orange,.badge-night{background:#FFF1E7;color:#E5701F}.badge-purple{background:#F2F2FD;color:#4B47C4}
  .status-pending{background:#FEF3C7;color:#B45309}.status-confirmed,.status-accepted,.status-active{background:#DCFCE7;color:#15803D}.status-rejected,.status-cancelled,.status-banned,.status-no_show{background:#FEE2E2;color:#B91C1C}.status-completed,.status-checked_in{background:#E0E7FF;color:#3730A3}.status-inactive{background:#F3F2F7;color:#3A3766}
  .status-pending,.status-confirmed,.status-accepted,.status-active,.status-rejected,.status-cancelled,.status-banned,.status-no_show,.status-completed,.status-checked_in,.status-inactive{padding:.25rem .625rem;border-radius:9999px;font-size:.75rem;font-weight:600;white-space:nowrap;display:inline-flex;align-items:center;gap:.3rem}
  .data-table{width:100%;border-collapse:collapse;font-size:.875rem}
  .data-table th{background:#FBFAF8;padding:.875rem 1rem;text-align:left;font-size:.7rem;font-weight:700;color:#6B6880;text-transform:uppercase;letter-spacing:.06em;white-space:nowrap}
  .data-table td{padding:.9375rem 1rem;border-top:1px solid #F3F2F7}.data-table tr:hover td{background:#FBFAF8}
  .alert-success{background:#F0FDF4;border:1px solid #BBF7D0;color:#15803D;border-radius:.875rem;padding:.875rem 1rem}
  .alert-error{background:#FEF2F2;border:1px solid #FECACA;color:#B91C1C;border-radius:.875rem;padding:.875rem 1rem}
  .alert-info{background:#F2F2FD;border:1px solid #C9C9F2;color:#3E3AA8;border-radius:.875rem;padding:.875rem 1rem}
  .alert-warning{background:#FFFBEB;border:1px solid #FDE68A;color:#B45309;border-radius:.875rem;padding:.875rem 1rem}
  .notif-badge{position:absolute;top:-4px;right:-4px;background:#FF8A3D;color:#fff;font-size:.625rem;font-weight:700;padding:0 .3rem;border-radius:9999px;min-width:16px;height:16px;display:flex;align-items:center;justify-content:center}
  .side-link{display:flex;align-items:center;gap:.75rem;padding:.625rem .75rem;border-radius:.75rem;font-size:.875rem;font-weight:500;transition:all .2s;color:rgba(255,255,255,.6)}
  .side-link:hover{background:rgba(255,255,255,.08);color:#fff}
  .side-link.active{background:rgba(255,255,255,.1);color:#fff}
  ::-webkit-scrollbar{width:8px;height:8px}::-webkit-scrollbar-thumb{background:#C9C9F2;border-radius:8px}
  *:focus-visible{outline:2px solid #5B5BD6;outline-offset:2px}
  input[type="checkbox"]{accent-color:#5B5BD6}
  @media print{.no-print{display:none!important}}
</style>

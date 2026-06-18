<?php
use Illuminate\Support\Facades\Schedule;
// Auto-cancel unresponsive bookings after timeout
Schedule::call(function() {
    $timeout = (int) \App\Models\Setting::getValue('booking_accept_timeout', 2);
    \App\Models\Booking::where('status','pending')
        ->where('payment_status','!=','pending')
        ->where('created_at','<',now()->subHours($timeout))
        ->update(['status'=>'cancelled']);
})->hourly();

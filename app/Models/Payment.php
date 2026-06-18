<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Payment extends Model {
    protected $fillable = ['booking_id','amount','type','gateway','gateway_order_id','gateway_payment_id','gateway_signature','status','gateway_response'];
    protected $casts = ['gateway_response'=>'array'];
    public function booking() { return $this->belongsTo(Booking::class); }
}

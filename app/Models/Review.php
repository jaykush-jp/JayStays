<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Review extends Model {
    protected $fillable = ['booking_id','customer_id','hotel_id','rating','comment','hotel_reply','hotel_replied_at','status'];
    protected $casts = ['hotel_replied_at'=>'datetime'];
    public function booking()  { return $this->belongsTo(Booking::class); }
    public function customer() { return $this->belongsTo(User::class,'customer_id'); }
    public function hotel()    { return $this->belongsTo(Hotel::class); }
}

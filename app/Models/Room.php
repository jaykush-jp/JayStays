<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model {
    use SoftDeletes;
    protected $fillable = ['hotel_id','name','description','stay_type','hourly_price','min_hours','overnight_price','price_3hr','price_6hr','price_12hr','capacity','amenities','images','is_available'];
    protected $casts = ['amenities'=>'array','images'=>'array','is_available'=>'boolean'];
    public function hotel()  { return $this->belongsTo(Hotel::class); }
    public function bookings(){ return $this->hasMany(Booking::class); }
    public function availability() { return $this->hasMany(RoomAvailability::class); }
    public function getPriceForHours(int $hours): float {
        if ($hours <= 3 && $this->price_3hr)  return $this->price_3hr;
        if ($hours <= 6 && $this->price_6hr)  return $this->price_6hr;
        if ($hours <= 12 && $this->price_12hr) return $this->price_12hr;
        return $this->hourly_price * $hours;
    }
}

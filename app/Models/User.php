<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {
    use HasFactory, Notifiable, SoftDeletes;
    protected $fillable = ['name','email','phone','password','google_id','avatar','role','status','email_verified_at','phone_verified_at'];
    protected $hidden   = ['password','remember_token'];
    protected $casts    = ['email_verified_at'=>'datetime','phone_verified_at'=>'datetime'];
    public function hotels()        { return $this->hasMany(Hotel::class); }
    public function bookings()      { return $this->hasMany(Booking::class,'customer_id'); }
    public function reviews()       { return $this->hasMany(Review::class,'customer_id'); }
    public function wishlists()     { return $this->hasMany(Wishlist::class,'customer_id'); }
    public function notifications() { return $this->hasMany(Notification::class)->latest(); }
    public function unreadNotifications() { return $this->notifications()->whereNull('read_at'); }
    public function isAdmin()       { return $this->role === 'admin'; }
    public function isHotelOwner()  { return $this->role === 'hotel_owner'; }
    public function isCustomer()    { return $this->role === 'customer'; }
    public function isActive()      { return $this->status === 'active'; }
    public function getInitialsAttribute() { return strtoupper(implode('',array_map(fn($w)=>$w[0],explode(' ',$this->name)))); }
}

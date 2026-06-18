<?php
// This file shows all model code. Each is saved separately below.
// File: app/Models/Hotel.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Hotel extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id','name','slug','city','area','address','description',
        'star_rating','amenities','images','cover_image','latitude','longitude',
        'listing_priority','listing_order','commission_percent','status',
        'rejection_reason','avg_rating','total_reviews','is_featured',
        'couple_friendly','accepts_local_id','tags',
    ];

    protected $casts = [
        'amenities'      => 'array',
        'images'         => 'array',
        'tags'           => 'array',
        'is_featured'    => 'boolean',
        'couple_friendly'=> 'boolean',
        'accepts_local_id'=> 'boolean',
        'latitude'       => 'float',
        'longitude'      => 'float',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(fn($m) => $m->slug ??= Str::slug($m->name).'-'.Str::random(5));
    }

    public function owner()        { return $this->belongsTo(User::class, 'user_id'); }
    public function rooms()        { return $this->hasMany(Room::class); }
    public function bookings()     { return $this->hasMany(Booking::class); }
    public function reviews()      { return $this->hasMany(Review::class); }
    public function offers()       { return $this->hasMany(Offer::class); }
    public function wishlists()    { return $this->hasMany(Wishlist::class); }

    public function getEffectiveCommissionAttribute(): float
    {
        return $this->commission_percent ?? (float) Setting::getValue('default_commission', 10);
    }

    public function scopeActive($q)   { return $q->where('status','active'); }
    public function scopeFeatured($q) { return $q->where('is_featured',true); }
    public function scopeOrdered($q)  {
        return $q->orderByRaw("FIELD(listing_priority,'top','middle','lower')")->orderBy('listing_order');
    }
}

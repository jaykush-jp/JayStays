<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Booking extends Model {
    use SoftDeletes;
    protected $fillable = [
        'booking_ref','customer_id','hotel_id','room_id',
        'guest_name','guest_phone','guest_email',
        'stay_type','checkin_at','checkout_at','hours',
        'room_rate','advance_amount','balance_amount','discount_amount','offer_code',
        'payment_type','status','payment_status',
        'hotel_accepted_at','hotel_rejected_at','rejection_reason','hotel_notes','special_requests'
    ];
    protected $casts = ['checkin_at'=>'datetime','checkout_at'=>'datetime','hotel_accepted_at'=>'datetime','hotel_rejected_at'=>'datetime'];
    protected static function boot() {
        parent::boot();
        static::creating(fn($m) => $m->booking_ref ??= 'MR'.strtoupper(Str::random(10)));
    }
    public function customer()  { return $this->belongsTo(User::class,'customer_id'); }
    public function hotel()     { return $this->belongsTo(Hotel::class); }
    public function room()      { return $this->belongsTo(Room::class); }
    public function payments()  { return $this->hasMany(Payment::class); }
    public function review()    { return $this->hasOne(Review::class); }
    public function isPending()   { return $this->status === 'pending'; }
    public function isAccepted()  { return $this->status === 'accepted'; }
    public function isConfirmed() { return $this->status === 'confirmed'; }
    public function isRejected()  { return $this->status === 'rejected'; }
    public function isCompleted() { return $this->status === 'completed'; }
    public function canReview()   { return $this->status === 'completed' && !$this->review; }
    public function getStatusColorAttribute(): string {
        return match($this->status) {
            'confirmed','accepted' => 'green',
            'pending'              => 'amber',
            'rejected','cancelled','no_show' => 'red',
            'completed','checked_in' => 'blue',
            default                => 'gray',
        };
    }
    public function getStatusLabelAttribute(): string {
        return match($this->status) {
            'pending'    => 'Awaiting Confirmation',
            'accepted'   => 'Accepted by Hotel',
            'confirmed'  => 'Confirmed',
            'rejected'   => 'Rejected',
            'checked_in' => 'Checked In',
            'completed'  => 'Completed',
            'cancelled'  => 'Cancelled',
            'no_show'    => 'No Show',
            default      => ucfirst($this->status),
        };
    }
}

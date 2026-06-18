<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class RoomAvailability extends Model {
    protected $fillable = ['room_id','date','is_blocked','block_reason','custom_price'];
    protected $casts    = ['date'=>'date','is_blocked'=>'boolean'];
    public function room() { return $this->belongsTo(Room::class); }
}

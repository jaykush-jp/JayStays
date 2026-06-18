<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Offer extends Model {
    protected $fillable = ['hotel_id','title','description','code','type','discount','max_discount','min_amount','stay_type','usage_limit','used_count','valid_from','valid_to','is_active'];
    protected $casts = ['valid_from'=>'date','valid_to'=>'date','is_active'=>'boolean'];
    public function hotel() { return $this->belongsTo(Hotel::class); }
    public function scopeActive($q) { return $q->where('is_active',true)->where(fn($q2)=>$q2->whereNull('valid_to')->orWhere('valid_to','>=',now())); }
    public function isValid(float $amount=0): bool {
        if (!$this->is_active) return false;
        if ($this->valid_from && now()->lt($this->valid_from)) return false;
        if ($this->valid_to   && now()->gt($this->valid_to))   return false;
        if ($this->usage_limit && $this->used_count>=$this->usage_limit) return false;
        return $amount >= $this->min_amount;
    }
    public function calculateDiscount(float $amount): float {
        $d = $this->type==='percentage' ? ($amount*$this->discount/100) : $this->discount;
        if ($this->max_discount) $d = min($d,$this->max_discount);
        return round($d,2);
    }
}

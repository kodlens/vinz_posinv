<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryAdjustment extends Model
{
    use HasFactory;

    protected $table = 'inventory_adjustments';
    protected $primaryKey = 'inventory_adjustment_id';
    protected $fillable = [
        'item_id', 
        'adjust_option',
        'adjusted_qty',
        'remarks'
    ];

    public function item(){
        return $this->hasOne(Item::class, 'item_id', 'item_id');
    }


}

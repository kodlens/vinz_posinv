<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesItemSerial extends Model
{
    use HasFactory;


    protected $table = 'sales_item_serials';
    protected $primaryKey = 'sales_item_serial_id';
    protected $fillable = [
        'sales_detail_id', 'sales_id', 'item_id', 'serial'
    ];


}

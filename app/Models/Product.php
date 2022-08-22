<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function payment()
    {
        return $this->hasMany(Payment::class,'product_id');
    }

    public function order_item()
    {
        return $this->hasOne(Order_item::class,'product_id');
    }
}

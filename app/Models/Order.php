<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function order_item()
    {
        return $this->hasOne(Order_item::class,'order_id');
    }

    public function order_shipping()
    {
        return $this->hasOne(Order_shipping::class,'order_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class,'order_id');
    }

    public function order_status()
    {
        return $this->hasMany(Order_status::class,'order_id');
    }
}

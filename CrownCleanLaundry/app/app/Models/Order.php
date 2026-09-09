<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_number',
        'customer_id',
        'customer_name',
        'phone_number',
        'order_date',
        'delivery_date',
        'sub_total',
        'addon_total',
        'discount',
        'tax_type',
        'taxable_amount',
        'tax_percentage',
        'tax_amount',
        'total',
        'note',
        'status',
        'order_type',
        'created_by',
        'financial_year_id'
    ];

    /* user relation */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by', 'id');
    }

    /* user relation */
    public function details()
    {
        return $this->hasMany(\App\Models\OrderDetail::class, 'order_id', 'id');
    }
    
    /* user relation */
    public function addons()
    {
        return $this->hasMany(\App\Models\OrderAddonDetail::class, 'order_id', 'id');
    }

    /**
     * Payments relationship for withSum() eager loading.
     */
    public function payments()
    {
        return $this->hasMany(\App\Models\Payment::class, 'order_id', 'id');
    }

    /**
     * Customer relationship for eager loading.
     */
    public function customer()
    {
        return $this->belongsTo(\App\Models\Customer::class, 'customer_id', 'id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proposition extends Model
{
    use HasFactory;
    protected $table = 'propositions';
    protected $fillable = [
        'type',
        'min',
        'max',
        'price',
        'delivery_company_id'
    ];

    public function deliveryCompany()
    {
        return $this->belongsTo(DeliveryCompany::class, 'delivery_company_id', 'id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product',
        'subtotal',
        'mtoal',
        'balance',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}

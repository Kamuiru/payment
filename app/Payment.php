<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        "transaction_id",
        "transaction_date",
        "amount",
        "phone_number"
    ];
}

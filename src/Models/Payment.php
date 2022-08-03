<?php

namespace Properos\Commerce\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'payable_type',
        'payable_id',
        'type',
        'user_id',
        'customer_email',
        'operation',
        'amount',
        'fee',
        'account_type',
        'account_id',
        'description',
        'transaction_id',
        'auth_code',
        'transaction_status',
        'provider',
        'refund_amount',
        'last_4',
        'ref_transaction_id'
    ];
}

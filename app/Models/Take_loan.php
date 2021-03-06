<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Take_loan extends Model 
{
    protected $fillable = ['loan_pid', 'loan_amount', 'loan_app_date', 'loan_length', 'wallet_key', 'created_at', 'updated_at', 'verified','userincome','repayment_amount','expiration_date'];

    protected $dates = [
        'created_at',
        'updated_at',
        'expiration_date'
    ];

    public function wallet()
    {
        return $this->belongTo('App\Models\Wallet', 'wallet_key');
    }
}

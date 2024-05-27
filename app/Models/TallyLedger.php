<?php

namespace App\Models;

use App\Models\Voucher;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TallyLedger extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function vouchers()
    {
        return $this->hasMany(Voucher::class, 'ledger_guid', 'guid');
    }
}

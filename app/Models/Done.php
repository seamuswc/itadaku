<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Done extends Model
{
    protected $fillable = ['redeem_id', 'nft_id', 'tracking_number'];
}

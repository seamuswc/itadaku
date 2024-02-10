<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mint extends Model
{
    protected $fillable = ['email', 'tracking_number', 'tx_hash', 'nft_id'];
}

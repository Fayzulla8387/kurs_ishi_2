<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sell extends Model
{
    protected $fillable = [
        'whom', 'given_sum', 'all_sum', 'indebtedness',
    ];




    use HasFactory;
}

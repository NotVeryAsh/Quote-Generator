<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuoteUpvote extends Model
{
    protected $fillable = [
        'ip',
        'quote_id'
    ];
}

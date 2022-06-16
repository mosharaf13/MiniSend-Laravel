<?php

namespace App\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    const STATUS_POSTED = 'posted';
    const STATUS_SENT = 'sent';
    const STATUS_FAILED = 'failed';


    protected $guarded = ['id'];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i a',
        'updated_at' => 'datetime:Y-m-d h:i a'
    ];

}
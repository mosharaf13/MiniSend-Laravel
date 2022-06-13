<?php

namespace App\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    const STATUS_POSTED = 'Posted';
    const STATUS_SENT = 'Sent';
    const STATUS_FAILED = 'failed';


    protected $guarded = ['id'];

}
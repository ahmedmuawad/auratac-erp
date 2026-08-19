<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DemoRequest extends Model
{
    protected $fillable = [
        'center_name',
        'contact_name',
        'phone',
        'center_size',
        'status',
    ];
}

<?php

namespace App\Models;

use App\Scopes\StaffUserScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends User
{
    use HasFactory;

    protected $table = 'users';

    protected static function booted()
    {
        parent::booted();
        static::addGlobalScope(new StaffUserScope);
    }
}

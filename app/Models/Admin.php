<?php

namespace App\Models;

use App\Scopes\AdminUserScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends User
{
    use HasFactory;

    protected $table = 'users';

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new AdminUserScope);
    }
}

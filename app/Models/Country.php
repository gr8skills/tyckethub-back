<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    const IS_COVERED_TRUE = 1;
    const IS_COVERED_FALSE = 0;

    protected $guarded = ['id'];
    protected $hidden = [
        'created_at', 'updated_at'
    ];
    public function states()
    {
        return $this->hasMany(State::class, 'country_id');
    }
}

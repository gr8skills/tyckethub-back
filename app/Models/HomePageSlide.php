<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomePageSlide extends Model
{
    use HasFactory;

    protected $table = 'home_page_slides';

    protected $guarded = [
        'id'
    ];
}

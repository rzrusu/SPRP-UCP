<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkinData extends Model
{
    use HasFactory;


    public $timestamps = false;

    public $primaryKey = 'skin_id';
}

<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class AdminRecord extends Model
{
    use HasFactory;

    protected $table = 'admin_record';
    protected $primaryKey = 'record_id';
    public $timestamps = false;

    protected $dates = ['record_date'];

    public function user()
    {
        return $this->belongsTo(User::class, 'account_id', 'account_id');
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Connection extends Model
{
    //use HasFactory;

    public $table = 'sessions';

    public $primaryKey = 'e_session_id';

    protected $fillable = [
        'e_session_acc',
        'e_session_char',
        'e_session_web',
        'e_session_time',
        'e_session_duty',
        'e_session_idle',
        'e_session_ip',
        'e_session_unix_store',
        'e_session_unix_last',
    ];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'account_id', 'e_session_acc');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function answers()
    {
        return [
            $this->answer_1,
            $this->answer_2,
            $this->answer_3,
            $this->answer_4,
        ];
    }

}

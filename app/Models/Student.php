<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'students';
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phoneno',
        'rollno',
        'class',
        'date_of_birth',
        'admission_date',
        'status',
        'user_id',
        'teacher_id',
      ];
}

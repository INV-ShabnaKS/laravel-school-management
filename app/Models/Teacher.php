<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $table = 'teachers';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phoneno',
        'subject',
        'emp_id',
        'date_of_join',
        'status',
        'user_id',
      ];
}

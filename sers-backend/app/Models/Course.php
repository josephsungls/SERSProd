<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $table = 'COURSE';
    protected $primaryKey = 'Course_ID';
    public $incrementing = false;
    public $timestamps = false;
}

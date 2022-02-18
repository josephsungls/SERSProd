<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Propose extends Model
{
    use HasFactory;
    protected $table = 'COURSE_PROPOSED';
    protected $primaryKey = 'CP_ID';
    public $timestamps = false;

}

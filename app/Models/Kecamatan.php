<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    protected $table = 'ms_kecamatan';
    protected $primaryKey = 'id';
    
    use HasFactory;
}

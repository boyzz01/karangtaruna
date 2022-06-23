<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Umkm extends Model
{
    protected $table = 'umkm';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'nama',
        'alamat',
        'bidang',
        'nib',
        'modal',
        'id_user',
        'foto'
    
    ];

    use HasFactory;
}

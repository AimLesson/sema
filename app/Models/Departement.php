<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Departement extends Model
{
    use HasFactory;

    protected $table = 'departement';

    protected $fillable = ['name','organisasi_id'];

    public function organisasi()
    {
        return $this->belongsTo(Organisasi::class);
    }
}

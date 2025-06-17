<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    protected $fillable = ['nama', 'weight', 'sumber'];

    public function nilais()
    {
        return $this->hasMany(Nilai::class);
    }
}

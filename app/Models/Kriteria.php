<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    protected $primaryKey = 'kode_kriteria';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['kode_kriteria', 'nama', 'weight', 'sumber'];

    public function nilais()
    {
        return $this->hasMany(Nilai::class, 'kode_kriteria', 'kode_kriteria');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    protected $fillable = ['tendik_nik', 'kode_kriteria', 'value']; /* Kolom yang ada di table */

    public function tendik()
    {
        return $this->belongsTo(Tendik::class, 'tendik_nik', 'nik');
    }

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class, 'kode_kriteria', 'kode_kriteria');
    }
}

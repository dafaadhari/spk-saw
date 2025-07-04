<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    protected $fillable = ['alternatif_nik', 'kode_kriteria', 'value'];

    public function Alternatif()
    {
        return $this->belongsTo(Alternatif::class, 'alternatif_nik', 'nik');
    }

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class, 'kode_kriteria', 'kode_kriteria');
    }
}

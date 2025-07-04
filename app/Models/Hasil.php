<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hasil extends Model
{
    protected $fillable = ['alternatif_nik', 'final_hasil', 'rank'];

    public function Alternatif()
    {
        return $this->belongsTo(Alternatif::class, 'alternatif_nik', 'nik');
    }
}

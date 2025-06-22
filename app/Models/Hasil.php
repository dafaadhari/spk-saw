<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hasil extends Model
{
    protected $fillable = ['tendik_nik', 'final_hasil', 'rank'];

    public function tendik()
    {
        return $this->belongsTo(Tendik::class, 'tendik_nik', 'nik');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    protected $fillable = ['tendik_id', 'kriteria_id', 'value'];

    public function tendik()
    {
        return $this->belongsTo(Tendik::class);
    }

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class);
    }
}

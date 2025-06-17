<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tendik extends Model
{
    protected $fillable = ['user_id', 'nama', 'nik', 'unit_kerja'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function nilais()
    {
        return $this->hasMany(Nilai::class);
    }

    public function hasil()
    {
        return $this->hasOne(Hasil::class);
    }
}

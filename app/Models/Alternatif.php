<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alternatif extends Model
{
    protected $primaryKey = 'nik';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'user_id',
        'nama',
        'nik',
        'unit_kerja',
        'jenis_pegawai',
        'jam_kerja_tahunan',
        'jam_kerja_bulanan'
    ]; /* Kolom yang ada di table */

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

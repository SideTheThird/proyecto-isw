<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disco extends Model
{
    use HasFactory;
    protected $table = 'discos';
    public $timestamps = false;
    protected $fillable = ['artistas_id','nombre','fecha_lanzamiento','portada'];

    public function artista() {
        return $this->belongsTo(Artista::class);
    }

    public function canciones() {
        return $this->hasMany(Cancion::class);
    }
}

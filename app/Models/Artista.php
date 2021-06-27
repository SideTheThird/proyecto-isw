<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Artista extends Model
{
    use HasFactory;
    protected $table = 'artistas';
    protected $fillable = ['nombre','foto','bio'];
    public $timestamps = false;
    public function discos() {
        return $this->hasMany(Disco::class);
    }
}

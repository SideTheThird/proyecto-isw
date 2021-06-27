<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cancion extends Model
{
    use HasFactory;
    protected $table = 'canciones';
    public $timestamps = false;
    protected $fillable = ['discos_id','nombre','letra','link'];

    public function disco() {
        return $this->belongsTo(Disco::class);
    }
}

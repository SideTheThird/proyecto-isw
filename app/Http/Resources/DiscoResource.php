<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Artista;

class DiscoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        $nombreArtista = Artista::find($this->artistas_id);

        return [
            'id' => $this->id,
            //'artistas_id' => $this->artistas_id,
            'nombre' => $this->nombre,
            'fecha_lanzamiento' => $this->fecha_lanzamiento,
            'artista' => $nombreArtista->nombre,
            'portada' => url($this->portada),
        ];
    }
}

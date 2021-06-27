<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Disco;

class CancionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $nombreDisco = Disco::find($this->discos_id);

        return [
            /* 'id' => $this->id,
            'discos_id' => $this->artistas_id, */
            'nombre' => $this->nombre,
            'disco' => $nombreDisco->nombre,
            'letra' => $this->letra,
            'link' => $this->link,
        ];
    }
}

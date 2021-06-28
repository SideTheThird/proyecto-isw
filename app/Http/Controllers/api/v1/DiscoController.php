<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Disco;
use App\Models\Artista;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\DiscoResource;
use Storage;

class DiscoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return DiscoResource::collection(Disco::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $datos = $request->all();
        Validator::make($request->all(),[
            'artistas_id'=>'required',
            'nombre'=>'required|max:200', 
            'fecha_lanzamiento'=>'required|max:100',
            'portada'=>'required|max:1024|image',
        ])->validate();
        
        /* $portada = $this->subirImagen($request);

        $disco = new Disco();
        $disco->artistas_id = $request->artistas_id;
        $disco->nombre = $request->nombre;
        $disco->fecha_lanzamiento = $request->fecha_lanzamiento;
        $disco->portada = $portada; */
        $disco = new Disco();
        
        $artista = $request->artistas_id;

        $file = $request->file('portada')->store('discos','s3');
        Storage::disk('s3')->setVisibility($file,'public');
        $url = Storage::disk('s3')->url($file);

        $datos['portada'] = $url;
        $datos['artistas_id'] = $artista;
        $disco->fill($datos);
        $res = $disco->save();
        
        if ($res){
            return response()->json(['message' => ' Disco agregado'], 201);
        }

        return response()->json(['message' => ' Disco no agregado'], 500);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $disco = Disco::find($id);
        if($disco == null){
            return response()->json(['Messagge'=>'Dato no encontrado'], 404);
        }
        return new DiscoResource($disco);
    }

    public function discosPorArtista($id)
    {
        $discos = Disco::where('artistas_id',$id)->get();
        if($disco == null){
            return response()->json(['Messagge'=>'Dato no encontrado'], 404);
        }
        return new DiscoResource($disco);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $disco = Disco::find($id);

        if($disco == null){
            return response()->json(['Message'=>'Dato no encontrado'], 404);
        }

        Validator::make($request->all(),[
            'artistas_id' => 'required',
            'nombre'=>'required',
            'fecha_lanzamiento'=>'required',
            'portada'=>'required',
        ])->validate();

        //$disco->artistas_id = $artistas->artistas_id;

        if(!empty($request->input('nombre'))){
            $disco->nombre=$request->input('nombre');
        }
        if(!empty($request->input('fecha_lanzamiento'))){
            $disco->fecha_lanzamiento=$request->input('fecha_lanzamiento');
        }
        if(!empty($request->file('foto'))){
            $disco = $this->subirImagen($request);
            $disco->foto=$archivo;
        } 
        
        
        $res = $disco->save();
        if($res){
            return response()->json(['mensaje'=>'Actualizado']);
        }
        return response()->json(['mensaje'=>'No Actualizado']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $disco = Disco::find($id);
        if($disco == null){
            return response()->json(['Messagge'=>'Dato no encontrado'], 404);
        }
        $res = $disco->delete();
        if($res){
            return response()->json(['mensaje'=>'Disco eliminado']);
        }
    }

    public function __construct() {
        $this->middleware('auth:api',['except'=>['index','show', 'discosPorArtista']]);
    }
}

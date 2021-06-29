<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Disco;
use App\Models\Artista;
use App\Models\Cancion;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\CancionResource;

class CancionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return CancionResource::collection(Cancion::all());
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
            'discos_id'=>'required',
            'nombre'=>'required|max:200', 
            'letra'=>'required|max:2000',
            'link'=>'required|max:1000',
        ])->validate();
        
        $cancion = new Cancion();
        
        $disco = $request->discos_id;
        $datos['artistas_id'] = $disco;
        $cancion->fill($datos);
        $res = $cancion->save();
        
        if ($res){
            return response()->json(['message' => ' Cancion agregada'], 201);
        }

        return response()->json(['message' => ' Cancion no agregada'], 500);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cancion = Cancion::find($id);
        if($cancion == null){
            return response()->json(['Messagge'=>'Dato no encontrado'], 404);
        }
        return new CancionResource($cancion);
    }

    public function cancionesPorDisco($id)
    {
        $canciones = Cancion::where('discos_id',$id)->get();
        if($canciones == null){
            return response()->json(['Messagge'=>'Dato no encontrado'], 404);
        }
        return CancionResource::collection($canciones);
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
        $cancion = Cancion::find($id);

        if($cancion == null){
            return response()->json(['Message'=>'Dato no encontrado'], 404);
        }

        Validator::make($request->all(),[
            'nombre'=>'required',
            'letra'=>'required',
            'link'=>'required',
        ])->validate();

        if(!empty($request->input('nombre'))){
            $cancion->nombre=$request->input('nombre');
        }
        if(!empty($request->input('letra'))){
            $cancion->letra=$request->input('letra');
        }
        if(!empty($request->input('link'))){
            $cancion->link=$request->input('link');
        }
        
        
        
        $res = $cancion->save();
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
        $cancion = Cancion::find($id);
        if($cancion == null){
            return response()->json(['Messagge'=>'Dato no encontrado'], 404);
        }
        $res = $cancion->delete();
        if($res){
            return response()->json(['mensaje'=>'Cancion eliminada.']);
        }
    }
    
    public function __construct() {
        $this->middleware('auth:api',['except'=>['index','show', 'cancionesPorDisco']]);
    }
}

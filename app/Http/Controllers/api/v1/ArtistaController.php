<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Artista;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ArtistaResource;
use Storage;

class ArtistaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        //return Artista::all();
        return ArtistaResource::collection(Artista::all());
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
        //$usuario = Auth::Users();
        $datos = $request->all();

        Validator::make($request->all(),[
            'nombre'=>'required|max:200', 
            'foto'=>'required|max:1024|image',
            'bio'=>'required|max:2000',
        ])->validate();
        
        $file = $request->file('foto')->store('artistas','s3');
        Storage::disk('s3')->setVisibility($file,'public');
        $url = Storage::disk('s3')->url($file);
        $datos['foto'] = $url;
    
        $artista = new Artista();
        $artista->fill($datos);

        $res = $artista->save();
        
        if ($res){
            return response()->json(['message' => ' Artista agregado'], 201);
        }

        return response()->json(['message' => ' Artista no agregado'], 500); 
 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $artista = Artista::find($id);
        if($artista == null){
            return response()->json(['Messagge'=>'Dato no encontrado'], 404);
        }
        return new ArtistaResource($artista);
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
        $artista = Artista::find($id);

        if($artista == null){
            return response()->json(['Message'=>'Dato no encontrado'], 404);
        }

        Validator::make($request->all(),[
            'nombre'=>'required',
            'foto'=>'required',
            'bio'=>'required',
        ])->validate();

        if(!empty($request->input('nombre'))){
            $artista->nombre=$request->input('nombre');
        }
        if(!empty($request->file('foto'))){
            $archivo = $this->subirImagen($request);
            $artista->foto=$archivo;
        } 
        if(!empty($request->input('bio'))){
            $artista->bio=$request->input('bio');
        }
        
        $res = $artista->save();
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
        $artista = Artista::find($id);
        if($artista == null){
            return response()->json(['Messagge'=>'Dato no encontrado'], 404);
        }
        $res = $artista->delete();
        if($res){
            return response()->json(['mensaje'=>'Artista eliminado.']);
        }
    }

    public function __construct() {
        $this->middleware('auth:api',['except'=>['index','show']]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Usuario;
use App\Models\Denuncia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class UsuarioDenunciaController extends Controller
{
    public function index($id){
    try{
        $usuario = Usuario::find($id);
        $denuncia=$usuario->denuncias;
        if($usuario == null)
            throw new \Exception('Registro no encontrado');
        return $denuncia;
    }catch(\Exception $e){
        return response()->json(['type' => 'error', 'message' => $e->getMessage()], 500);
    }
  }


     public function store(Request $request, $id){
      try{
        if(!$request->has('titulo') || !$request->has('descripcion') || !$request->has('latitud') || !$request->has('longitud')){
             throw new \Exception('Se esperaba campos mandatorios');
           }

        $usuario = Usuario::find($id);
        if(!$usuario){
          return response()->json(['type' => 'success', 'message' => 'Usuario no existe'], 404);
        }

        $denuncia= new Denuncia();
        $denuncia->titulo = $request->get('titulo');
        $denuncia->descripcion = $request->get('descripcion');
        $denuncia->ubicacion =$request->get('ubicacion');
        $denuncia->latitud = $request->get('latitud');
        $denuncia->longitud = $request->get('longitud');
        $denuncia->usuario_id = $id;

        if($request->hasFile('imagen') && $request->file('imagen')->isValid()){
          $imagen = $request->file('imagen');
          $filename = $request->file('imagen')->getClientOriginalName();
          Storage::disk('images')->put($filename,  File::get($imagen));
          $denuncia->imagen = $filename;
        }
        $denuncia->save();
        return response()->json(['type' => 'success', 'message' => 'Registro completo'], 200);
      }catch(\Exception $e){
        return response()->json(['type' => 'error', 'message' => $e->getMessage()], 404);
      }
    }

    public function destroy($idUsuario, $idDenuncia){
      try{
        $usuario = Usuario::find($idUsuario);
        if($usuario == null)
          throw new \Exception('Registro no encontrado');

        $denuncia=$usuario->denuncias()->find($idDenuncia);
        if($denuncia == null)
            throw new \Exception('Registro no encontrado');

       if(Storage::disk('images')->exists($denuncia->imagen))
       Storage::disk('images')->delete($denuncia->imagen);
       
        $denuncia->delete();
        return response()->json(['type' => 'success', 'message' => 'Registro eliminado'], 200);
      }catch(\Exception $e){
        return response()->json(['type' => 'error', 'message' => $e->getMessage()], 500);
      }
    } 
}

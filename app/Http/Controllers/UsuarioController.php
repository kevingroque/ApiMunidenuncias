<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Http\Requests;
use App\Models\Usuario;

class UsuarioController extends Controller
{
    public function index(){
    $usuario = Usuario::all();
    return $usuario;
  }
  
  public function show($id){
    try{
        $usuario = Usuario::find($id);
        if($usuario == null)
            throw new \Exception('Registro no encontrado');
        return $usuario;
    }catch(\Exception $e){
        return response()->json(['type' => 'error', 'message' => $e->getMessage()], 500);
    }
  }
  
  public function store(Request $request){
    try{
      if(!$request->has('username') || !$request->has('password') ||
         !$request->has('nombres') || !$request->has('correo')){
        throw new \Exception('Se esperaba campos mandatorios');
      }
      $usuario = new Usuario();
      $usuario->username = $request->get('username');
      $usuario->password = bcrypt($request->get('password'));
      $usuario->nombres = $request->get('nombres');
      $usuario->correo = $request->get('correo');

      if($request->hasFile('imagen') && $request->file('imagen')->isValid()){
        $imagen = $request->file('imagen');
        $filename = $request->file('imagen')->getClientOriginalName();
        Storage::disk('images')->put($filename,  File::get($imagen));
        $usuario ->imagen = $filename;
      }
      $usuario->save();
      return response()->json(['type' => 'success', 'message' => 'Registro completo'], 200);
    }catch(\Exception $e){
      return response()->json(['type' => 'error', 'message' => $e->getMessage()], 404);
    }
  }

     public function destroy($id){
      try{
        $usuario = Usuario::find($id);
        if($usuario == null)
          throw new \Exception('Registro no encontrado');
        if(Storage::disk('images')->exists($usuario->imagen))
        Storage::disk('images')->delete($usuario->imagen);
        $usuario->delete();
        return response()->json(['type' => 'success', 'message' => 'Registro eliminado'], 200);
      }catch(\Exception $e){
        return response()->json(['type' => 'error', 'message' => $e->getMessage()], 500);
      }
    }

}

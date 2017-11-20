<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Denuncia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class DenunciaController extends Controller
{
    public function index(){
    try{
      $denuncia = Denuncia::all();
      if(!$denuncia)
          throw new \Exception('Registro no encontrado');
      return $denuncia;
    }catch(\Exception $e){
      return response()->json(['type' => 'error', 'message' => $e->getMessage()], 500);
    }
  }
  
  //Obtener un inmueble por id
  public function show($id){
    try{
      $denuncia = Denuncia::find($id);
      if($denuncia == null)
          throw new \Exception('Registro no encontrado');
      return $denuncia;
    }catch(\Exception $e){
      return response()->json(['type' => 'error', 'message' => $e->getMessage()], 500);
    }
  }
}

<?php

namespace App\Http\Controllers;

use App\Modelos\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Modelos\CargaArchivo;
use App\Modelos\RoleType;
use Validator;

class CargaArchivoController extends Controller
{

    public function inicio(Request $request){
        return view('carga_archivo.list',[
            'url' => $request->path(),
            'titulo' => ['titulo'=>'Proceso','sub_titulo'=>'Carga de archivos'],
            'usuario' => getParty(session('party_id')),
            'cargaArchivo' => CargaArchivo::all(),
        ]);
    }

    public function addCargaArchivo(Request $request, $idCargaArchivo = null){

        return view('carga_archivo.partials.crear',[
            'url' => $request->path(),
            'titulo' => ['titulo'=>'Proceso','sub_titulo'=>'Crear carga de archivos'],
            'usuario' => getParty(session('party_id')),
            'dataCargaArchivo' => $idCargaArchivo === null ? $idCargaArchivo : CargaArchivo::find($idCargaArchivo),
            'roleType' => RoleType::All(),
            'notificacion' => Notificacion::get()
        ]);

    }

    public function storeCargaArchivo(Request $request){
        $validar = Validator::make($request->all(), [
            'nombre' => 'required',
            'descripcion' => 'required',
            'carpeta' => 'required',
            'usuario' => 'required'
        ]);

        $success = false;
        $msg = '<div class="alert alert-danger" role="alert" style="margin: 0">
                      ha ocurrido un inconveniente al guardar el proceso para cargar documentos, intente nuevamente.!
                </div>';

        if (!$validar->fails()) {

            ($request->has('id_carga_archivo') && !empty($request->get('id_carga_archivo')))
                ?  $objCargaArchivo = CargaArchivo::find($request->id_carga_archivo)
                :  $objCargaArchivo = new CargaArchivo;

            $objCargaArchivo->nombre = $request->nombre;
            $objCargaArchivo->descripcion = $request->descripcion;
            $objCargaArchivo->carpeta = $request->carpeta;
            $objCargaArchivo->role_type_id = $request->usuario;
            $objCargaArchivo->id_notificacion = $request->notificacion != "" ? $request->notificacion : null ;
            $objCargaArchivo->notificacion_doctor = /*$request->notificacion_doctor === "1" ? true :*/ false;
            $objCargaArchivo->solicitud_tratamiento = $request->solicitud_tratamiento === "1" ? true : false;
            $objCargaArchivo->icono = $request->icono;

            if($objCargaArchivo->save()) {
                $success = true;
                $msg = '<div class="alert alert-success" role="alert" style="margin: 0">
                            Se ha guardado el proceso de carga de documento con éxito.!
                        </div>';
            }
        }else{

            $errores = '';
            foreach ($validar->errors()->all() as $error) {
                if ($errores == '') {
                    $errores = '<li>' . $error . '</li>';
                } else {
                    $errores .= '<li>' . $error . '</li>';
                }
            }
            $msg = '<div class="alert alert-danger">' .
                '<p class="text-center">¡Por favor corrija los siguientes errores!</p>' .
                '<ul>' .
                $errores .
                '</ul>' .
                '</div>';
        }
        return [
            'success' =>$success,
            "msg" => $msg
        ];
    }

    public function deleteCargaArchivo(Request $request){
        CargaArchivo::destroy($request->id_carga_archivo);
        $success = true;
        $msg = '<div class="alert alert-success" role="alert" style="margin: 0">
                    Se ha eliminado el proceso de Carga de archivo, éxitosamente.!
                </div>';

        return [
            'success' =>$success,
            "msg" => $msg
        ];
    }
}

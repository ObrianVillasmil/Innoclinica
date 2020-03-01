<?php

namespace App\Http\Controllers;

use App\Modelos\RoleType;
use Illuminate\Http\Request;
use App\Modelos\Tratamiento;
use App\Modelos\DocumentoConsolidado;
use App\Modelos\DocumentoConsolidadoRoleType;
use App\Modelos\CorreoDocumentoConsolidado;
use Validator;

class DocumentoConsolidadoController extends Controller
{
    public function inicio(Request $request){

        return view('documentos_consolidados.list',[
            'url' => $request->path(),
            'titulo' => ['titulo'=>'Documentos consolidado','sub_titulo'=>'Consolidado de documentos'],
            'usuario' => getParty(session('party_id')),
            'tratamientos' => Tratamiento::where('estado',1)->get()
        ]);

    }

    public function configuracionDocumentoConsolidado(Request $request, $idTratamiento){

        $tratamiento = Tratamiento::where([
            ['estado',1],
            ['id_tratamiento',$idTratamiento]
        ])->first();

        return view('documentos_consolidados.partials.configuracion',[
            'url' => $request->path(),
            'titulo' => ['titulo'=>'Configuración de consolidado de documentos','sub_titulo'=>$tratamiento->nombre_tratamiento],
            'usuario' => getParty(session('party_id')),
            'tratamiento' => $tratamiento,
            'roleType' => RoleType::whereNotIn('role_type_id',['REPRESENTANTE_LEGAL','MEDICO_USUARIO','END_USER_CUSTOMER'])->get(),
            'configuracion'=> DocumentoConsolidado::where('id_tratamiento', $request->id_tratamiento)->first()
        ]);
    }

    public function storeConfiguracionDocumentoConsolidado(Request $request){
        //  dd($request->all());
        $validar = Validator::make($request->all(), [
            'id_tratamiento' => 'required',
            'nombre_documento_consolidado' => 'required',
        ],[
           'id_tratamiento.required' => 'No se logró capturar el identificador del tratamiento',
            'nombre_documento_consolidado.required' => 'El campo nombre del proceso es obligatorio',
        ]);

        $success = true;
        $msg = '<div class="alert alert-success" role="alert" style="margin: 0">
                      Se ha guardado con éxito la configuración de documentos consolidados para el tratamiento.!
                </div>';

        if (!$validar->fails()) {

            $continue = true;

            if($continue && isset($request->arrCorreos)){
                $validar1 = Validator::make($request->all(), [
                    'arrCorreos.*' => 'required|email',
                ],[
                    'arrCorreos.*.email' => 'Lo escrito en las casillas creadas para mails debe ser un correo válido',
                    'arrCorreos.*.required' => 'Debe escribir los mails a los que se enviará el correo en las casillas creadas',
                ]);

                if ($validar1->fails()) {
                    $continue = false;
                    $success = false;
                }
            }

            if($continue){

                $documentoConsolidado = DocumentoConsolidado::where('id_tratamiento',$request->id_tratamiento)->first();

                $objDocumentoConsolidado = new DocumentoConsolidado;
                $objDocumentoConsolidado->id_tratamiento = $request->id_tratamiento;
                $objDocumentoConsolidado->nombre = $request->nombre_documento_consolidado;

                if($objDocumentoConsolidado->save()) {
                    $modelDocumentoConsolidado = DocumentoConsolidado::all()->last();
                    $success = true;

                    if (isset($request->arrRoles)){
                        foreach ($request->arrRoles as $rol) {
                            $objDocumentoConsolidadoRoleType = new DocumentoConsolidadoRoleType;
                            $objDocumentoConsolidadoRoleType->id_documento_consolidado = $modelDocumentoConsolidado->id_documento_consolidado;
                            $objDocumentoConsolidadoRoleType->role_type_id = $rol['rol'];
                            $objDocumentoConsolidadoRoleType->correo = $rol['correo'];
                            $objDocumentoConsolidadoRoleType->firma = $rol['firma'];
                            if (!$objDocumentoConsolidadoRoleType->save()) {
                                $success = false;
                                $msg = '<div class="alert alert-danger" role="alert" style="margin: 0">
                                        ha ocurrido un inconveniente al guardar el proceso de documentos consolidados, intente nuevamente.!
                                    </div>';
                                DocumentoConsolidado::destroy($modelDocumentoConsolidado->id_documento_consolidado);
                                return [
                                    'success' =>$success,
                                    "msg" => $msg
                                ];
                            }
                        }
                    }

                    if($success && isset($request->arrCorreos)){
                        foreach ($request->arrCorreos as $correo) {
                            $objCorreoDocumentoConsolidado = new CorreoDocumentoConsolidado;
                            $objCorreoDocumentoConsolidado->id_documento_consolidado = $modelDocumentoConsolidado->id_documento_consolidado;
                            $objCorreoDocumentoConsolidado->correo = $correo;
                            if(!$objCorreoDocumentoConsolidado->save()){
                                $success = false;
                                $msg = '<div class="alert alert-danger" role="alert" style="margin: 0">
                                            ha ocurrido un inconveniente al guardar el proceso de documentos consolidados, intente nuevamente.!
                                        </div>';
                                DocumentoConsolidado::destroy($modelDocumentoConsolidado->id_documento_consolidado);
                                return [
                                    'success' =>$success,
                                    "msg" => $msg
                                ];
                            }
                        }
                    }

                    if(isset($documentoConsolidado))
                        DocumentoConsolidado::destroy($documentoConsolidado->id_documento_consolidado);

                }else{
                    $success = false;
                    $msg = '<div class="alert alert-danger" role="alert" style="margin: 0">
                              ha ocurrido un inconveniente al guardar el proceso de documentos consolidados, intente nuevamente.!
                            </div>';
                }
            }
        }else{
            $success = false;
        }

        if((isset($validar) && $validar->fails()) || (isset($validar1) && $validar1->fails()) ){

            $v = [];
            if(isset($validar) && count($validar->errors()) > 0)
                $v = array_merge($v,$validar->errors()->all());
            if(isset($validar1) && count($validar1->errors()) > 0)
                $v = array_merge($v,$validar1->errors()->all());

            $errores = '';
            foreach ($v as $error) {
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
}

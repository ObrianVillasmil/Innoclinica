<?php

namespace App\Http\Controllers;

use App\Modelos\LogAdministrador;
use App\Modelos\TratamientoSolicitado;
use Illuminate\Http\Request;
use App\Modelos\Person;
use Dompdf\Dompdf;
use App\Modelos\Documento;
use Validator;
use App\Modelos\DocumentoTratamientoSolicitado;
use App\Mail\DocumentosTratamientoSolicitado;
use Illuminate\Support\Facades\Mail;
use App\Modelos\DetalleDistribucionTratamientoDoctor;

class AlertaController extends Controller
{
    public function inicio(Request $request){
        return view('alerta.list',[
            'url' => $request->path(),
            'titulo' => ['titulo'=>'Alertas','sub_titulo'=>'administración de alertas'],
            'usuario' => getParty(session('party_id')),
        ]);
    }

    public function listSesionUsuario(){
        return view('alerta.partials.listados.list_sesion_usuario',[
            'sesion' => LogAdministrador::where([
                ['tabla','user_login'],
                ['estado_notificacion',false]
            ])->orderBy('fecha_registro','desc')->paginate(20)
        ]);
    }

    public function listNotificaciones(){
        return view('alerta.partials.listados.list_notificaciones',[
            'notificacion' => LogAdministrador::where([['tabla','tratamiento_notificacion_segundo_paso'],['estado_notificacion',false]])
                ->orWhere('tabla','cotizacion')->orderBy('fecha_registro','desc')->paginate(20)
        ]);
    }

    public function listSolicitudTratamiento(){
        return view('alerta.partials.listados.list_tratamientos_solicitados',[
            'solicitudTratamiento' => LogAdministrador::where([['tabla','tratamiento_solicitado'],['estado_notificacion',false]])->orderBy('fecha_registro','desc')->paginate(20)
        ]);
    }

    public function formAsignarDoctor(Request $request){

        $person = Person::join('party_role as pr','person.party_id','pr.party_id')
            ->join('role_type as rt','pr.role_type_id','rt.role_type_id')
            ->where('rt.role_type_id','MEDICO_USUARIO')->orderBy('first_name','asc')->get();

        $dataDoctor =[];

        foreach ($person as $p)
            $dataDoctor[] = ['nombre'=> $p->first_name." ".$p->last_name, 'party_id' => $p->party_id];
            //if($p->party->party_role->role_type->role_type_id === "MEDICO_USUARIO")
                //$dataDoctor[] = ['nombre'=> $p->first_name." ".$p->last_name, 'party_id' => $p->party_id];
       // dd($person,$dataDoctor);

        return view('alerta.partials.form_asignar_doctor',[
            'id_tratamiento_solicitado' => $request->id_tratamiento_solicitado,
            'doctores' => $dataDoctor

        ]);
    }

    public function storeAsignarDoctor(Request $request){
        $success = false;
        $msg = '<div class="alert alert-danger" role="alert" style="margin: 0">
                      ha ocurrido un inconveniente asignar el doctor al tratamiento, intente nuevamente.!
                </div>';

        $objTratamientoSolicitado = TratamientoSolicitado::where('id_tratamiento_solicitado',$request->id_tratamiento_solicitado);
        $update = $objTratamientoSolicitado->update(['id_doctor'=>$request->party_id]);

        if($update){
            $success = true;
            $msg = '<div class="alert alert-success" role="alert" style="margin: 0">
                    Se ha asignado el doctor al tratamiento éxitosamente.!
                </div>';
        }
        return [
            'success' =>$success,
            "msg" => $msg
        ];
    }

    public function cuerpoDocumento($idDocumento){

        $pdf = new Dompdf();
        $configuracion = getConfiguracionEmpresa();
        $datos = [
            'NOMBRE_EMPRESA' => $configuracion->nombre_empresa,
            'PAIS_EMPRESA' => $configuracion->pais,
            'ID_EMPRESA' => $configuracion->ruc_empresa,
            'DIREC_EMPRESA' => $configuracion->direccion_empresa,
            'NOMBRE_REP_EMPRESA' => $configuracion->nombre_representante,
            'ID_REP_EMPRESA' => $configuracion->identificacion_representante,
            'TLF_REP_EMPRESA' => $configuracion->telefono_representante,
            'CORREO_REP_EMPRESA' => $configuracion->correo_representante,
            'TEXTO_DOCUMENTO' => Documento::where('id_documento',$idDocumento)->first()->cuerpo
        ];
        $pdf->loadHTML(crearDocumento($datos,false));
        $pdf->render();
        $pdf->stream('archivo.pdf',["Attachment" => false]);

    }

    public function cargarDocumentosSolicitudTratamiento(Request $request){


        $validar = Validator::make($request->all(), [
            'documento.*' => 'required|mimes:pdf,jpg,JPG,JPEG,PNG,png,jpeg',
        ],[
            'documento.mimes'    => 'El archivo a subir debe ser PDF, PNG o JPG',
            /*'documento.max'      => 'El archivo a subir debe no ser mayor a 1MB, archivo subido '.(number_format($request->file('documento.*')->getSize()/1024/1024,2,".","")).' MB',*/
            'documento.required' => 'Debe cargar al menos un archivo'

        ]);

        $success = false;
        $msg = '';

        if (!$validar->fails()) {

            foreach ($request->file('documento') as $doc) {

                $nombreArchivo = rand(1,2000)."_".$doc->getClientOriginalName();
                $objDocumentoTratamientosolicitado = new DocumentoTratamientosolicitado;
                $objDocumentoTratamientosolicitado->id_tratamiento_solicitado = $request->id_tratamiento_solicitado;
                $objDocumentoTratamientosolicitado->nombre = $nombreArchivo;
                //$objDocumentoTratamientosolicitado->icono = $request->icono;
                if($objDocumentoTratamientosolicitado->save()){
                    $success = true;
                    $msg = '<div class="alert alert-success" role="alert" style="margin: 0">
                                Se ha guardado el archivo éxitosamente.!
                            </div>';
                    \Storage::disk('documentos')->put($nombreArchivo, \File::get($doc));
                }else{
                    \Storage::disk('documentos')->delete($nombreArchivo);
                    $success = false;
                    $msg = '<div class="alert alert-danger" role="alert" style="margin: 0">
                              ha ocurrido un inconveniente guardar el archivo.!
                            </div>';

                    return response()->json([
                        "success" =>$success,
                        "msg" => $msg
                    ]);
                }

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

        return response()->json([
            "success" =>$success,
            "msg" => $msg
        ]);

    }

    public function eliminarArchivoDocumentoTratamientoSolicitado(Request $request){
        $success = false;
        $msg = '<div class="alert alert-danger">' .
            '<p class="text-center">Ha ocurrido un error al eliminar el archivo, intente nuevamente</p>' .
            '<ul>';

        $objDocumentoTratamientosolicitado = DocumentoTratamientosolicitado::find($request->id_documento_tratamiento_solicitado);
        \Storage::disk('documentos')->delete($objDocumentoTratamientosolicitado->nombre);

        if($objDocumentoTratamientosolicitado->delete()){
            $success = true;
            $msg = '<div class="alert alert-success">' .
                '<p class="text-center">Se ha eliminado el archivo con éxito</p>' .
                '<ul>';
        }
        return [
            'success' =>$success,
            "msg" => $msg
        ];
    }

    public function enviarCorreoDocumento(Request $request){

        $success = false;
        $msg = '<div class="alert alert-danger" role="alert" style="margin: 0">
                      ha ocurrido un inconveniente al enviar el correo con los archivos adjuntados, intente nuevamente.!
                </div>';

        $validar = Validator::make($request->all(), [
            'asunto' => 'required',
            'mensaje' => 'required'
        ]);

        if (!$validar->fails()) {

            $documentoConsolidado = getDocumentoConsolidadoByIdTratamiento($request->id_tratamiento);
            $correos=[];

            foreach ($documentoConsolidado->correo_documento_solicitado as $correo)
                $correos[] = $correo->correo;

            $achivos = [];

            foreach ($request->arr_archivos as $item) {

                if ($item['ingreso'] === 'cliente')
                    $achivos[] = [
                        'data' => getCargaArchivoClienteById($item['id']),
                        'ingreso' => $item['ingreso']
                    ];

                if ($item['ingreso'] === 'admin')
                    $achivos[] = [
                        'data' =>getDocumento($item['id']),
                        'ingreso' => $item['ingreso']
                    ];

                if ($item['ingreso'] === 'distribucion_tratamiento')
                    $achivos[] = [
                        'data' => $item['id'],
                        'ingreso' => $item['ingreso']
                    ];

                if ($item['ingreso'] === 'otros')
                    $achivos[] = [
                        'data' => getDocumentoTratamientoSolicitadoById($item['id']),
                        'ingreso' => $item['ingreso']
                    ];

            }

            Mail::to($correos[0])
                ->cc($correos)->send(new DocumentosTratamientoSolicitado($achivos,$request->asunto,$request->mensaje));

            $success = true;
            $msg = '<div class="alert alert-success" role="alert" style="margin: 0">
                    Se ha enviado el correo éxitosamente.!
                </div>';

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

    public function storeDetalleDistribucionTratamientoDoctor(Request $request){

        $success = false;
        $msg = '<div class="alert alert-danger" role="alert" style="margin: 0">
                      ha ocurrido un inconveniente al guardar los datos en el sistema, intente nuevamente.!
                </div>';

        $objDetalleDistribucionTratamientoDoctor = DetalleDistribucionTratamientoDoctor::find($request->id_detalle_distribucion_tratamiento_doctor);
        $objDetalleDistribucionTratamientoDoctor->update([
            'sitio_aplicacion' => $request->sitio_aplicacion,
            'comentarios' => $request->comentario,
            'cumplido' => $request->cumplido,
            'fecha_aplicacion_real' => $request->fecha_aplicacion_real,
            'fecha_aplicacion' => $request->fecha_aplicacion,
            'cantidad_aplicacion' => $request->cantidad_aplicacion
        ]);
        if($objDetalleDistribucionTratamientoDoctor->update()){
            $success = true;
            $msg = '<div class="alert alert-success" role="alert" style="margin: 0">
                        Se han guardado los datos con éxito en el sistema.!
                   </div>';
        }
        return [
            'success' =>$success,
            "msg" => $msg
        ];

    }

    public function desactivarNotificacion(Request $request){

        $success = false;
        $msg = '<div class="alert alert-danger" role="alert" style="margin: 0">
                      ha ocurrido un inconveniente al descativar la notificación, intente nuevamente.!
                </div>';

        $objLogAdministrador = LogAdministrador::find($request['id_log_administrador']);
        if($objLogAdministrador->update([
            'estado_notificacion'=>true
        ])){

            $success = true;
            $msg = '<div class="alert alert-success" role="alert" style="margin: 0">
                    Se ha desactivado la notifiación éxitosamente.!
                </div>';

        }
        return [
            'success' =>$success,
            "msg" => $msg
        ];

    }


}

<?php

namespace App\Http\Controllers;

use App\Mail\ErrorMailDoctor;
use App\Mail\NotificacionGeneral;
use App\Mail\NotificacionTratamientoDoctor;
use App\Modelos\CargaArchivo;
use App\Modelos\CargaArchivoCliente;
use App\Modelos\DistribucionTratamientoDoctor;
use App\Modelos\Especialidad;
use App\Modelos\LogAdministrador;
use App\Modelos\Person;
use App\Modelos\Tratamiento;
use App\Modelos\TratamientoSolicitado;
use App\Modelos\UserLogin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use App\Modelos\IntervinienteTratamientoSolicitado;
use App\Modelos\DetalleTratamientoDoctor;
use App\Modelos\DetalleDistribucionTratamientoDoctor;
use App\Modelos\CapturaDatoCliente;
use App\Modelos\PartyRole;
use App\Modelos\Party;
use App\Modelos\ContactMech;
use App\Modelos\TelecomNumber;
use App\Modelos\PartyContactMech;
use App\Modelos\DetalleTratamiento;
use Validator;
use Storage;

class TratamientoClienteController extends Controller
{
    public function inicio(Request $request){

        getParty(session('party_id'))->party_role->role_type->role_type_id === "MEDICO_USUARIO"
            ? $tratamientos = Tratamiento::where([['tratamiento.estado',1],['ts.id_doctor',session('party_id')]])->join('tratamiento_solicitado as ts','tratamiento.id_tratamiento','ts.id_tratamiento')->orderBy('nombre_tratamiento','asc')->get()
            : $tratamientos = Tratamiento::where('estado',1)->orderBy('nombre_tratamiento','asc')->get();

        return view('tratamiento_cliente.list',[
            'url' => $request->path(),
            'titulo' => ['titulo'=>'Tratamientos','sub_titulo'=>'Seleccione su tratamiento'],
            'usuario' => getParty(session('party_id')),
            'tratamientos' => $tratamientos
        ]);
    }

    public function procesoTratamiento(Request $request, $idTratamiento, $idTratamientoSolicitado = null,$partyIdSolicitado = null){

        $dataPasosTratamiento = getTratamiento($idTratamiento);

        $detalleTratamiento = getDetalleTratamiento($idTratamiento);
        $detalleTratamientoDefault = $detalleTratamiento;

        if(isset($idTratamientoSolicitado))
            $detalleTratamiento = getDetalleTratamientoDoctorByIdTratamientoSolicitado($idTratamientoSolicitado);

        $datosCotizacion = isset($detalleTratamiento) ? $detalleTratamiento->datos_cotizacion() : null;

        if(!isset($datosCotizacion))
            $datosCotizacion = isset($detalleTratamientoDefault) ? $detalleTratamientoDefault->datos_cotizacion() : null;

        if(isset($datosCotizacion)){
            $x = 1;
        }else{
            $x = 0;
        }


        $party = getParty(session('party_id'));
        foreach($dataPasosTratamiento->procesos as $a => $p){
            $dataProceso = dataProcesoTratamiento($p);
            //dump($dataProceso);
            if(!isset($dataProceso['role_type_id'])){
                $dataProceso->put('role_type_id', "MEDICO_USUARIO");
            }
                //dump($dataProceso['role_type_id'] ,$party->party_role->role_type_id);
            if($party->party_role->role_type->role_type_id === "REPRESENTANTE_LEGAL" || $party->party_role->role_type->role_type_id === "END_USER_CUSTOMER"){
                $arrRol = ["REPRESENTANTE_LEGAL","END_USER_CUSTOMER"];
            }else{
                $arrRol = [$party->party_role->role_type->role_type_id];
            }

            if((!isset($dataProceso['role_type_id']) && isset($dataProceso['id_especialidad'])) || (in_array($dataProceso['role_type_id'],$arrRol))){
                $x++;
            }

        }



        return view('tratamiento_cliente.procesos_tratamiento',[
            'url' => $request->path(),
            'titulo' => ['titulo'=>'Tratamiento','sub_titulo'=> $dataPasosTratamiento->nombre_tratamiento." ".(isset($partyIdSolicitado) ? " ,Solicitado por ".getParty($partyIdSolicitado)->person->first_name." ".getParty($partyIdSolicitado)->person->last_name :" ".'')],
            'usuario' => getParty(session('party_id')),
            'tratamiento' => $dataPasosTratamiento,
            'procesos' => $dataPasosTratamiento->procesos,
            'idTratamientoSolicitado' =>$idTratamientoSolicitado,
            'idTratamiento' => $idTratamiento,
            'partyIdSolicitante' => $partyIdSolicitado,
            'procesosByUser' => $x,
            'prodcuto' => isset($datosCotizacion)  ? $datosCotizacion['producto'] : null,
            'cotiza' => 'tratamiento_cliente'
        ]);
    }

    public function storeArchivoCliente(Request $request){

        $validar = Validator::make($request->all(), [
            'archivo.*' => 'mimes:pdf,jpg,JPG,JPEG,PNG,png,jpeg|max:1024',
            'carpeta.*' => 'required'
        ],[
            'archivo.*.mimes' => 'El archivo a subir debe ser PDF, PNG o JPG',
            'archivo.*.max' => 'El archivo a subir debe no ser mayor a 1MB, archivo subido '/*.(number_format($request->file('archivo')->getSize()/1024/1024,2,".","")).' MB'*/,

        ]);

        $success = false;
        $msg = '';

        if (!$validar->fails()) {

            if(count(explode(")", $request->doctor)) > 1){

                if($request->has("doctor") && isset(explode(")", $request->doctor)[0]))
                    $partyIdDoctor = explode(")", $request->doctor)[0];

                if(isset($partyIdDoctor)){
                    foreach(getParty($partyIdDoctor)->party_contact_mech as $pcm){
                        if($pcm->contact_mech->contact_mech_type_id === "EMAIL_ADDRESS"){
                            $mailDoctor = $pcm->contact_mech->info_string;
                        }

                    }

                }

                if(!isset($mailDoctor)){
                    //getConfiguracionEmpresa()->correo_empresa
                    Mail::to(getConfiguracionEmpresa()->correo_empresa)->send(new ErrorMailDoctor($request->doctor));
                    return [
                        'success' =>false,
                        "msg" => '<div class="alert alert-danger" role="alert" style="margin: 0">
                                    El doctor '.explode(")", $request->doctor)[1].' no tiene un correo configurado para notificarlo del tratamiento que solicita, un correo fue enviado a la gerencia de '.getConfiguracionEmpresa()->nombre_empresa.' para solucionar el inconveniente y nuestro contrataciones se contactará en breve con usted para que realice con éxito su solicitud.!
                                  </div>'
                    ];
                }else{
                    if(getCargaArchivo($request->id_carga_archivo)->notificacion_doctor)
                        Mail::to($mailDoctor)->send(new NotificacionTratamientoDoctor((int)session('party_id')));
                }

                if(getUserLogin($partyIdDoctor) == null){
                    $objUserLogin = new UserLogin;
                    $objUserLogin->user_login_id = $partyIdDoctor;
                    $objUserLogin->current_password = "{SHA}".sha1("123456");
                    $objUserLogin->enabled = "N";
                    $objUserLogin->email = $mailDoctor;
                    $objUserLogin->token = hash("sha512", $objUserLogin->current_password . now()->toDateString() . 'Innofarm');;
                    $objUserLogin->party_id = $partyIdDoctor;
                    if(!$objUserLogin->save()){
                        $success = false;
                        $msg .= '<div class="alert alert-danger" role="alert" style="margin: 0">
                                Ha ocurrido un error al procesar la solicitud, intente nuevamente        
                            </div>';
                    }else{
                        crear_log('user_login',$objUserLogin->user_login_id,(int)session('party_id'),"Se ha creado una nueva cuenta para el doctor ". getParty($partyIdDoctor)->person->first_name. " " .getParty($partyIdDoctor)->person->last_name." con el usuario ". $mailDoctor ." y la contraseña 12345, esta puede ser cambiada desde la sección de Usuarios");
                    }
                }

            }

            $party = getParty(session('party_id'));
                                                                                                                                                                    // ROL AGREGADO SOLO PARA PRUEBAS
            if($party->party_role->role_type->role_type_id == "END_USER_CUSTOMER" || $party->party_role->role_type->role_type_id == "REPRESENTANTE_LEGAL" || $party->party_role->role_type->role_type_id == "ADMIN"){
                $tratamientoSolicitado = getSolicitudTratamiento($request->id_tratamiento,$party->party_id);
                $partyId = $party->party_id;
                if(!isset($tratamientoSolicitado)){
                    setSolicitudTratamiento($request->id_tratamiento,$request->paso);
                }else{
                    updateSolicitudTratamiento($tratamientoSolicitado->id_tratamiento_solicitado,$request->paso);
                }
            }else{
                $partyId = $request->partyIdSolicitante;
            }

                       //$file = $request->file('archivo');



            $tratamientoSolicitado = getSolicitudTratamiento($request->id_tratamiento,$partyId);

            if($party->party_role->role_type->role_type_id !== "END_USER_CUSTOMER" && $party->party_role->role_type->role_type_id !== "REPRESENTANTE_LEGAL" && $party->party_role->role_type->role_type_id !== "ADMIN"){

                $objIntervinienteTratamientoSolicitado = getIntervinienteTratamiento($request->id_tratamiento);

                setIntervinienteTratamiento($objIntervinienteTratamientoSolicitado, $tratamientoSolicitado->id_tratamiento_solicitado, $request->paso);

            }

            $x=0;
            foreach ($request->file('archivo') as $file) {
                $nombreArchivo = mt_rand().$file->getClientOriginalName();
                \Storage::disk('archivos')->put($request->carpeta."/".$nombreArchivo, \File($file));

                $objCargaArchivoCliente = new CargaArchivoCliente;
                $objCargaArchivoCliente->id_tratamiento_solicitado = $tratamientoSolicitado->id_tratamiento_solicitado;
                $objCargaArchivoCliente->archivo = $nombreArchivo;
                $objCargaArchivoCliente->carpeta = $request->carpeta;
                $objCargaArchivoCliente->id_carga_archivo = $request->id_carga_archivo;
                if ($objCargaArchivoCliente->save()) $x++;
            }

            if(count($request->file('archivo')) === $x){
                $objTratamientoSolicitado = TratamientoSolicitado::where([['id_tratamiento',$request->id_tratamiento],['party_id',session('party_id')],['estado',1]]);
                if($objTratamientoSolicitado->first() != null && ($objTratamientoSolicitado->first()->proceso_actual < $request->paso))
                    $objTratamientoSolicitado->update(['proceso_actual'=>$request->paso]);

                $log = crear_log("tratamiento_notificacion", $request->id_tratamiento, session('party_id'),"El ".$party->party_role->role_type->description." ".getParty(session('party_id'))->person->first_name." ".getParty(session('party_id'))->person->last_name ." ha cargado un archivo para el tratamiento". getTratamiento($request->id_tratamiento)->nombre_tratamiento);
                if($log){
                    $success = true;
                    $msg = '<div class="alert alert-success" role="alert" style="margin: 0">
                               Se ha cargado su archivo con éxito.!
                           </div>';
                }
            }

            if(isset($request->correo_doctor) && $request->correo_doctor!="undefined"){
                $mensaje = "<div style='padding: 30px;background: #33b35a;color:white'>
<<<<<<< HEAD
                                Hola Dr. ".$request->doctor." su cliente ".getParty((int)session('party_id'))->person->first_name." ".getParty((int)session('party_id'))->person->last_name." ha solicitado un tratamiendo en ".getConfiguracionEmpresa()->nombre_empresa.", nuestro personal se contactará con usted los mas brevemente posible
=======
                                Hola Dr. ".$request->doctor." su cliente ".getParty((int)session('party_id'))->person->first_name." ".getParty((int)session('party_id'))->person->last_name." ha solicitado un tratamiendo en ".getConfiguracionEmpresa()->nombre_empresa.", nuestro contrataciones se contactará con usted los mas brevemente posible
>>>>>>> b33f1bba520636fe4938396469f8d02c7ff1f642
                            </div>";

                Mail::to($request->correo_doctor)
                    ->cc(getConfiguracionEmpresa()->correo_empresa)->send(new NotificacionGeneral($mensaje));
            }

            if($request->tlf_doctor != null && $request->tlf_doctor != "undefined" && isset($request->doctor)){
                mensajeTexto()->message()->send([
                    'to' => '593983537432',//'593'.$request->tlf_doctor
                    'from' => getConfiguracionEmpresa()->nombre_empresa,
<<<<<<< HEAD
                    'text' => "Hola Dr. ".$request->doctor." su paciente ".getParty((int)session('party_id'))->person->first_name." ".getParty((int)session('party_id'))->person->last_name." ha solicitado un tratamiendo en ".getConfiguracionEmpresa()->nombre_empresa.", nuestro personal se contactará con usted los mas brevemente posible"
=======
                    'text' => "Hola Dr. ".$request->doctor." su paciente ".getParty((int)session('party_id'))->person->first_name." ".getParty((int)session('party_id'))->person->last_name." ha solicitado un tratamiendo en ".getConfiguracionEmpresa()->nombre_empresa.", nuestro contrataciones se contactará con usted los mas brevemente posible"
>>>>>>> b33f1bba520636fe4938396469f8d02c7ff1f642
                ]);
            }

            if(isset($request->id_notificacion)){

                $notificacion = getNotificacion($request->id_notificacion);
                $person = Person::get();

                if (isset($notificacion->tipo_notificacion) && $notificacion->tipo_notificacion->id_tipo_notificacion == 1) { //MAIL
                    $otros = [];

                    foreach ($person as $per) {

                        if ($notificacion->administrador) {
                            if(isset($per->party_role->role_type_id))
                                if ($per->party_role->role_type_id == "ADMIN")
                                    foreach ($per->party->party_contact_mech as $pcm)
                                        if ($pcm->contact_mech->contact_mech_type_id == "EMAIL_ADDRESS")
                                            $otros[] = $pcm->contact_mech->info_string;

                        } elseif ($notificacion->representante_legal || $notificacion->paciente) {

                            if ($per->party_id == (int)session('party_id'))
                                foreach ($per->party->party_contact_mech as $pcm)
                                    if ($pcm->contact_mech->contact_mech_type_id == "EMAIL_ADDRESS")
                                        $otros[] = $pcm->contact_mech->info_string;
                        }
                    }

                    if ($notificacion->otros_notificacion != null)
                        foreach ($notificacion->otros_notificacion as $otrosNotificacion)
                            $otros[] = $otrosNotificacion->texto;

                    Mail::to($otros[0])
                        ->cc($otros)->send(new NotificacionGeneral($notificacion->mensaje));

                } elseif (isset($notificacion->tipo_notificacion) && $notificacion->tipo_notificacion->id_tipo_notificacion == 2) { // MENSAJE DE TEXTO

                    $numeros = [];
                    foreach ($person as $per) {
                        if ($notificacion->administrador) {
                            if(isset($per->party_role->role_type_id)) {
                                if ($per->party_role->role_type_id == "ADMIN") {

                                    foreach ($per->party->party_contact_mech as $pcm)
                                        if ($pcm->contact_mech->contact_mech_type_id == "TELECOM_NUMBER")
                                            $numeros[] = $pcm->contact_mech->telecom_number->country_code . $pcm->contact_mech->telecom_number->contact_number;

                                } elseif ($notificacion->representante_legal || $notificacion->paciente) {
                                    if ($per->party_id == (int)session('party_id'))
                                        foreach ($per->party->party_contact_mech as $pcm)
                                            if ($pcm->contact_mech->contact_mech_type_id == "TELECOM_NUMBER")
                                                $numeros[] = $pcm->contact_mech->telecom_number->country_code.$pcm->contact_mech->telecom_number->contact_number;

                                }
                            }
                        }
                    }

                    /*if ($notificacion->otros_notificacion != null)
                        foreach ($notificacion->otros_notificacion as $otrosNotificacion)
                            $numeros[] = $otrosNotificacion->texto;

                    foreach ($numeros as $num) {
                        mensajeTexto()->message()->send([
                            'to' => '593983537432',//$num
                            'from' => getConfiguracionEmpresa()->nombre_empresa,
                            'text' => $notificacion->mensaje
                        ]);
                    }*/
                }

                $success = true;
                $msg .= '<div class="alert alert-success" role="alert" style="margin: 0">
<<<<<<< HEAD
                                Se ha notificado a su médico de cabecera y a '.getConfiguracionEmpresa()->nombre_empresa .' de la solicitud de su tratamiento, muy pronto el personal de '.getConfiguracionEmpresa()->nombre_empresa .' se contactará con usted.!
=======
                                Se ha notificado a su médico de cabecera y a '.getConfiguracionEmpresa()->nombre_empresa .' de la solicitud de su tratamiento, muy pronto el contrataciones de '.getConfiguracionEmpresa()->nombre_empresa .' se contactará con usted.!
>>>>>>> b33f1bba520636fe4938396469f8d02c7ff1f642
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

    public function storeNotificacionTratamiento(Request $request){

        $logAdministrador = LogAdministrador::where([
            ['tabla', 'tratamiento_notificacion_segundo_paso'],
            ['id_registro_tabla', $request->id_tratamiento],
            ['id_usuario', session('party_id')]
        ])->first();

        if($logAdministrador !== null){
            $log = 0;
        }else{
            $log = crear_log("tratamiento_notificacion_segundo_paso", $request->id_tratamiento, session('party_id'),"El usuario ".getParty(session('party_id'))->person->first_name." ".getParty(session('party_id'))->person->last_name ." ha mostrado interés sobre el tratamiento ". getTratamiento($request->id_tratamiento)->nombre_tratamiento);
        }
        return $log;

    }

    public function deleteArchivoTratamiento(Request $request){

        $success = false;
        $msg = '<div class="alert alert-danger">' .
                    '<p class="text-center">Ha ocurrido un error al eliminar el archivo, intente nuevamente</p>' .
                '<ul>';

        $objCargaArchivoCliente = CargaArchivoCliente::where([
           ['carpeta',$request->carpeta],
           ['archivo',$request->archivo]
        ]);

        if($objCargaArchivoCliente->delete()){

            Storage::disk('archivos')->delete($request->carpeta."/".$request->archivo);
            $success = true;
            $msg = '<div class="alert alert-success">' .
                '<p class="text-center">Se ha eliminado el archivo, por favor cargue el nuevo archivo para el tratamiento</p>' .
                '<ul>';
        }
        return [
            'success' =>$success,
            "msg" => $msg
        ];
    }

    public function storeDistribucionTratamientoDoctor(Request $request){

        $validar = Validator::make($request->all(), [
            'idTratamiento' => 'required',
            'partyIdSolicitante' => 'required',
            'cie10'=> 'required',
            'descripcion_patologica' => 'required'
        ],[
            'idTratamiento.required' => 'No se ha seleccionado el tratamiento',
            'partyIdSolicitante.required' => 'No se ha seleccionado el paciente para el tratamiento',
            'cie10.required' => 'Debe seleccionar una enfermedad de la lista disponible del CIE-10',
            'descripcion_patologica.required' => 'Debe escribir la descripción patológica del tratamiento'
        ]);

        $success = false;
        $msg = '';

        if (!$validar->fails()) {

            $tratamientoSolicitado = getTratamientoSolicitado($request->idTratamiento,$request->partyIdSolicitante);

            $success = false;
            $msg = '<div class="alert alert-danger" role="alert" style="margin: 0">
                       Ha ocurrido un error inesperado al guardar la distribución del tratamiento, intente nuevamente.!
                    </div>';

            $obj = DetalleTratamientoDoctor::where('id_tratamiento_solicitado',$tratamientoSolicitado->id_tratamiento_solicitado)->first();
            if(isset($obj))
                $obj->delete();

            $objDetalleTratamientoDoctor = new DetalleTratamientoDoctor;
            $objDetalleTratamientoDoctor->id_tratamiento_solicitado = $tratamientoSolicitado->id_tratamiento_solicitado;
            $objDetalleTratamientoDoctor->id_cie10 = $request->cie10;
            $objDetalleTratamientoDoctor->justificacion_medica = $request->justificacion;
            $objDetalleTratamientoDoctor->descripcion_patologica = $request->descripcion_patologica;

            if($objDetalleTratamientoDoctor->save()){
                $modelDetalleTratamientoDoctor = DetalleTratamientoDoctor::All()->last();

                $party = getParty(session('party_id'));

                if($party->party_role->role_type->role_type_id !== "END_USER_CUSTOMER" && $party->party_role->role_type->role_type_id !== "ADMIN")
                    $partyId = $request->partyIdSolicitante;
                else
                    $partyId = session('party_id');

                $tratSolic = TratamientoSolicitado::where([['id_tratamiento',$request->idTratamiento],['party_id',$partyId],['estado',1]])->first();

                if($party->party_role->role_type->role_type_id !== "END_USER_CUSTOMER" && $party->party_role->role_type->role_type_id !== "REPRESENTANTE_LEGAL" && $party->party_role->role_type->role_type_id !== "ADMIN"){

                    $objIntervinienteTratamientoSolicitado = IntervinienteTratamientoSolicitado::where([
                        ['id_tratamiento_solicitado',$tratSolic->id_tratamiento_solicitado],
                        ['party_id',session('party_id')]]);

                    if($objIntervinienteTratamientoSolicitado->first() == null){
                        $objTratamientoSolicitado = new IntervinienteTratamientoSolicitado;
                        $objTratamientoSolicitado->id_tratamiento_solicitado = $tratSolic->id_tratamiento_solicitado;
                        $objTratamientoSolicitado->party_id = session('party_id');
                        $objTratamientoSolicitado->proceso_actual = (int)$request->paso;
                        $objTratamientoSolicitado->save();
                    }else{
                        if($objIntervinienteTratamientoSolicitado->first()->proceso_actual < (int)$request->paso)
                            $objIntervinienteTratamientoSolicitado->update(['proceso_actual'=> $request->paso]);
                    }

                }


                foreach ($request->distribucion_tratamiento as $x => $distribucion_tratamiento_doctor) {
                    $objDistribucionTratamientoDoctor = new DistribucionTratamientoDoctor;
                    $objDistribucionTratamientoDoctor->id_detalle_tratamiento_doctor = $modelDetalleTratamientoDoctor->id_detalle_tratamiento_doctor;
                    $objDistribucionTratamientoDoctor->intervalo = $distribucion_tratamiento_doctor['intervalo'];
                    $objDistribucionTratamientoDoctor->cantidad_intervalo = $distribucion_tratamiento_doctor['cantidad_intervalo'];
                    $objDistribucionTratamientoDoctor->cantidad_aplicacion = $distribucion_tratamiento_doctor['cantidad_aplicacion'];

                    if($objDistribucionTratamientoDoctor->save()){
                        $modelDistribucionTratamientoDoctor = DistribucionTratamientoDoctor::All()->last();
                        foreach ($request->distribucionTratamientoDoctor[$x] as $detalleDistribucionTratamientoDoctor) {
                            foreach ($detalleDistribucionTratamientoDoctor as $fases) {
                                //foreach ($fases as $fila) {
                                    $objDetalleDistribucionTratamientoDoctor = new DetalleDistribucionTratamientoDoctor;
                                    $objDetalleDistribucionTratamientoDoctor->id_distribucion_tratamiento_doctor = $modelDistribucionTratamientoDoctor->id_distribucion_tratamiento_doctor;
                                    $objDetalleDistribucionTratamientoDoctor->intervalo = $fases['intervalo_aplicacion'];
                                    $objDetalleDistribucionTratamientoDoctor->cantidad_intervalo = $fases['cantidad_intervalo'];
                                    $objDetalleDistribucionTratamientoDoctor->cantidad_aplicacion = $fases['cantidad'];
                                    $objDetalleDistribucionTratamientoDoctor->product_id = $fases['producto'];

                                    if($objDetalleDistribucionTratamientoDoctor->save()){
                                        $success = true;
                                        $msg = '<div class="alert alert-success" role="alert" style="margin: 0">
                                                    Se ha guardado la distribución con éxito.!
                                                </div>';
                                    }else{
                                        DetalleTratamientoDoctor::destroy($modelDetalleTratamientoDoctor->id_detalle_tratamiento_doctor);
                                    }

                                //}

                            }

                        }

                    }else{
                        DetalleTratamientoDoctor::destroy($modelDetalleTratamientoDoctor->id_detalle_tratamiento_doctor);
                    }
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
        return [
            'success' =>$success,
            "msg" => $msg
        ];

    }

    public function storeDistribucionTratamientoSeguimiento(Request $request){

        $validar = Validator::make($request->all(), [
            'idTratamiento' => 'required',
            'partyIdSolicitante' => 'required',
            'cie10'=> 'required',
            'descripcion_patologica' => 'required'
        ],[
            'idTratamiento.required' => 'No se ha seleccionado el tratamiento',
            'partyIdSolicitante.required' => 'No se ha seleccionado el paciente para el tratamiento',
            'cie10.required' => 'Debe seleccionar una enfermedad de la lista disponible del CIE-10',
            'descripcion_patologica.required' => 'Debe escribir la descripción patológica del tratamiento'
        ]);

        //dd($validar->errors()->all());
        $success = false;
        $msg = '';

        if (!$validar->fails()) {

            $fechaAnterior = "";
            $ultimaFechaAnterior = "";
            $solicitante = getParty($request->partyIdSolicitante);

            $objDetalleTratamientoDoctor = DetalleTratamientoDoctor::where('id_tratamiento_solicitado',$solicitante->tratamiento_solicitado()->id_tratamiento_solicitado);

            if(isset($objDetalleTratamientoDoctor))
                $objDetalleTratamientoDoctor->delete();

            $tratamiento = getTratamiento($request->idTratamiento);
            //$calculoDistribucionTratamiento = $tratamiento->distribucion_tratamiento[0]->calculo_intervalo;

            $tratamientoSolicitado = getTratamientoSolicitado($request->idTratamiento, $request->partyIdSolicitante);

            $objDetalleTratamientoDoctor = new DetalleTratamientoDoctor;
            $objDetalleTratamientoDoctor->id_tratamiento_solicitado = $tratamientoSolicitado->id_tratamiento_solicitado;
            $objDetalleTratamientoDoctor->id_cie10 = $request->cie10;
            $objDetalleTratamientoDoctor->justificacion_medica = $request->justificacion;
            $objDetalleTratamientoDoctor->descripcion_patologica = $request->descripcion_patologica;

            if ($objDetalleTratamientoDoctor->save()) {
                $modelDetalleTratamientoDoctor = DetalleTratamientoDoctor::All()->last();

                foreach ($request->distribucion_tratamiento as $x => $distribucion_tratamiento_doctor) { // X == 0 Es el comienzo de la primera fase
                    $w = 0;
                    $objDistribucionTratamientoDoctor = new DistribucionTratamientoDoctor;
                    $objDistribucionTratamientoDoctor->id_detalle_tratamiento_doctor = $modelDetalleTratamientoDoctor->id_detalle_tratamiento_doctor;
                    $objDistribucionTratamientoDoctor->intervalo = $distribucion_tratamiento_doctor['intervalo'];
                    $objDistribucionTratamientoDoctor->cantidad_intervalo = $distribucion_tratamiento_doctor['cantidad_intervalo'];
                    $objDistribucionTratamientoDoctor->cantidad_aplicacion = $distribucion_tratamiento_doctor['cantidad_aplicacion'];

                    switch ($distribucion_tratamiento_doctor['intervalo']) {
                        case 1:
                            $faseIntervaloDistribucion = 1;
                            break;
                        case 2:
                            $faseIntervaloDistribucion = 7;

                            break;
                        case 3:
                            $faseIntervaloDistribucion = 30;
                            break;
                    }

                    if ($objDistribucionTratamientoDoctor->save()) {

                        $modelDistribucionTratamientoDoctor = DistribucionTratamientoDoctor::All()->last();

                        foreach ($request->distribucionTratamientoDoctor[$x] as $y => $detalleDistribucionTratamientoDoctor) { // Y == 0 es el inicio de una fase
                            //dump($y,$detalleDistribucionTratamientoDoctor);
                            foreach ($detalleDistribucionTratamientoDoctor as $z=> $fases) { // Z == 0 Es la primera aplicacion de cada fase
                                $objDetalleDistribucionTratamientoDoctor = new DetalleDistribucionTratamientoDoctor;
                                $objDetalleDistribucionTratamientoDoctor->id_distribucion_tratamiento_doctor = $modelDistribucionTratamientoDoctor->id_distribucion_tratamiento_doctor;
                                $objDetalleDistribucionTratamientoDoctor->intervalo = $fases['intervalo_aplicacion'];
                                $objDetalleDistribucionTratamientoDoctor->cantidad_intervalo = $fases['cantidad_intervalo'];
                                $objDetalleDistribucionTratamientoDoctor->cantidad_aplicacion = $fases['cantidad'];
                                $objDetalleDistribucionTratamientoDoctor->product_id = $fases['producto'];

                                switch ($fases['intervalo_aplicacion']) {
                                    case 1:
                                        $intervaloDistribucion = 1;
                                        break;
                                    case 2:
                                        $intervaloDistribucion = 7;
                                        break;
                                    case 3:
                                        $intervaloDistribucion = 30;
                                        break;
                                }

                                //CUANDO Y == 0 EL CALCULO DE LA FECHA SE REINICIA

                                if(isset($fechaInicial) && $y == 1 && $z == 0)
                                    $fechaAnterior = $fechaInicial;

                                if (($x == 0 && $y == 0 && $z == 0) || ($x == 0 && ($y==1 && $z==0) || ($y==2 && $z == 0))){
                                    $fechaAnterior = $tratamientoSolicitado->fecha_inicio;
                                }else{

                                    $dias = $intervaloDistribucion * $fases['cantidad_intervalo']; // 7 Días

                                    if($z==0 && $y>0)
                                        $dias =0;

                                    if ($w == 0)
                                        $dias = $dias + $faseIntervaloDistribucion * $distribucion_tratamiento_doctor['cantidad_intervalo'];

                                    if ($tratamiento->detalle_tratamiento->calculo_intervalo && ($y == 0 || $y == 1 || $y == 2))
                                        $fechaAnterior = $tratamientoSolicitado->fecha_inicio;

                                    $fechaAnterior = Carbon::parse($fechaAnterior)->addDays($dias)->toDateString();

                                    $w++;

                                }

                                if($y == 0 && $z == 0)
                                     $fechaInicial = $fechaAnterior;

                                $objDetalleDistribucionTratamientoDoctor->fecha_aplicacion = $fechaAnterior;


                                if ($objDetalleDistribucionTratamientoDoctor->save()) {
                                    $success = true;
                                    $msg = '<div class="alert alert-success" role="alert" style="margin: 0">
                                                    Se ha guardado la distribución con éxito.!
                                                </div>';
                                } else {
                                    DetalleTratamientoDoctor::destroy($modelDetalleTratamientoDoctor->id_detalle_tratamiento_doctor);
                                }
                            }


                        }

                    }

                    /*foreach ($request->distribucionTratamientoSeguimiento as $x => $disTraSeg) {
                        $z=0;
                        $objDistribucionTratamientoDoctor = new DistribucionTratamientoDoctor;
                        $objDistribucionTratamientoDoctor->id_tratamiento = $request->idTratamiento;
                        $objDistribucionTratamientoDoctor->party_id_doctor = $idDoctor;
                        $objDistribucionTratamientoDoctor->party_id_solicitante = $request->partyIdSolicitante;
                        $objDistribucionTratamientoDoctor->id_cie10 = $request->cie10;
                        $objDistribucionTratamientoDoctor->descripcion_patologica = $request->descripcion_patologica;
                        $objDistribucionTratamientoDoctor->justificacion_medica = $request->justificacion;
                        $objDistribucionTratamientoDoctor->intervalo = $disTraSeg['intervalo'];
                        $objDistribucionTratamientoDoctor->cantidad_intervalo = $disTraSeg['cantidad_intervalo'];
                        $objDistribucionTratamientoDoctor->cantidad_aplicacion = $disTraSeg['cantidad_aplicacion'];
                        $objDistribucionTratamientoDoctor->product_id = $request->id_producto;

                        switch ($disTraSeg['intervalo']){
                            case 1:
                                $faseIntervaloDistribucion = 1;
                                break;
                            case 2:
                                $faseIntervaloDistribucion = 7;

                                break;
                            case 3:
                                $faseIntervaloDistribucion = 30;
                                break;
                        }

                        if ($objDistribucionTratamientoDoctor->save()) {

                            $modelDistribucionTratamientoDoctor = DistribucionTratamientoDoctor::all()->last();

                            foreach ($request->detalleDistribucionTratamientoSeguimiento[$x] as $y => $detDistTraSeg){

                                $objDetalleDistribucionTratamientoDoctor = new DetalleDistribucionTratamientoDoctor;
                                $objDetalleDistribucionTratamientoDoctor->id_distribucion_tratamiento_doctor = $modelDistribucionTratamientoDoctor->id_distribucion_tratamiento_doctor;
                                $objDetalleDistribucionTratamientoDoctor->cumplido = $detDistTraSeg['cumplido'];
                                $objDetalleDistribucionTratamientoDoctor->intervalo = $detDistTraSeg['intevalo_aplicacion'];
                                $objDetalleDistribucionTratamientoDoctor->cantidad_intervalo = $detDistTraSeg['cantidad_intervalo'];
                                $objDetalleDistribucionTratamientoDoctor->cantidad_aplicacion = $detDistTraSeg['dosis'];

                                switch ($detDistTraSeg['intevalo_aplicacion']){
                                    case 1:
                                        $intervaloDistribucion = 1;
                                        break;
                                    case 2:
                                        $intervaloDistribucion = 7;
                                        break;
                                    case 3:
                                        $intervaloDistribucion = 30;
                                        break;
                                }

                                if($x == 0 && $y == 0){
                                    $fechaAnterior = $tratamientoSolicitado->fecha_inicio;
                                }else{
                                    $dias = $intervaloDistribucion*$detDistTraSeg['cantidad_intervalo'];

                                    if($z==0)
                                        $dias = $dias + $faseIntervaloDistribucion * $disTraSeg['cantidad_intervalo'];

                                    if($tratamiento->distribucion_tratamiento[0]->calculo_intervalo && $y ==0)
                                        $fechaAnterior = $tratamientoSolicitado->fecha_inicio;

                                        $fechaAnterior = Carbon::parse($fechaAnterior)->addDays($dias)->toDateString();
                                    }

                                $objDetalleDistribucionTratamientoDoctor->fecha_aplicacion = $fechaAnterior;

                                if(!$objDetalleDistribucionTratamientoDoctor->save()){

                                    $success = false;
                                    $msg = '<div class="alert alert-danger" role="alert" style="margin: 0">
                                                Ha ocurrido un error inesperado al guardar la distribución del tratamiento, intente nuevamente.!
                                            </div>';
                                }else{
                                    $success = true;
                                    $msg = '<div class="alert alert-success" role="alert" style="margin: 0">
                                                Se ha guardado la distribución con éxito.!
                                            </div>';
                                }

                                $z++;
                            }

                        }*/
                }
            }else{
                    $success = false;
                    $msg = '<div class="alert alert-danger" role="alert" style="margin: 0">
                                        Ha ocurrido un error inesperado al guardar la distribución del tratamiento, intente nuevamente.!
                                    </div>';
                }

            } else {

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
                'success' => $success,
                "msg" => $msg
            ];


    }

    public function storeDatosDoctor(Request $request){

        $success = false;
        $msg = '<div class="alert alert-danger" role="alert" style="margin: 0">
                     Ha ocurrido un error al procesar la solicitud, intente nuevamente        
                </div>';

        $str = ["(",")"," ","-"];
        $doctor = explode(")",$request->campo_texto_doctor);
        $partyDoctor = isset($doctor[1]) ? $doctor[0] : null;
        $nombreDoctor = isset($doctor[1]) ? $doctor[1] : $doctor[0];
        $tlf1 = isset($request->campo_tlf_1) ? str_replace($str,"",$request->campo_tlf_1) : null;
        $mail1 = isset($request->campo_mail_1) ? $request->campo_mail_1 : null;
        $texto1 = isset($request->campo_texto_1) ? $request->campo_texto_1 : null;
        $texto2 = isset($request->campo_texto_2) ? $request->campo_texto_2 : null;

        $objCapturaDatoCliente = isset($request->id_datos_cliente) ? CapturaDatoCliente::find($request->id_datos_cliente) : new CapturaDatoCliente;
        $objCapturaDatoCliente->id_captura_dato = $request->id_captura_dato;

        if(!isset($partyDoctor)){

            $objParty = new Party;
            $objParty->party_id = getSequenceValueItem('Party');
            $objParty->party_type_id = "PERSON";
            $objParty->created_by_user_login = session('party_id');

            if($objParty->save()) {

                $objPartRole = new PartyRole;
                $objPartRole->party_id = $objParty->party_id;
                $objPartRole->role_type_id = "MEDICO_USUARIO";

                if($objPartRole->save()){

                    if (setSequenceValueItem('Party')) {

                        $objPerson = new Person;
                        $objPerson->party_id = $objParty->party_id;
                        $objPerson->first_name = $nombreDoctor;

                        if($objPerson->save()){

                            $objUserLogin = new UserLogin;
                            $objUserLogin->user_login_id = $objParty->party_id;
                            $objUserLogin->current_password =  "{SHA}".sha1("1234");
                            $objUserLogin->enabled = "N";
                            $objUserLogin->created_stamp = now()->toDateString();
                            $objUserLogin->party_id = $objParty->party_id;
                            $objUserLogin->email = isset($mail1) ? $mail1 : "";
                            if($objUserLogin->save())
                                crear_log("tratamiento_notificacion_segundo_paso", session('party_id'), $objParty->party_id, 'Se han guardado los datos del doctor '.getParty($objParty->party_id)->person->first_name ." ".  getParty($objParty->party_id)->person->last_name.'');

                            if(isset($tlf1)){

                                $objContactMetch = new ContactMech;
                                $objContactMetch->contact_mech_id = getSequenceValueItem('ContactMech');
                                $objContactMetch->contact_mech_type_id = 'TELECOM_NUMBER';

                                if ($objContactMetch->save()) {

                                    setSequenceValueItem('ContactMech');
                                    $objTelecomNumber = new TelecomNumber;
                                    $objTelecomNumber->contact_mech_id = $objContactMetch->contact_mech_id;
                                    $objTelecomNumber->country_code = "593";
                                    $objTelecomNumber->contact_number = $request->campo_tlf_1;

                                    if ($objTelecomNumber->save()) {

                                        $objPartyContactMech = new PartyContactMech;
                                        $objPartyContactMech->party_id = $objParty->party_id;
                                        $objPartyContactMech->contact_mech_id = $objContactMetch->contact_mech_id;
                                        $objPartyContactMech->role_type_id = "MEDICO_USUARIO";
                                        $objPartyContactMech->from_date = now()->format('Y/m/d');
                                        $objPartyContactMech->save();

                                    }

                                }

                            }

                            if(isset($mail1)){

                                $objContactMetch = new ContactMech;
                                $objContactMetch->contact_mech_id = getSequenceValueItem('ContactMech');
                                $objContactMetch->contact_mech_type_id = 'EMAIL_ADDRESS';
                                $objContactMetch->info_string = $mail1;

                                if ($objContactMetch->save()) {

                                    setSequenceValueItem('ContactMech');
                                    $objPartyContactMech = new PartyContactMech;
                                    $objPartyContactMech->party_id = $objParty->party_id;
                                    $objPartyContactMech->contact_mech_id = $objContactMetch->contact_mech_id;
                                    $objPartyContactMech->role_type_id = "MEDICO_USUARIO";
                                    $objPartyContactMech->from_date = now()->format('Y/m/d');
                                    $objPartyContactMech->save();

                                }

                            }

                        }

                    }

                }

            }

        }else{
            $user = getUserLogin($partyDoctor);
            if(!isset($user)){

                $objUserLogin = new UserLogin;
                $objUserLogin->user_login_id = $partyDoctor;
                $objUserLogin->current_password =  "{SHA}".sha1("1234");
                $objUserLogin->enabled = "N";
                $objUserLogin->created_stamp = now()->toDateString();
                $objUserLogin->party_id = $partyDoctor;
                $objUserLogin->email = isset($mail1) ? $mail1 : "";
                if($objUserLogin->save())
                    crear_log("tratamiento_notificacion_segundo_paso", session('party_id'), $partyDoctor, 'Se han guardado los datos del doctor '.getParty($partyDoctor)->person->first_name ." ".  getParty($partyDoctor)->person->last_name.'');

            }else{

                $party = getParty($partyDoctor);

                foreach ($party->party_contact_mech as $partyContactMech) {
                    if(isset($partyContactMech->contact_mech->telecom_number->contact_number)){
                        $objTelecomNumber = TelecomNumber::where('contact_mech_id',$partyContactMech->contact_mech->contact_mech_id);
                        $objTelecomNumber->update(['contact_number'=>str_replace($str,"",$request->campo_tlf_1)]);
                    }
                    if($partyContactMech->contact_mech->info_string != ""){
                        $objTelecomNumber = ContactMech::where('contact_mech_id',$partyContactMech->contact_mech->contact_mech_id);
                        $objTelecomNumber->update(['info_string'=>$request->campo_mail_1 ]);
                    }
                }
            }
        }

        if(isset($texto1))
            $objCapturaDatoCliente->texto1 = $texto1;
        if(isset($texto2))
            $objCapturaDatoCliente->texto2 = $texto2;


        $party = getParty(session('party_id'));
                                                                                                                                                                // ROL AGREGADO SOLO PARA PRUEBAS
        if($party->party_role->role_type->role_type_id == "END_USER_CUSTOMER" || $party->party_role->role_type->role_type_id == "REPRESENTANTE_LEGAL" || $party->party_role->role_type->role_type_id == "ADMIN"){
            $tratamientoSolicitado = getSolicitudTratamiento($request->id_tratamiento,$party->party_id);
            $partyId = $party->party_id;
            if(!isset($tratamientoSolicitado)){
                setSolicitudTratamiento($request->id_tratamiento,$request->paso,isset($partyDoctor) ? $partyDoctor : $objParty->party_id);
            }else{
                updateSolicitudTratamiento($tratamientoSolicitado->id_tratamiento_solicitado,$request->paso);
            }
        }else{
            $partyId = $request->partyIdSolicitante;
        }

        $tratamientoSolicitado = getSolicitudTratamiento($request->id_tratamiento,$partyId);


        if($party->party_role->role_type->role_type_id !== "END_USER_CUSTOMER" && $party->party_role->role_type->role_type_id !== "REPRESENTANTE_LEGAL" && $party->party_role->role_type->role_type_id !== "ADMIN"){

            $objIntervinienteTratamientoSolicitado = getIntervinienteTratamiento($request->id_tratamiento);
            setIntervinienteTratamiento($objIntervinienteTratamientoSolicitado, $tratamientoSolicitado->id_tratamiento_solicitado, $request->paso);

        }

        $objCapturaDatoCliente->id_tratamiento_solicitado = $tratamientoSolicitado->id_tratamiento_solicitado;
        $objCapturaDatoCliente->party_id = isset($partyDoctor) ? $partyDoctor : $objParty->party_id;

        if($objCapturaDatoCliente->save()){

            $log = crear_log("tratamiento_notificacion", $request->id_tratamiento, session('party_id'),"El ".$party->party_role->role_type->description." ".getParty(session('party_id'))->person->first_name." ".getParty(session('party_id'))->person->last_name ." ha ingresado los datos de su doctor tratante para el tratamiento ". getTratamiento($request->id_tratamiento)->nombre_tratamiento);
            if($log){
                $success = true;
                $msg = '<div class="alert alert-success" role="alert" style="margin: 0">
                       Se han enviado los datos éxitosamente.!        
                    </div>';

                if($request->notifica_doctor){

                    mensajeTexto()->message()->send([
                        'to' => '593983537432',//'593'.str_replace($str,"",$request->campo_tlf_1)
                        'from' => getConfiguracionEmpresa()->nombre_empresa,
<<<<<<< HEAD
                        'text' => "Hola Dr. ".$nombreDoctor." su paciente ".getParty(session('party_id'))->person->first_name." ".getParty(session('party_id'))->person->last_name." ha solicitado un tratamiendo en ".getConfiguracionEmpresa()->nombre_empresa.", nuestro personal se contactará con usted los mas brevemente posible"
=======
                        'text' => "Hola Dr. ".$nombreDoctor." su paciente ".getParty(session('party_id'))->person->first_name." ".getParty(session('party_id'))->person->last_name." ha solicitado un tratamiendo en ".getConfiguracionEmpresa()->nombre_empresa.", nuestro contrataciones se contactará con usted los mas brevemente posible"
>>>>>>> b33f1bba520636fe4938396469f8d02c7ff1f642
                    ]);

                }
            }

        }
        return [
            'success' =>$success,
            "msg" => $msg
        ];
    }

    public function addFaseDistribucionTratamientoSeguimiento(Request $request){

        $solicitante = getParty($request->partyIdSolicitante);

        $objDistribucionTratamientoDoctor = DetalleTratamientoDoctor::where('id_tratamiento_solicitado',$solicitante->tratamiento_solicitado()->id_tratamiento_solicitado)->first();

        if(!isset($objDistribucionTratamientoDoctor)){
            $success = false;
            $msg = '<div class="alert alert-danger" role="alert" style="margin: 0">
                        El doctor tratante del paciente no ha configurado la distribución del tratamiento aún
                    </div>';
        }else{

            $objDistribucionTratamiento = new DistribucionTratamientoDoctor;
            $objDistribucionTratamiento->cantidad_intervalo = $request->cantidad_intervalo;
            $objDistribucionTratamiento->intervalo = $request->intervalo;
            $objDistribucionTratamiento->cantidad_aplicacion = $request->cantidad_aplicacion;
            $objDistribucionTratamiento->id_detalle_tratamiento_doctor = $objDistribucionTratamientoDoctor->id_detalle_tratamiento_doctor;
            $objDistribucionTratamiento->nuevo= true;

            if($objDistribucionTratamiento->save()){
                $modelDetalleDistribucionTratamiento = DistribucionTratamientoDoctor::all()->last();
                for ($x=0;$x<$request->cantidad_aplicacion;$x++){
                    for ($y=0;$y<count($request->producto);$y++){
                        $objDetalleDistribucionTratamiento = new DetalleDistribucionTratamientoDoctor;
                        $objDetalleDistribucionTratamiento->id_distribucion_tratamiento_doctor = $modelDetalleDistribucionTratamiento->id_distribucion_tratamiento_doctor;
                        $objDetalleDistribucionTratamiento->cumplido = false;
                        $objDetalleDistribucionTratamiento->intervalo = 1;
                        $objDetalleDistribucionTratamiento->cantidad_intervalo = 1;
                        $objDetalleDistribucionTratamiento->cantidad_aplicacion = 1;
                        $objDetalleDistribucionTratamiento->product_id = $request->producto[$y]['producto'];
                        if(!$objDetalleDistribucionTratamiento->save()){
                            DistribucionTratamientoDoctor::destroy($modelDetalleDistribucionTratamiento->id_distribucion_tratamiento_doctor);
                            $success = false;
                            $msg = '<div class="alert alert-danger" role="alert" style="margin: 0">
                                Ha ocurrido un error inesperado al crear la nueva fase, intente nuevamente.!
                            </div>';
                        }
                    }
                }

                $success = true;
                $msg = '<div class="alert alert-success" role="alert" style="margin: 0">
                        Se ha creado la nueva fase con éxito.!
                   </div>';

            }else{
                $success = false;
                $msg = '<div class="alert alert-danger" role="alert" style="margin: 0">
                        Ha ocurrido un error inesperado al crear la nueva fase, intente nuevamente.!
                    </div>';
            }
        }

        return [
            'success' =>$success,
            "msg" => $msg
        ];

    }

    public function deleteFaseDistribucionTratamientoSeguimiento(Request $request){

        $success = false;
        $msg = '<div class="alert alert-danger">' .
            '<p class="text-center">Ha ocurrido un error al eliminar la fase del tratamiento, intente nuevamente</p>' .
            '<ul>';

        $objDistribucionTratamientoDoctor = DistribucionTratamientoDoctor::destroy($request->id_distribucion_tratamiento_doctor);

        if($objDistribucionTratamientoDoctor){
            $success = true;
            $msg = '<div class="alert alert-success">' .
                '<p class="text-center">Se ha eliminado la fase con éxito</p>' .
                '<ul>';
        }
        return [
            'success' =>$success,
            "msg" => $msg
        ];
    }

    public function updateFechaTratamientoSolicitado(Request $request){

        $success = false;
        $msg = '<div class="alert alert-danger">' .
            '<p class="text-center">Ha ocurrido un error al colocar la fecha de inicio del tratamiento, intente nuevamente</p>' .
            '<ul>';

        $objTratamientoSolicitado = TratamientoSolicitado::find($request->id_tratamineto_solicitado);
        $objTratamientoSolicitado = $objTratamientoSolicitado->update(['fecha_inicio'=>$request->fecha_inicio]);

        if($objTratamientoSolicitado){
            $success = true;
            $msg = '<div class="alert alert-success text-center">' .
                    'Se ha actualizado la fecha de inicio del tratamiento con éxito' .
                '<ul>';
        }
        return [
            'success' =>$success,
            "msg" => $msg
        ];

    }

}

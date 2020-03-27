<?php

use App\Modelos\LogAdministrador;
use App\Modelos\SequenceValueItem;
use App\Modelos\Party;
use App\Modelos\ContactMech;
use App\Modelos\Menu;
use App\Modelos\UserLogin;
use App\Modelos\DetalleTratamientoDoctor;
use \App\Modelos\Empresa;
use Carbon\Carbon;
use \App\Modelos\Documento;
use \App\Modelos\Notificacion;
use \App\Modelos\Tratamiento;
use \App\Modelos\CargaArchivo;
use App\Modelos\Person;
use \App\Modelos\TratamientoSolicitado;
use \App\Modelos\CargaArchivoCliente;
use \App\Modelos\ProcesoTratamiento;
use \App\Modelos\Productos;
use App\Modelos\IntervinienteTratamientoSolicitado;
use App\Modelos\DistribucionTratamiento;
use \App\Modelos\DetalleTratamiento;
use \App\Modelos\Cie10Tratamiento;
use \App\Modelos\DistribucionTratamientoDoctor;
use App\Modelos\OrderRole;
use App\Modelos\Cie10;
use \App\Modelos\SubMenu;
use App\Modelos\InventoryItem;
use App\Modelos\BotConversacion;
use \App\Modelos\CapturaDato;
use \App\Modelos\CapturaDatoCliente;
use \App\Modelos\DocumentoConsolidado;
use App\Modelos\DocumentoTratamientoSolicitado;
use \App\Modelos\PreguntaRespuesta;


function crear_log($tabla,$idRegistroTabla,$idUsuario,$accion){
    $objLogAdministrador = new LogAdministrador;
    $objLogAdministrador->tabla = $tabla;
    $objLogAdministrador->id_registro_tabla = $idRegistroTabla;
    $objLogAdministrador->id_usuario = $idUsuario;
    $objLogAdministrador->accion = $accion;
    $objLogAdministrador->ip = request()->ip();
    if($objLogAdministrador->save()){
        return 1;
    }else{
        return 0;
    }
}

function getSequenceValueItem($item){
    return SequenceValueItem::where('seq_name',$item)->select('seq_id')->first()->seq_id + 1;
}

function setSequenceValueItem($item,$operacion = "aumentar"){

    if($operacion === "aumentar")
        $dataItem = SequenceValueItem::where('seq_name', $item)->select('seq_id')->first()->seq_id + 1;

    if($operacion === "disminuir")
        $dataItem = SequenceValueItem::where('seq_name', $item)->select('seq_id')->first()->seq_id - 1;

    $objSequenceValueItem = SequenceValueItem::find($item);
    $objSequenceValueItem->seq_id = $dataItem;

    if ($objSequenceValueItem->save()) {
        return true;
    } else {
        return false;
    }
}

function getParty($id){
    return Party::find($id);
}

function getContactMech($id){
    return ContactMech::find($id);
}

function getMenu($id){
    return Menu::find($id);
}

function getSubMenuByPath($path){
    return SubMenu::where('path',$path)->first();
}

function getUserLogin($id){
    return UserLogin::find($id);
}

function getConfiguracionEmpresa(){
    return Empresa::first();
}

function getDocumento($idDocumento){
    return Documento::find($idDocumento);
}

function crearDocumento($datos,$save=true){
    $direccion="";
    $party = getParty(session('party_id'));
    foreach ($party->party_contact_mech as $pcm)
        if($pcm->contact_mech->contact_mech_type_id === "POSTAL_ADDRESS")
           // dump($pcm->contact_mech->contact_mech_type_id);
            $direccion = $pcm->contact_mech->posta_address->city." ".$pcm->contact_mech->posta_address->address1;

    $tags = [
        '[NOMBRE_EMPRESA]',
        '[PAIS_EMPRESA]',
        '[ID_EMPRESA]',
        '[DIREC_EMPRESA]',
        '[NOMBRE_REP_EMPRESA]',
        '[ID_REP_EMPRESA]',
        '[TLF_REP_EMPRESA]',
        '[CORREO_REP_EMPRESA]',
        '[DIA]',
        '[MES]',
        '[ANNO]',
        '[SALTO_DE_PAGINA]',
        '[NOMBRE_USUARIO]',
        '[APELLIDO_USUARIO]',
        '[ID_USUARIO]',
        '[DIREC_USUARIO]'
    ];
    $data = [
        ucwords($datos['NOMBRE_EMPRESA']),         //[NOMBRE_EMPRESA]
        $datos['PAIS_EMPRESA'],                   //[ID_EMPRESA]
        $datos['ID_EMPRESA'],                    //[DIREC_EMPRESA]
        ucwords($datos['DIREC_EMPRESA']),       //[NOMBRE_REP_EMPRESA]
        $datos['NOMBRE_REP_EMPRESA'],          //[ID_REP_EMPRESA]
        $datos['ID_REP_EMPRESA'],             //[DIERC_EMPLEADO]
        $datos['TLF_REP_EMPRESA'],           //[CARGO_EMPLEADO]
        $datos['CORREO_REP_EMPRESA'],       //[SALARIO_EMPLEADO]
        Carbon::now()->format('d'),
        Carbon::now()->format('m'),
        Carbon::now()->format('Y'),
        "<div style='page-break-after:always;'></div>", //[SALTO_DE_PAGINA]'
        $party->person->first_name,
        $party->person->last_name,
        $party->identification->id_value,
        $direccion
     ];

    $nuevaCadena = preg_replace($tags,$data,$datos['TEXTO_DOCUMENTO']);
    $eliminar = ['[',']'];
    $vacio = ['',''];
    $cadenaFormateada = str_replace($eliminar, $vacio, $nuevaCadena);
    if($save){
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($cadenaFormateada);
        $number = mt_rand();
        $pdf->save(storage_path('app/public').'/'.$number.'-'.'.pdf');

        return $number.'-'.'.pdf';
    }else{
        return $cadenaFormateada;
    }

}

function getNotificacion($idNotificacion){
    return Notificacion::find($idNotificacion);
}

function getCargaArchivo($idCargaArchivo){
    return CargaArchivo::find($idCargaArchivo);
}

function getIconoProceso(){
    return [
        '<i class="fa fa-address-card-o"></i>',
        '<i class="fa fa-cloud-upload"></i>',
        '<i class="fa fa-file-text-o"></i>',
        '<i class="fa fa-file-pdf-o"></i>',
        '<i class="fa fa-files-o"></i>'
    ];
}

function getTratamiento($idTratamiento,$all=null){
    if(isset($all))
        return Tratamiento::all();

    return Tratamiento::find($idTratamiento);
}

function getDoctores(){
    $doctores = [];
    //$person = Person::get();

    $person= Person::join('party as pt','person.party_id','pt.party_id')
        ->join('party_role as pr','pr.party_id','pt.party_id')
        ->join('role_type as rt','pr.role_type_id','pr.role_type_id')
        ->where('pr.role_type_id','MEDICO_USUARIO')
        ->select('person.party_id','person.first_name','person.last_name')
        ->distinct('person.party_id')->get();
        //dd($person);
    foreach ($person as $p) {
        //if(isset($p->party->party_role)){
            //if($p->party->party_role->role_type->role_type_id === "MEDICO_USUARIO")
                $doctores[] = $p->party_id.") ".$p->first_name." ".$p->last_name;
        //}
    }
    return $doctores;
}

function getDataProceso($tabla,$idTabla,$parametro){
    return DB::table($tabla)->where($idTabla,$parametro)->first();
}

function getTratamientoSolicitado($idTratamiento,$partyId,$all=null){

    if(isset($all))
        return TratamientoSolicitado::where('estado',true)->get();

    return TratamientoSolicitado::where([
        ['id_tratamiento',$idTratamiento],
        ['party_id',$partyId],
        ['estado',1]
    ])->first();
}

function getCargaArchivoCliente($idTratamientoSolicitado,$idCargaArchivo){
    return CargaArchivoCliente::where([
        ['id_tratamiento_solicitado',$idTratamientoSolicitado],
        ['id_carga_archivo',$idCargaArchivo]
    ])->get();
}

function getProcesoTratamiento($idProceso){
    return ProcesoTratamiento::find($idProceso);
}

function getProducto($product_id){
    return Productos::where('product_id',$product_id)->first();
}

function getTratamientoSolicitadoById($idTratamientoSolicitado){
    return TratamientoSolicitado::find($idTratamientoSolicitado);
}

function getIntervinienteTratamientoSolicitado($idTratamiento, $partyId){
    return IntervinienteTratamientoSolicitado::where([
        ['id_tratamiento_solicitado',$idTratamiento],
        ['party_id',$partyId]
    ])->first();
}

function getEnfermedades(){
    $enfermedades = [];

    foreach (Cie10::all() as $c) {
        $enfermedades[] = $c->codigo .") ".$c->descripcion;
    }
    return $enfermedades;
}

function getIdEnfermedad($codigo){
    return Cie10::where('codigo',$codigo)->first();
}

function getDetalleTratamiento($idTratamiento){
    return DetalleTratamiento::where('id_tratamiento',$idTratamiento)->first();
}

function getDetalleTratamientoDoctorByIdTratamientoSolicitado($idTratamientoSolicitado){
    return DetalleTratamientoDoctor::where('id_tratamiento_solicitado',$idTratamientoSolicitado)->first();
}

function getCie10Tratamiento($idTratamiento){
    return Cie10Tratamiento::where('id_tratamiento',$idTratamiento)->get();
}

function getcie10($idCie10){
    return Cie10::find($idCie10);
}

function getDistribucionTratamientoDoctor($idTratamiento,$partyDoctor,$partySolicitante){
    return DistribucionTratamientoDoctor::where([
        ['id_tratamiento',$idTratamiento],
        ['party_id_doctor', $partyDoctor],
        ['party_id_solicitante',$partySolicitante]
    ])->get();
}

function getCapturaDato($idCapturaDato){
   return CapturaDato::find($idCapturaDato);
}

function setSolicitudTratamiento($idTratamiento,$paso,$idDoctor = null){

    $objSolicitudTratamiento = new TratamientoSolicitado;
    $objSolicitudTratamiento->id_tratamiento = $idTratamiento;
    $objSolicitudTratamiento->party_id = session('party_id');
    $objSolicitudTratamiento->proceso_actual = $paso;
    isset($idDoctor) != null ? $objSolicitudTratamiento->id_doctor = $idDoctor : "";
    if($objSolicitudTratamiento->save()){
        $modeloTratamientoSolicitado = TratamientoSolicitado::all()->last();
        crear_log('tratamiento_solicitado',$modeloTratamientoSolicitado->id_tratamiento_solicitado,session('party_id'),'Se ha solicitado un nuevo tratamiento por parte del usuario '.getParty(session('party_id'))->person->first_name ." ".getParty(session('party_id'))->person->last_name .'');

    }
}

function getSolicitudTratamiento($idTratamiento,$partyId){
    return TratamientoSolicitado::where([
        ['id_tratamiento',$idTratamiento],
        ['party_id',$partyId],
        ['estado',1]
    ])->first();
}

function updateSolicitudTratamiento($idTratamietoSolicitado,$paso,$partyIdDoctor=null){
    $tratamientoSolictado = TratamientoSolicitado::find($idTratamietoSolicitado);
    $tratamientoSolictado->proceso_actual = $paso;
    isset($partyIdDoctor) != null ? $tratamientoSolictado->id_doctor = $partyIdDoctor : "";
    $tratamientoSolictado->save();
}

function getIntervinienteTratamiento($idTratamiento){
    return IntervinienteTratamientoSolicitado::where([
        ['id_tratamiento_solicitado',$idTratamiento],
        ['party_id',session('party_id')]
    ]);
}

function setIntervinienteTratamiento($objIntervinienteTratamientoSolicitado,$idTratamientoSolicitado ,$paso){

    if($objIntervinienteTratamientoSolicitado->first() == null){
        $objTratamientoSolicitado = new IntervinienteTratamientoSolicitado;
        $objTratamientoSolicitado->id_tratamiento_solicitado = $idTratamientoSolicitado;
        $objTratamientoSolicitado->party_id = session('party_id');
        $objTratamientoSolicitado->proceso_actual = $paso;
        $objTratamientoSolicitado->save();
    }else{
        if($objIntervinienteTratamientoSolicitado->first()->proceso_actual < $paso)
            $objIntervinienteTratamientoSolicitado->update(['proceso_actual'=> $paso]);
    }

}

function mensajeTexto(){

    $basic = new \Nexmo\Client\Credentials\Basic(env('NEXMO_KEY'), env('NEXMO_SECRET'));
    return $client = new \Nexmo\Client($basic);
}

function getCapturaDatoCliente($idTratamientoSolicitado,$idCapturaDatoCliente){
    return CapturaDatoCliente::where([
        ['id_tratamiento_solicitado',$idTratamientoSolicitado],
        ['id_captura_dato',$idCapturaDatoCliente]
    ])->first();
}

function dataProcesoTratamiento($p,$cantProcesos=null){
   // $id="";
    switch ($p->sub_menu->path){
        case 'notificacion':
            $id = 'id_nontificacion';
            break;
        case 'documento':
            $id = 'id_documento';
            break;
        case 'carga_archivo':
            $id = 'id_carga_archivo';
            break;
        case 'distribucion_tratamiento':
            $id = 'id_tratamiento';
            $p->sub_menu->path = "detalle_tratamiento";
            $p->id_proceso = $p->id_tratamiento;
            break;
        case 'captura_dato':
            $id = 'id_captura_dato';
            break;
        case 'documento_consolidado':
            $id = 'id_documento_consolidado';
            break;
        case "detalle_tratamiento":
            $id = 'id_tratamiento';
            $p->id_proceso = $p->id_tratamiento;
            break;
        case 'cotizacion':
            $p->sub_menu->path = "tratamiento";
            $id = 'id_tratamiento';
            break;
        case "tratamiento":
            $id = 'id_tratamiento';
            $p->id_proceso = $p->id_tratamiento;
            break;

    }

    isset($cantProcesos)
        ? $data =  collect(getDataProceso($p->sub_menu->path,$id,$p->id_proceso))['role_type_id']
        : $data = collect(getDataProceso($p->sub_menu->path,$id,$p->id_proceso));

    return $data;
}

function getIntervalo($intervalo){

    switch ($intervalo){
        case "1":
            return "D";
            break;
        case "2":
            return "S";
            break;
        case "3":
            return "M";
            break;
    }
}

function getDocumentoConsolidado($idDocumentoConsolidado){
    return DocumentoConsolidado::find($idDocumentoConsolidado);
}

function getDocumentoConsolidadoByIdTratamiento($idTratamiento){
    return DocumentoConsolidado::where('id_tratamiento',$idTratamiento)->first();
}

function getCargaArchivoClienteById($idCargaArchivoCliente){
    return CargaArchivoCliente::find($idCargaArchivoCliente);
}

function getDocumentoTratamientoSolicitadoById($idDocumentoTratamientoSolicitado){
    return DocumentoTratamientoSolicitado::find($idDocumentoTratamientoSolicitado);
}

function getFechaAplicacionFase($idTratamiento,$partyId){

    $tratamientoSolicitado = getTratamientoSolicitado($idTratamiento,$partyId);
    $fechaInicio = $tratamientoSolicitado->fecha_inicio;
    $calculoTratamiento = $tratamientoSolicitado->tratamiento->distribucion_tratamiento[0]->calculo_intervalo;

}

function chatsBotSesion(){
    return BotConversacion::where('id_sesion',session('id_sesion'))->orderBy('fecha_ingreso','asc')->get();
}

function getPreguntaRespuesta($idPreguntaRespuesta){
    return PreguntaRespuesta::find($idPreguntaRespuesta);
}

function getNotifiacionesActivas(){
   $data = LogAdministrador::where('tabla','tratamiento_notificacion_segundo_paso')->where('estado_notificacion',false)
        ->orWhere('tabla','cotizacion')->where('estado_notificacion',false)
        ->orWhere('tabla','tratamiento_solicitado')->where('estado_notificacion',false)->get();

   return $data;
}

function getNotificacionesNoVistas(){

    $objLogAdministrador = LogAdministrador::where([
        ['tabla','tratamiento_solicitado'],
    ])->orWhere('tabla','tratamiento_notificacion')->where('estado_notificacion',0)
        ->orWhere('tabla','tratamiento_notificacion_segundo_paso')->where('estado_notificacion',0)
        ->orWhere('tabla','cotizacion')->where('estado_notificacion',0);

    return $objLogAdministrador->count();
}

function getProductosEntregados($pductId,$party_id){

    $cantidad = 0;
    $data = OrderRole::where([
        ['order_role.party_id',$party_id],
        ['role_type_id','BILL_TO_CUSTOMER'],
        ['iid.quantity_on_hand_diff','!=',0],
        ['iid.available_to_promise_diff','=',0],
        ['it.product_id',$pductId],
    ])->join('inventory_item_detail as iid','order_role.order_id','iid.order_id')
      ->join('inventory_item as it','iid.inventory_item_id','it.inventory_item_id')
      /*->orWhereNotIn('it.status_id',['INV_NS_ON_HOLD','INV_NS_DEFECTIVE'])*/->get();

    foreach ($data as $ventas)
        if($ventas->status_id != "INV_NS_ON_HOLD" && $ventas->status_id != "INV_NS_DEFECTIVE")
            $cantidad+=(int)abs($ventas->quantity_on_hand_diff);


    return $cantidad;
}

function getProductosImportados($pductId,$autorizacionId){

    $cantidad = 0;
    $data = InventoryItem::where([
        ['product_id',$pductId],
        ['autorizacion_id',$autorizacionId]
    ])->whereNotNull('autorizacion_id')->get();

    foreach ($data as $importado)
        $cantidad+=(int)abs($importado->accounting_quantity_total);

    return $cantidad;
}
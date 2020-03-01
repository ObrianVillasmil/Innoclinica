<?php

namespace App\Http\Controllers;

use App\Modelos\CapturaDato;
use App\Modelos\CargaArchivo;
use App\Modelos\Cie10Tratamiento;
use App\Modelos\DetalleDistribucionTratamiento;
use App\Modelos\DetalleTratamiento;
use App\Modelos\DistribucionTratamiento;
use App\Modelos\Documento;
use App\Modelos\DocumentoConsolidado;
use App\Modelos\Menu;
use App\Modelos\Notificacion;
use App\Modelos\Productos;
use Illuminate\Http\Request;
use App\Modelos\Especialidad;
use App\Modelos\Tratamiento;
use Illuminate\Support\Facades\Session;
use App\Modelos\ProcesoTratamiento;
use \App\Modelos\SubMenu;
use Dompdf\Dompdf;
use Validator;

class TratamientoController extends Controller
{
    public function inicio(Request $request){

        return view('tratamiento.list',[
            'url' => $request->path(),
            'titulo' => ['titulo'=>'Configuraciones','sub_titulo'=>'Creación de tratamientos'],
            'usuario' => getParty((int)session::get('party_id')),
            'trataminetos' => Tratamiento::where('estado',isset($request->estado) ? $request->estado : 1)->get(),
            'estado' => isset($request->estado) ? $request->estado : 1

        ]);
    }

    public function addTratamiento(Request $request, $idTratamiento = null){
        return view('tratamiento.partials.crear',[
            'url' => $request->path(),
            'titulo' => ['titulo'=>'Proceso','sub_titulo'=>'Tratamientos'],
            'usuario' => getParty((int)session::get('party_id')),
            'especialidades' => Especialidad::all(),
            'dataTratamiento' => $idTratamiento === null ? $idTratamiento : Tratamiento::find($idTratamiento)
        ]);
    }

    public function storeTratamiento(Request $request){
       // dd($request->all());
        $validar = Validator::make($request->all(), [
            'nombre' => 'required',
            'especialidad' => 'required',
        ]);


        if(!empty($request->imagen))
            $validar = Validator::make($request->all(), [
                'imagen' => 'mimes:jpeg,png,JPEG,JPG,PNG|dimensions:min_width=250,min_height=300,max_width=250,max_height=300'
            ],['imagen.dimensions' => 'La imagen del tratamiento debe ser mínimo 250px de ancho por 300px de alto']);

        $success = false;
        $msg = '<div class="alert alert-danger" role="alert" style="margin: 0">
                      ha ocurrido un inconveniente al guardar el tratamiento, intente nuevamente.!
                </div>';

        if (!$validar->fails()) {

            ($request->tratamiento && !empty($request->tratamiento))
                ?  $objTratamiento = Tratamiento::find($request->tratamiento)
                :  $objTratamiento = new Tratamiento;

            $objTratamiento->nombre_tratamiento = $request->nombre;
            $objTratamiento->id_especialidad = $request->especialidad;
            $objTratamiento->icono = $request->icono;

            if(!empty($request->imagen)){

                $archivo = $request->file('imagen');
                $imagen  =  mt_rand().$archivo->getClientOriginalName();
                \Storage::disk('tratamientos')->put($imagen, \File::get($archivo));
                $objTratamiento->imagen = $imagen;

                if($request->tratamiento && !empty($request->tratamiento)){
                    $dataTratamiento = Tratamiento::where('id_tratamiento',$request->tratamiento)->first();
                    \Storage::disk('tratamientos')->delete($dataTratamiento->imagen);
                }
            }


            if($objTratamiento->save()) {
                $success = true;
                $msg = '<div class="alert alert-success" role="alert" style="margin: 0">
                            Se ha guardado el tratamiento con éxito.!
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

    public function addProcesosTratamiento(Request $request, $idTratamiento=null){
        return view('tratamiento.partials.add_procesos_tratamientos',[
            'url' => $request->path(),
            'titulo' => ['titulo'=>'Proceso','sub_titulo'=>'Creación de tratamientos'],
            'usuario' => getParty(session('party_id')),
            'icono' => getIconoProceso(),
            'procesos' => Menu::where('nombre','Procesos')->first(),
            'notificaciones' => Notificacion::get(),
            'cargaArchivo' => CargaArchivo::get(),
            'documentos' => Documento::get(),
            'procesosTratamientos' => isset($idTratamiento) ? getTratamiento($idTratamiento) : null,
            'idTratamiento' => $idTratamiento,
            'distribucion_tratamiento' => Tratamiento::whereIn('id_tratamiento', function ($query){
                $query->select('id_tratamiento')->from('distribucion_tratamiento');
            })->get(),
            'capturaDatos' =>CapturaDato::get(),
            'documentoConsolidado' => DocumentoConsolidado::where('id_tratamiento',$idTratamiento)->first()
        ]);
    }

    public function storeProcesoTratamiento(Request $request){

        $validar = Validator::make($request->all(), [
            'arr_procesos' => 'required'
        ],['arr_procesos.required'=> 'No se pudo obtener el identificador del proceso']);

        $success = false;
        $msg = '<div class="alert alert-danger" role="alert" style="margin: 0">
                      ha ocurrido un inconveniente al guardar los procesos del tratamiento, intente nuevamente.!
                </div>';

        if (!$validar->fails()) {


            if(getTratamiento($request->id_tratamiento)->procesos->count() > 0)
                ProcesoTratamiento::where('id_tratamiento',$request->id_tratamiento)->delete();


            foreach ($request->arr_procesos as $proceso) {
                $objProcesos = new ProcesoTratamiento;
                $objProcesos->id_tratamiento = $request->id_tratamiento;
                $objProcesos->id_sub_menu = $proceso['id_submenu'];
                $objProcesos->id_proceso = $proceso['id_proceso'];
                $objProcesos->save();

                $success = true;
                $msg = '<div class="alert alert-success" role="alert" style="margin: 0">
                            Se han guardado exitosamente los procesos para el tratamiento.!
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

    public function updateEstadoTratamiento(Request $request){

        $success = false;
        $msg = '<div class="alert alert-danger" role="alert" style="margin: 0">
                      ha ocurrido un inconveniente al cambiar el estado del tratamiento, intente nuevamente.!
                </div>';

        $objTratamiento = Tratamiento::find($request->id_tratamiento);
        $objTratamiento->estado = $request->estado == null ? true: false;

        if($objTratamiento->save()){
            $success = true;
            $msg = '<div class="alert alert-success" role="alert" style="margin: 0">
                            Se ha actualizado exitosamente el estado tratamiento.!
                        </div>';
        }

        return [
            'success' =>$success,
            "msg" => $msg
        ];

    }

    public function deleteTratamiento(Request $request){
        $success = false;
        $msg = '<div class="alert alert-danger" role="alert" style="margin: 0">
                      ha ocurrido un inconveniente al tratar de eliminar el tratamiento, intente nuevamente.!
                </div>';

        if(Tratamiento::destroy($request->id_tratamiento)){

            $success = true;
            $msg = '<div class="alert alert-success" role="alert" style="margin: 0">
                     Se ha eliminado el tratamiento con exito.!
                </div>';

        }
        return [
            'success' =>$success,
            "msg" => $msg
        ];

    }

    public function formDistribucionTratamiento(Request $request){

        $detalleTratamiento = DetalleTratamiento::where('id_tratamiento',$request->id_tratamiento)->first();

        return view('tratamiento.partials.form_distribucion_tratamiento',[
            'id_tratamiento' => $request->id_tratamiento,
            'cie10Tratamiento' => Cie10Tratamiento::where('id_tratamiento',$request->id_tratamiento)
                ->join('cie10 as c','cie10_tratamiento.id_cie10','c.id_cie10')->get(),
            'detalleTratamiento' => isset($detalleTratamiento) ? $detalleTratamiento : null
        ]);

    }

    public function inputDistribucionTratamiento(Request $request){
        return view('tratamiento.partials.input_distribucion_tratamiento',[
           'x'=>$request->x
        ]);
    }

    public function formatoDistribucionTratamiento(Request $request){

        $detalleTratamiento = DetalleTratamiento::where('id_tratamiento',$request->id_tratamiento)->first();

        return view('tratamiento.partials.formato_distribucion_tratamiento',[
            'datosFase' => $request->data,
            'idTratamiento' => $request->id_tratamiento,
            'distribucion' => (isset($detalleTratamiento->distribucion_tratamiento) && $detalleTratamiento->distribucion_tratamiento->count() > 0) ? $detalleTratamiento->distribucion_tratamiento : null,
            'productos' => Productos::orderBy('product_name','asc')->get()
        ]);
    }

    public function storeDistribucionTratamiento(Request $request){

        $success = false;
        $msg = '<div class="alert alert-info" role="alert">
                    No se ha guardado registros, intente nuevamente
                </div>';

        $objcie10Tratamiento = Cie10Tratamiento::where('id_tratamiento',$request->id_tratamiento);
        if($objcie10Tratamiento->first() != null)
            $objcie10Tratamiento->delete();

        if(isset($request->enfermedades)){
            foreach ($request->enfermedades as $enfermedades){
                $objCieTratamiento = new Cie10Tratamiento;
                $objCieTratamiento->id_tratamiento = $request->id_tratamiento;
                $objCieTratamiento->id_cie10 = getIdEnfermedad($enfermedades['codigo_cied10'])->id_cie10;
                if($objCieTratamiento->save()){
                    $msg = '<div class="alert alert-success" role="alert">
                                Se han guardado las enfermedades con éxito
                            </div>';
                }
            }
        }

        $obj = DetalleTratamiento::where('id_tratamiento',$request->id_tratamiento)->first();
        if(isset($obj))
            $obj->delete();

        $objDetalleTratamiento = new DetalleTratamiento;
        $objDetalleTratamiento->id_tratamiento = $request->id_tratamiento;
        $objDetalleTratamiento->calculo_intervalo = $request->calculo_intervalo;

        if($objDetalleTratamiento->save()){
            $modelDetalleTratamiento = DetalleTratamiento::all()->last();

            if(isset($request['distribucion_tratamiento'])) {
                foreach ($request['distribucion_tratamiento'] as $x => $dist) {
                    $objDistribucionTratamiento = new DistribucionTratamiento;
                    $objDistribucionTratamiento->id_detalle_tratamiento = $modelDetalleTratamiento->id_detalle_tratamiento;
                    $objDistribucionTratamiento->intervalo = isset($dist['intervalo']) ? $dist['intervalo'] : null;
                    $objDistribucionTratamiento->cantidad_intervalo = isset($dist['cantidad_intervalo']) ? $dist['cantidad_intervalo'] : null;
                    $objDistribucionTratamiento->cantidad_aplicacion = $dist['cantidad_aplicacion'];
                    if ($objDistribucionTratamiento->save()) {
                        $modelDistribucionTratamiento = DistribucionTratamiento::all()->last();
                        foreach($request['detalle_distribucion_tratamiento'][$x] as $fases){
                            foreach($fases as $filas) {
                                $objDetalleDistribucionTratamiento = new DetalleDistribucionTratamiento;
                                $objDetalleDistribucionTratamiento->id_distribucion_tratamiento = $modelDistribucionTratamiento->id_distribucion_tratamiento;
                                $objDetalleDistribucionTratamiento->product_id = $filas['producto'];
                                $objDetalleDistribucionTratamiento->cantidad_aplicacion = $filas['cantidad'];
                                $objDetalleDistribucionTratamiento->intervalo = $filas['intervalo_aplicacion'];
                                $objDetalleDistribucionTratamiento->cantidad_intervalo = $filas['cantidad_intervalo'];
                                $objDetalleDistribucionTratamiento->save();
                            }
                        }
                    }
                }
            }
            $msg = '<div class="alert alert-success" role="alert">
                            Se ha guardado la distribución del producto para el tratamiento con éxito
                        </div>';
            $success = true;
        }

        return [
            'success' =>$success,
            "msg" => $msg
        ];

    }

    public function seguimientoTratamiento(Request $request, $idTratamiento, $partyIdSolicitado = null){

        $dataPasosTratamiento = getTratamiento($idTratamiento);
        $subMenu = SubMenu::where('path','documento')->select('id_sub_menu')->first();
        $documentoProcesoTratamiento = ProcesoTratamiento::where([
            ['id_sub_menu',$subMenu->id_sub_menu],
            ['id_tratamiento',$idTratamiento]
        ])->select('id_proceso')->get();

        $documentoConsolidado = getDocumentoConsolidadoByIdTratamiento($idTratamiento);

        $roles = [];
        if(isset($documentoConsolidado->documento_solicitado_role_type))
            foreach ($documentoConsolidado->documento_solicitado_role_type as $rol_type)
                $roles[] = $rol_type->role_type_id;


        return view('alerta.partials.seguimiento_tratamiento',[
            'url' => $request->path(),
            'titulo' => ['titulo'=>'Tratamiento','sub_titulo'=> $dataPasosTratamiento->nombre_tratamiento." ".(isset($partyIdSolicitado) ? " ,Solicitado por ".getParty($partyIdSolicitado)->person->first_name." ".getParty($partyIdSolicitado)->person->last_name :" ".'')],
            'usuario' => getParty(session('party_id')),
            'tratamiento' => $dataPasosTratamiento,
            'procesos' => $dataPasosTratamiento->procesos,
            'idTratamiento' => $idTratamiento,
            'partyIdSolicitante' => $partyIdSolicitado,
            'documentoProcesoTratamiento' => $documentoProcesoTratamiento,
            'roles' => $roles,
            'documentoConsolidado' => $documentoConsolidado
        ]);

    }

    public function visualizarTratamiento($idTratamiento){
        $pdf = new Dompdf;
        $html = view('tratamiento.partials.formato_distribucion',[
            "idTratamiento" => $idTratamiento
        ]);
        $pdf->loadHTML($html);
        //$pdf->setPaper('A4', 'Landscape');
        $pdf->render();
        $pdf->stream('distribución del tratamiento',["Attachment" => 0]);
    }

    public function agregarArchivoTratamiento(Request $request){
        return view('alerta.partials.agregar_archivo_tratamiento');
    }

    public function distribucionTratamientoSolicitado($idTratamiento,$partyId,$idDoctor=null){

        $pdf = new Dompdf;
        $html = view('tratamiento.partials.formato_distribucion',[
            "idTratamiento" => $idTratamiento,
            "partyId" => $partyId,
            'idDoctor' => $idDoctor,
            'seguimiento' => true,
        ]);
        $pdf->loadHTML($html);
        $pdf->setPaper('A4', 'Landscape');
        $pdf->render();
        $pdf->stream('distribución del tratamiento',["Attachment" => 0]);
    }

}

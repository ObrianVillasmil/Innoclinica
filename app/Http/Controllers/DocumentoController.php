<?php

namespace App\Http\Controllers;

use App\Modelos\RoleType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Modelos\Documento;
use Validator;
use Dompdf\Dompdf;
use Carbon\Carbon;

class DocumentoController extends Controller
{
    public function inicio(Request $request){
        return view('documento.list',[
            'url' => $request->path(),
            'titulo' => ['titulo'=>'Proceso','sub_titulo'=>'Documentos'],
            'usuario' => getParty((int)session::get('party_id')),
            'documentos' => Documento::all()
        ]);
    }

    public  function addDocumento(Request $request){
        return view('documento.partials.crear',[
            'url' => $request->path(),
            'titulo' => ['titulo'=>'Configuración','sub_titulo'=>'Listado de documentos'],
            'usuario' => getParty((int)session::get('party_id')),
            'roleType' => RoleType::get(),
        ]);
    }

    public function storeDocumento(Request $request){
        $validar = Validator::make($request->all(), [
            'nombre' => 'required',
            'descripcion' => 'required',
            'usuario' => 'required'
        ],['usuario.required'=>'Debe seleccionar quien ejecutará este proceso']);

        $success = false;
        $msg = '<div class="alert alert-danger" role="alert" style="margin: 0">
                      ha ocurrido un inconveniente al guardar el documento, intente nuevamente.!
                </div>';

        if (!$validar->fails()) {

            $request->has('id_documento')
                ?  $objDocumento = Documento::find($request->id_documento)
                :  $objDocumento = new Documento;

            $objDocumento->nombre = $request->nombre;
            $objDocumento->descripcion = $request->descripcion;
            $objDocumento->cuerpo = $request->cuerpo_documento;
            $objDocumento->role_type_id = $request->usuario;
            $objDocumento->icono = $request->icono;

            if($objDocumento->save()) {
                $success = true;
                $msg = '<div class="alert alert-success" role="alert" style="margin: 0">
                            Se ha guardado el documento con éxito.!
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

    public function verDocumento($idDocumento){

        $direccion="";
        $configuracion = getConfiguracionEmpresa();
        $party = getParty(session('party_id'));
        foreach ($party->party_contact_mech as $pcm) {
           if($pcm->contact_mech->contact_mech_type_id === "POSTAL_ADDRESS"){
               dd( getContactMech($pcm->contact_mech->contact_mech_id),$pcm->contact_mech->postal_address, $pcm->contact_mech->contact_mech_id,$pcm->contact_mech->contact_mech_type_id);
              // $direccion = $pcm->contact_mech->postal_address->city." ".$pcm->contact_mech->postal_address->address1;
           }

        }
        $datos = [
            'NOMBRE_EMPRESA' => $configuracion->nombre_empresa,
            'PAIS_EMPRESA' => $configuracion->pais,
            'ID_EMPRESA' => $configuracion->ruc_empresa,
            'DIREC_EMPRESA' => $configuracion->direccion_empresa,
            'NOMBRE_REP_EMPRESA' => $configuracion->nombre_representante,
            'ID_REP_EMPRESA' => $configuracion->identificacion_representante,
            'TLF_REP_EMPRESA' => $configuracion->telefono_representante,
            'CORREO_REP_EMPRESA' => $configuracion->correo_representante,
            'NOMBRE_USUARIO', $party->person->first_name,
            'APELLIDO_USUARIO', $party->person->last_name,
            'ID_USUARIO', $party->identification->id_value,
            'DIREC_USUARIO', $direccion
        ];

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
            ucwords($datos['NOMBRE_EMPRESA']),                 //[NOMBRE_EMPRESA]
            $datos['PAIS_EMPRESA'],                          //[ID_EMPRESA]
            $datos['ID_EMPRESA'],                          //[DIREC_EMPRESA]
            ucwords($datos['DIREC_EMPRESA']),                 //[NOMBRE_REP_EMPRESA]
            $datos['NOMBRE_REP_EMPRESA'],                          //[ID_REP_EMPRESA]
            $datos['ID_REP_EMPRESA'],                 //[DIERC_EMPLEADO]
            $datos['TLF_REP_EMPRESA'],                          //[CARGO_EMPLEADO]
            $datos['CORREO_REP_EMPRESA'],                          //[SALARIO_EMPLEADO]
            Carbon::now()->format('d'),
            Carbon::now()->format('m'),
            Carbon::now()->format('Y'),
            "<div style='page-break-after:always;'></div>", //[SALTO_DE_PAGINA]'
            $datos['NOMBRE_USUARIO'],
            $datos['APELLIDO_USUARIO'],
            $datos['ID_USUARIO'],
            $datos['DIREC_USUARIO']
        ];


        $nuevaCadena = preg_replace($tags,$data,getDocumento($idDocumento)->cuerpo);
        $eliminar = ['[',']'];
        $vacio = ['',''];
        $cadenaFormateada = str_replace($eliminar, $vacio, $nuevaCadena);
        $dompdf = new DOMPDF();
        $dompdf->loadHtml($cadenaFormateada);
        $dompdf->render();
        $dompdf->stream('Documento.pdf',['Attachment' => false]);

    }

    public function editarDocumento(Request $request, $idDocumento,$active=null){

        return view('documento.partials.crear',[
            'documento' => getDocumento($idDocumento),
            'url' => $request->path(),
            'titulo' => ['titulo'=>'Configuración','sub_titulo'=>'Listado de documentos'],
            'usuario' => getParty((int)session::get('party_id')),
            'roleType' => RoleType::get(),
            'active' => $active
        ]);
    }

    public function uploadDocumento(Request $request){

        $validar = Validator::make($request->all(), [
            'nombre_documento' => 'required',
            'descripcion_documento' => 'required',
            'usuario' => 'required',
            'documento' => 'mimes:pdf'
        ],['usuario.required'=>'Debe seleccionar quien ejecutará este proceso',
            'descripcion_documento.required'=> 'debe colocar la descripción del proceso',
            'nombre_documento'=> 'Debe colocar el nombre del proceso',
            'documento.mimes'=>'El documento a subir debe ser PDF'
        ]);

        $success = false;
        $msg = '<div class="alert alert-danger" role="alert" style="margin: 0">
                      ha ocurrido un inconveniente al guardar el documento, intente nuevamente.!
                </div>';

        if (!$validar->fails()) {

            $file = $request->file('documento');


            empty($request->id_documento)
                ? $objDocumento = new Documento
                : $objDocumento = Documento::find($request->id_documento);


            if($file != ""){
                $nombre = mt_rand().$file->getClientOriginalName();
                $objDocumento->archivo = $nombre;
            }

            $objDocumento->nombre = $request->nombre_documento;
            $objDocumento->descripcion = $request->descripcion_documento;
            $objDocumento->role_type_id = $request->usuario;
            $objDocumento->icono = $request->icono;

            if(!empty($request->id_documento) && $file != ""){
                \Storage::disk('documentos')->delete(getDocumento($request->id_documento)->archivo);
                \Storage::disk('documentos')->put($nombre, \File::get($file));
            }

            if($objDocumento->save()) {

                if(empty($request->id_documento))
                    \Storage::disk('documentos')->put($nombre, \File::get($file));

                $success = true;
                $msg = '<div class="alert alert-success" role="alert" style="margin: 0">
                            Se ha guardado el documento con éxito.!
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

    public function deleteDocumento(Request $request){
        $success = false;
        $msg = '<div class="alert alert-danger" role="alert" style="margin: 0">
                      ha ocurrido un inconveniente al guardar el documento, intente nuevamente.!
                </div>';

        if(getDocumento($request->id_documento)->archivo!="")
            \Storage::disk('documentos')->delete(getDocumento($request->id_documento)->archivo);

        if(Documento::destroy($request->id_documento)){
            $success = true;
            $msg = '<div class="alert alert-success" role="alert" style="margin: 0">
                            Se ha eliminado el documento con éxito.!
                        </div>';
        }
        return [
            'success' =>$success,
            "msg" => $msg
        ];
    }
}

<?php

namespace App\Http\Controllers;

use App\Modelos\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Modelos\Geo;
use Validator;

class EmpresaController extends Controller
{
    public $configuracionEmpresa;

    public function __construct(){
        $this->configuracionEmpresa = getConfiguracionEmpresa();
    }

    public function inicio(Request $request){
        return view('empresa.inicio',[
            'url' => $request->path(),
            'titulo' => ['titulo'=>'Configuraciones','sub_titulo'=>'empresa'],
            'usuario' => getParty((int)session::get('party_id')),
            'paises' => Geo::where('geo_type_id','COUNTRY')->get(),
            'empresa' => Empresa::first(),
            'configuracionEmpresa' => $this->configuracionEmpresa
        ]);
    }

    public function storeDatosEmpresa(Request $request){

        $validar = Validator::make($request->all(), [
            'nombre' => 'required',
            'moneda' => 'required',
            'pais' => 'required',
            'ruc' => 'required',
            'direccion' => 'required'
        ]);
        $success = false;

        if (!$validar->fails()) {
            ($this->configuracionEmpresa !== null)
                ? $objDatosEmpresa = Empresa::find($this->configuracionEmpresa->id_empresa)
                : $objDatosEmpresa = new Empresa;

            $objDatosEmpresa->nombre_empresa = $request->nombre;
            $objDatosEmpresa->moneda = $request->moneda;
            $objDatosEmpresa->pais = $request->pais;
            $objDatosEmpresa->ruc_empresa = $request->ruc;
            $objDatosEmpresa->direccion_empresa = $request->direccion;
            $objDatosEmpresa->correo_empresa = $request->correo_empresa;

            if ($objDatosEmpresa->save()) {
                $model = Empresa::all()->last();
                crear_log("empresa",$model->id_empresa,session::get('party_id'),"El usuario ha actualizado los datos de la empresa");
                $success = true;
                $msg = '<div class="alert alert-success" role="alert" style="margin: 0">
                      Se ha guardado los datos de la empresa con éxito.!
                </div>';
            }else{
                $msg = '<div class="alert alert-danger" role="alert" style="margin: 0">
                      ha ocurrido un inconveniente al guardar los datos de la empresa, intente nuevamente.!
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

    public function storeDatosRepresentante(Request $request){

        $validar = Validator::make($request->all(), [
            'nombre_representante' => 'required',
            'apellido_representante' => 'required',
            'identificacion_representante' => 'required',
            'telefono' => 'required',
            'correo_representante' => 'required'
        ]);
        $success = false;

        if (!$validar->fails()) {
            ($this->configuracionEmpresa !== null)
                ? $objDatosEmpresa = Empresa::find($this->configuracionEmpresa->id_empresa)
                : $objDatosEmpresa = new Empresa;

            $objDatosEmpresa->nombre_representante = $request->nombre_representante;
            $objDatosEmpresa->apellidos_representante = $request->apellido_representante;
            $objDatosEmpresa->identificacion_representante = $request->identificacion_representante;
            $objDatosEmpresa->telefono_representante = $request->telefono;
            $objDatosEmpresa->correo_representante = $request->correo_representante;

            if ($objDatosEmpresa->save()) {
                $model = Empresa::all()->last();
                crear_log("empresa",$model->id_empresa,session::get('party_id'),"El usuario ha actualizado los datos de la empresa");
                $success = true;
                $msg = '<div class="alert alert-success" role="alert" style="margin: 0">
                      Se ha guardado los datos del representante de la empresa con éxito.!
                </div>';
            }else{
                $msg = '<div class="alert alert-danger" role="alert" style="margin: 0">
                      ha ocurrido un inconveniente al guardar los datos del representante de la empresa, intente nuevamente.!
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

    public function storeVisualizacion(Request $request){

        $validar = Validator::make($request->all(), [
            'imagen_login' => 'mimes:jpeg,png,JPEG,JPG,PNG|dimensions:min_width=1400,min_height=900',
            'logo_empresa' => 'mimes:jpeg,png,JPEG,JPG,PNG|dimensions:min_width=50,min_height=50'
        ],[
            'imagen_login.dimensions' => 'La imagen para el fonodo del login debe ser mínimo de 1400px por 900px',
            'logo_empresa.dimensions' => 'La imagen para el logo de la empresa debe ser mínimo de 50px por 50px',

        ]);

        $success = false;
        $msg = '<div class="alert alert-danger" role="alert" style="margin: 0">
                      ha ocurrido un inconveniente al guardar las imágenes, intente nuevamente.!
                </div>';

        if (!$validar->fails()) {

            ($this->configuracionEmpresa !== null)
                ? $objDatosEmpresa = Empresa::find($this->configuracionEmpresa->id_empresa)
                : $objDatosEmpresa = new Empresa;

            if($request->has('imagen_login')){
                $archivo = $request->file('imagen_login');
                $imagen  =  mt_rand().$archivo->getClientOriginalName();
                \Storage::disk('public')->put($imagen,  \File::get($archivo));
                $objDatosEmpresa->img_fondo_login = $imagen;
            }


            if($request->has('logo_empresa')){
                $archivo = $request->file('logo_empresa');
                $imagen  =  mt_rand().$archivo->getClientOriginalName();
                \Storage::disk('public')->put($imagen,  \File::get($archivo));
                $objDatosEmpresa->logo_empresa = $imagen;
            }


            $success = true;
            $objDatosEmpresa->save();
            $msg = '<div class="alert alert-success" role="alert" style="margin: 0">
                      Se han guardado las imágenes con éxito.!
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

    public function storeTerminosCondiciones(Request $request){

        $validar = Validator::make($request->all(), [
            'terminos_condiciones' => 'required'
        ]);
        $success = false;

        if (!$validar->fails()) {
            ($this->configuracionEmpresa !== null)
                ? $objDatosEmpresa = Empresa::find($this->configuracionEmpresa->id_empresa)
                : $objDatosEmpresa = new Empresa;

            $objDatosEmpresa->terminos_condiciones = $request->terminos_condiciones;

            $success = false;
            $msg = '<div class="alert alert-danger" role="alert" style="margin: 0">
                      ha ocurrido un inconveniente al guardar los terminos y condiciones, intente nuevamente.!
                    </div>';

            if($objDatosEmpresa->save()){

                $success = true;
                $msg = '<div class="alert alert-success" role="alert" style="margin: 0">
                      Se han guardado los terminos y condiciones con éxito.!
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

    public function storeVariablesInvetario(Request $request){

        $validar = Validator::make($request->all(), [
            'intervalo_inventario' => 'required',
            'cantidad' => 'required',
            'correo_1' => 'required'
        ],[
            'intervalo_inventario.required' => 'Debe seleccionar el intervalo de la variable del inventario',
            'cantidad.required' => 'Debe escribir la cantidad del intervalo',
            'correo_1.required' => 'Debe colocar al menos un correo para notificar  sobre el inventario'
        ]);
        $success = false;

        if (!$validar->fails()) {
            ($this->configuracionEmpresa !== null)
                ? $objDatosEmpresa = Empresa::find($this->configuracionEmpresa->id_empresa)
                : $objDatosEmpresa = new Empresa;

            $objDatosEmpresa->intervalo_inventario = $request->intervalo_inventario;
            $objDatosEmpresa->cantidad_intervalo = $request->cantidad;
            $objDatosEmpresa->correo1_notificacion_intervalo = $request->correo_1;
            $objDatosEmpresa->correo2_notificacion_intervalo = $request->correo_2;
            $objDatosEmpresa->correo3_notificacion_intervalo = $request->correo_3;

            $success = false;
            $msg = '<div class="alert alert-danger" role="alert" style="margin: 0">
                      ha ocurrido un inconveniente al guardar la configuración de las notifiaciones del inventario, intente nuevamente.!
                    </div>';

            if($objDatosEmpresa->save()){

                $success = true;
                $msg = '<div class="alert alert-success" role="alert" style="margin: 0">
                      Se han guardado la configuración de las notifiaciones del inventario con éxito.!
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

}

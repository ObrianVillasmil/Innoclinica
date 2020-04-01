<?php

namespace App\Http\Controllers;

use App\Modelos\DetalleTratamiento;
use App\Modelos\Productos;
use App\Modelos\Tratamiento;
use App\Modelos\TratamientoSolicitado;
use Illuminate\Http\Request;
use PhpXmlRpc\Value;
use PhpXmlRpc\Request as ResqClientXmlrpc;
use PhpXmlRpc\Client;
use Validator;

class CotizacionController extends Controller
{
    public function inicio(Request $request){
        return view('cotizacion.list',[
            'url' => $request->path(),
            'titulo' => ['titulo'=>'Cotizaciones','sub_titulo'=>'Cotice algún tratamiento'],
            'usuario' => getParty(session('party_id')),
            'tratamientos' => Tratamiento::all()
        ]);
    }

    public function cotizador(Request $request){

        $detalleTratamiento = getDetalleTratamiento($request->id_tratamiento);

        //if(isset($request->id_tratamiento_solicitado))
        // Aquí va la distribución del tratamiento del tratamiento solicitado


        $datosCotizacion = isset($detalleTratamiento) ? $detalleTratamiento->datos_cotizacion() : null;

        return view('cotizacion.partials.cotizador',[
            'prodcuto' => isset($datosCotizacion)  ? $datosCotizacion['producto'] : null,
            'tratamiento' => getTratamiento($request->id_tratamiento),
            'cotiza' => 'procesos'
        ]);
    }

    public function cotizadorProductos(){

        return view('cotizacion.partials.cotizador_productos',[
            'prodcutos' => Productos::all()
        ]);
    }

    public function solicitarCotizacion(Request $request){

        $validar = Validator::make($request->all(), [
            'product_id' => 'required',
            'cantidad' => 'required',
            'forma_pago' => 'required'
        ], [
            'product_id.required' => "No se encontró el producto a cotizar",
            'cantidad.required' => "No se ingresó la cantidad de prodcutos a cotizar",
            'forma_pago.required' => "No se ingresó la forma de pago"

           ]);

        $success = false;

        if (!$validar->fails()) {
            $party = getParty(session('party_id'));
            $productId = new Value($request->product_id);
            $cantidad = new Value($request->cantidad);
            $formaPago = new Value($request->forma_pago);


            $client = new Client('http://innoclinica.evalua.com.ec:8081/ventas/control/xmlrpc');
            $client->setCredentials('tratamientos','Trat-2019');
            $response = $client->send(new ResqClientXmlrpc('cotizarProducto',[
                $productId, $cantidad, $formaPago
            ]));


            crear_log("cotizacion", session('party_id'), session('party_id'), 'El usuario '.$party->person->first_name." ".$party->person->last_name." está interezado en una cotización del producto ".getProducto($request->product_id)->product_name);

            return response()->json([
                'valor'=> $response->faultCode() ==0 ? json_decode($response->value()->me['struct']['res']->me['string']) : $response->faultCode() !=0,
                'codigo' =>$response->faultCode(),
                'mensaje' =>  $response->faultString()
            ]);

        }else{
            $success = true;
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

    public function crearCotizacion(Request $request){

        $person = getParty(session('party_id'));
        $direccionId = "";
        $direccionEnvioId = "";
        $telefonoId ="";
        $emailId = "";
        $tratamientSolicitado = TratamientoSolicitado::find($request->id_tratamiento_solicitado);
        $medicoId = $tratamientSolicitado->id_doctor;
        $envioDomId =$request->tipo_envio;

        foreach($person->party_contact_mech as $contactMech){
            if($contactMech->contact_mech->contact_mech_type_id === "POSTAL_ADDRESS"){
                $direccionId = $contactMech->contact_mech->contact_mech_id;
                $direccionEnvioId= $contactMech->contact_mech->contact_mech_id;
            }

            if($contactMech->contact_mech->contact_mech_type_id === "TELECOM_NUMBER")
                $telefonoId = $contactMech->contact_mech->contact_mech_id;

            if($contactMech->contact_mech->contact_mech_type_id === "EMAIL_ADDRESS")
                $emailId = $contactMech->contact_mech->contact_mech_id;
        }

        //dump('direccionId => '.$direccionId. 'direccionEnvioId => '.$direccionEnvioId. ' telefonoId=> '.$telefonoId.' emailId=> '.$emailId. ' medicoId=> '.$medicoId. ' envioDomId=> '.$envioDomId.' tipoPagoId=> '.$request->forma_pago);

        if($direccionId!="" && $direccionEnvioId!="" && $telefonoId!="" && $emailId!="" && $medicoId!="" && $envioDomId!="" && isset($request->forma_pago)){
            $success = true;
            $clienteId  = new Value($person->party_id);
            $direccionId = new Value($direccionId);
            $direccionEnvioId = new Value($direccionEnvioId);
            $telefonoId = new value($telefonoId);
            $emailId = new value($emailId);
            $medicoId = new value($medicoId);
            $tipoPago  = new value($request->forma_pago);
            $envioDomId = new value($envioDomId);
            $data=[];
            foreach($request->product as $pro) {
                //dd(' clienteId=>'.$person->party_id. ' cantidad=>'.$pro['cantidad'].' productId=> '.$pro['product_id']);

                $productId = new value($pro['product_id']);
                $cantidad = new value($pro['cantidad']);
                $client = new Client('http://innoclinica.evalua.com.ec:8081/ventas/control/xmlrpc');
                $client->setCredentials('tratamientos','Trat-2019');
                $response = $client->send(new ResqClientXmlrpc('crearCotizacion',[
                    $clienteId,$direccionId,$direccionEnvioId,$telefonoId,$emailId,$medicoId,$productId,$cantidad,$tipoPago,$envioDomId
                ]));
                $data[] = response()->json([
                    'valor'=> $response->faultCode() ==0 ? json_decode($response->value()->me['struct']['res']->me['string']) : false,
                    'codigo' =>$response->faultCode(),
                    'mensaje' =>  $response->faultString()
                ]);
            }

            $msg = $data;

        }else{
            $success = false;

            if($direccionId=="")
                $msg = "El usuario no tiene una dirección de envío registrada en el sistema";

            if($telefonoId=="")
                $msg = "El usuario no tiene un teléfono registrado en el sistema";

            if($emailId=="")
                $msg = "El usuario no tiene un correo registrado en el sistema";

            if($medicoId=="")
                $msg = "El tratamiento no tiene un doctor asignado";

            if($envioDomId=="")
                $msg = "Seleccione el tipo de envío";

            if(!isset($request->forma_pago))
                $msg = "Seleccione la forma de pago";

        }
        return[
            'success' =>$success,
            'msg' =>$msg
        ];

    }
}

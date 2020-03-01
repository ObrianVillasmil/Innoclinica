<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpXmlRpc\Value;
use PhpXmlRpc\Request as resq;
use PhpXmlRpc\Client;



class PruebaController extends Controller
{

    public function prueba(){
        /*
         *   $request = xmlrpc_encode_request("cotizarProducto", array("SIIONCOBCG40","1","CREDITO"));
        $auth = base64_encode("tratamientos:Trat-2019");

        $header = "Content-Type: text/xml\r\nAuthorization: Basic $auth" ;
        $context = stream_context_create(array('http' => array(
            'method' => "POST",
            'header' => $header,
            'content' => $request
        )));

        $url = "http://innoclinica.evalua.com.ec:8081/ventas/control/xmlrpc";

        $file = file_get_contents($url, false, $context);
        $response = xmlrpc_decode($file);

        if (xmlrpc_is_fault($response)) {
            "xmlrpc: $response[faultString] ($response[faultCode])";
        } else {
            print_r(json_decode($response["res"]));
        } */
        $productId = 'SIIONCOBCG40';
        $cant = 1;
        $fpago = "CREDITO";

        $client = new Client('http://innoclinica.evalua.com.ec:8081/ventas/control/xmlrpc');
        $client->setCredentials('tratamientos','Trat-2019');
        $response = $client->send(new resq('cotizarProducto',array(new Value($productId),new Value($cant),new Value($fpago))));

        if($response->faultCode() !=0)
            /*error*/ dd($response->faultString());
        else
            dd(json_decode($response->value()->me['struct']['res']->me['string']));

    }
}

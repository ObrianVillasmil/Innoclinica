@php
    $pdf = App::make('dompdf.wrapper');
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
        'TEXTO_DOCUMENTO' =>$dataProceso['cuerpo']
    ];
    $pdf->loadHTML(crearDocumento($datos,false));
    $n = rand();
    $pdf->save(public_path().'/'.$n.'_archivo_pdf_'.session('party_id').'.pdf');
@endphp


<embed src="{{'/'.$n.'_archivo_pdf_'.session('party_id').'.pdf'}}" type="application/pdf" width="100%" height="600px" />
@php
    use Dompdf\Dompdf;
    $pdf = new Dompdf;
    $html = view('tratamiento.partials.formato_distribucion',["idTratamiento" => $idTratamiento]);
    $pdf->loadHTML($html);
    //$pdf->setPaper('A4', 'Landscape');
    $pdf->render();
    $pdf->stream('distribución del tratamiento',["Attachment" => 0]);
@endphp

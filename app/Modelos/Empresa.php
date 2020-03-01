<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $table= "empresa";

    protected $primaryKey = "id_empresa";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'nombre_empresa',
        'moneda',
        'pais',
        'ruc_empresa',
        'correo_empresa',
        'direccion_empresa',
        'nombre_representante',
        'apellidos_representante',
        'identificacion_representante',
        'telefono_representante',
        'correo_representante',
        'img_fondo_login',
        'logo_empresa',
        'terminos_condiciones',
        'intervalo_inventario',
        'cantidad_intervalo'
    ];
}

<?php

namespace App\Http\Controllers;

use App\Modelos\Menu;
use App\Modelos\Permisos;
use App\Modelos\SubMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Modelos\RoleType;
use Validator;


class MenuController extends Controller
{
    public function inicio(Request $request){
        return view('menu.inicio',[
            'url' => $request->path(),
            'titulo' => ['titulo'=>'Configuraciones','sub_titulo'=>'menú'],
            'menu' => Menu::orderBy('nombre','asc')->get(),
            'subMenu' => SubMenu::orderBy('nombre','asc')->get(),
            'usuario' => getParty((int)session::get('party_id')),
            'roles' => RoleType::orderBy('description','asc')->get()
        ]);
    }

    public function addMenu(Request $request){
        return view('menu.partials.add_menu',[
            'menu' => Menu::where('id_menu',$request->id_menu)->first()
        ]);
    }

    public function storeMenu(Request $request){

        $validar = Validator::make($request->all(), [
            'nombre' => 'required',
            'icono' => 'required',
            'path' => 'required'
        ]);
        $success = false;

        if (!$validar->fails()) {

            if(!empty($request->id_menu)){
                $objMenu = Menu::find($request->id_menu);
                $accion = "Update";
            }else{
                $objMenu = new Menu;
                $accion =  "Insert";
            }

            $objMenu->nombre = $request->nombre;
            $objMenu->icono  = $request->icono;
            $objMenu->path   = $request->path;

            if($objMenu->save()){
                $model = Menu::all()->last();
                crear_log("menu",$model->id_menu,1,$accion);
                $success = true;
                $msg = '<div class="alert alert-success" role="alert" style="margin: 0">
                      Se ha guardado el nuevo menú con éxito.!
                </div>';
            }else{
                $msg = '<div class="alert alert-danger" role="alert" style="margin: 0">
                      ha ocurrido un inconveniente al guardar el menú, intente nuevamente.!
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

    public function deleteMenu(Request $request){

        Permisos::where('id_menu',$request->id_menu)->delete();
        SubMenu::where('id_menu',$request->id_menu)->delete();
        Menu::destroy($request->id_menu);
        crear_log('menu',$request->id_menu,1,'Delete');
        $success = true;
        $msg = '<div class="alert alert-success" role="alert" style="margin: 0">
                    Se ha eliminado el menú, éxitosamente.!
                </div>';

        return [
            'success' =>$success,
            "msg" => $msg
        ];
    }

    public function addSubMenu(Request $request){
        return view('menu.partials.add_sub_menu',[
            'menu' => Menu::all(),
            'subMenu' => SubMenu::where('id_sub_menu',$request->id_sub_menu)->first()
        ]);
    }

    public function storeSubMenu(Request $request){

        $validar = Validator::make($request->all(), [
            'nombre' => 'required',
            'icono' => 'required',
            'menu' => 'required',
            'path' => 'required'
        ]);
        $success = false;

        if (!$validar->fails()) {

            if(!empty($request->id_sub_menu)){
                $objSubMenu = SubMenu::find($request->id_sub_menu);
                $accion = "Update";
            }else{
                $objSubMenu = new SubMenu();
                $accion =  "Insert";
            }

            $objSubMenu->id_menu = $request->menu;
            $objSubMenu->nombre  = $request->nombre;
            $objSubMenu->icono   = $request->icono;
            $objSubMenu->path    = $request->path;

            if($objSubMenu->save()){
                $model = SubMenu::all()->last();
                crear_log("sub_menu",$model->id_sub_menu,1,$accion);
                $success = true;
                $msg = '<div class="alert alert-success" role="alert" style="margin: 0">
                      Se ha guardado el nuevo menú con éxito.!
                </div>';
            }else{
                $msg = '<div class="alert alert-danger" role="alert" style="margin: 0">
                      ha ocurrido un inconveniente al guardar el menú, intente nuevamente.!
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

    public function deleteSubMenu(Request $request){

        SubMenu::destroy($request->id_sub_menu);
        crear_log('menu',$request->id_sub_menu,1,'Delete');
        $success = true;
        $msg = '<div class="alert alert-success" role="alert" style="margin: 0">
                    Se ha eliminado el sub menú, éxitosamente.!
                </div>';

        return [
            'success' =>$success,
            "msg" => $msg
        ];
    }

    public function asignarPermisos(Request $request){

        return view('menu.partials.add_permisos',[
            'rol' => $request->id_rol,
            'menus' => Menu::orderBy('nombre','asc')->get(),
            'permisos' => Permisos::where('id_rol',$request->id_rol)->get()
        ]);
    }

    public function storePermisos(Request $request)
    {
        $validar = Validator::make($request->all(), [
            //'arrMenu' => 'required|Array',
            'id_rol' => 'required'
        ]);
        $success = false;

        if (!$validar->fails()) {

            $cantPemisos = Permisos::where('id_rol',$request->id_rol)->count();

            $cantPemisos > 0
                ? $delete_menu = Permisos::where('id_rol',$request->id_rol)->delete()
                : $delete_menu = true;

            if($delete_menu){
                $i=0;
                $success = true;
                $msg = '<div class="alert alert-success" role="alert" style="margin: 0">
                                Se ha guardado los permisos con éxito.!
                            </div>';
                if(isset($request->arrMenu)){

                    foreach ($request->arrMenu as $menu){
                        $objPermiso = new Permisos;
                        $objPermiso->id_menu = $menu['id_menu'];
                        $objPermiso->id_rol = $request->id_rol;
                        $objPermiso->save() ? $i++ : "";
                    }

                    if($i === count($request->arrMenu)){
                        $success = true;
                        $msg = '<div class="alert alert-success" role="alert" style="margin: 0">
                                    Se ha guardado los permisos con éxito.!
                                </div>';
                    }else{
                        $msg = '<div class="alert alert-danger" role="alert" style="margin: 0">
                                    ha ocurrido un inconveniente al guardar los permisos, intente nuevamente.!
                                </div>';
                    }
                }
            }else{
                $msg = '<div class="alert alert-danger" role="alert" style="margin: 0">
                           ha ocurrido un inconveniente al guardar los permisos, intente nuevamente.!
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

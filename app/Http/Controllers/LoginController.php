<?php

namespace App\Http\Controllers;

use App\Modelos\Geo;
use App\Modelos\Party;
use App\Modelos\PartyIdentification;
use App\Modelos\PartyIdentificationType;
use App\Modelos\PartyRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Validator;
use Illuminate\Support\Facades\DB;
use App\Modelos\UserLogin;
use App\Modelos\Person;
use App\Modelos\RoleType;
use App\Modelos\ContactMech;
use App\Modelos\PartyContactMech;
use App\Modelos\TelecomNumber;
use App\Mail\ConfirmarNuevoUsuario;
use App\Mail\ReiniciarContrasena;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Modelos\Icono;


class LoginController extends Controller
{

  public function index(){
      return view('login.inicio');
  }

  public function registro(Request $request){
        return view('registro.inicio',
            [
                'pais'=> Geo::where('geo_type_id','COUNTRY')->get(),
                'tipoIdentificacion' => PartyIdentificationType::all(),
            ]);
  }

  public function terminosCondiciones(){
      return view('registro.partials.terminos_condiciones');
  }

  public function storeRegistro(Request $request){

      Validator::make($request->all(), [
          'g-recaptcha-response' => 'required|captcha',
          'contrasena' => 'required|min:4|max:10|confirmed',
          'contrasena_confirmation' => 'required|min:4|max:10',
          'nombre' => 'required|min:4|max:30',
          'apellido' => 'required',
          'telefono' => 'required',
          'correo' => 'required|email|unique:user_login,email'
      ],[
          'g-recaptcha-response.required' => 'El captcha es obligatorio.',
          'g-recaptcha-response.captcha' => 'El código ingresado es incorrecto.'
      ])->validate();

      $objParty = new Party;
      $objParty->party_id = getSequenceValueItem('Party');
      $objParty->party_type_id = "PERSON";

      if($objParty->save()) {

          $model = Party::all()->last();
          crear_log("party", $model->party_id, $objParty->party_id, 'Creación de nuevo party_id');

          $objUserLogin = new UserLogin;
          $objUserLogin->user_login_id = $objParty->party_id;
          $objUserLogin->current_password = "{SHA}" . sha1($request->contrasena);
          $objUserLogin->enabled = "N";
          $objUserLogin->created_stamp = now()->toDateString();
          $objUserLogin->party_id = $objParty->party_id;
          $objUserLogin->email = $request->correo;
          $objUserLogin->token = hash("sha512", $objUserLogin->current_password . now()->toDateString() . 'Innofarm');

          if ($objUserLogin->save()) {

              $model = UserLogin::all()->last();
              crear_log("user_login", $model->user_login_id, $objParty->party_id, 'Creación de nuevo usuario');

              //$objPartyRole = new PartyRole;
              //$objPartyRole->party_id = $objUserLogin->party_id;
              //$objPartyRole->role_type_id = $request->rol;

              //if ($objPartyRole->save()) {

                      //$model = PartyRole::all()->last();
                      //crear_log("party_role", $model->role_type_id, $objParty->party_id, 'Creación de nuevo party_role');
                      $objPerson = new Person;
                      $objPerson->party_id = $objUserLogin->party_id;
                      $objPerson->first_name = $request->nombre;
                      $objPerson->last_name = $request->apellido;
                      //$objPerson->birth_date = $request->fecha_nacimiento;
                      //$objPerson->nacionalidad = $request->nacionalidad;

                      if ($objPerson->save()) {

                          $model = Person::all()->last();
                          crear_log("person", $model->party_id, $objParty->party_id, 'Creación de una nueva persona');
                          //$objPartyIdentification = new PartyIdentification;
                          //$objPartyIdentification->party_id = $objUserLogin->party_id;
                          //$objPartyIdentification->party_identification_type_id = $request->tipo_identificacion;
                          //$objPartyIdentification->id_value = $request->identificacion;

                          //if ($objPartyIdentification->save()) {

                              //$model = PartyIdentification::all()->last();
                              //crear_log("person", $model->party_id, $objParty->party_id, 'Creación de una nueva identificación de persona');

                              $objContactMetch = new ContactMech;
                              $objContactMetch->contact_mech_id = getSequenceValueItem('ContactMech');
                              $objContactMetch->contact_mech_type_id = 'EMAIL_ADDRESS';
                              $objContactMetch->info_string = $request->correo;

                              if ($objContactMetch->save()) {

                                  setSequenceValueItem('ContactMech');
                                  $model = ContactMech::all()->last();
                                  crear_log("contact_mech", $model->contact_mech_id, $objParty->party_id, 'Creación de una nuevo mail');
                                  $objPartyContactMech = new PartyContactMech;
                                  $objPartyContactMech->party_id = $objUserLogin->party_id;
                                  $objPartyContactMech->contact_mech_id = $objContactMetch->contact_mech_id;
                                  //$objPartyContactMech->role_type_id = $request->rol;
                                  $objPartyContactMech->from_date = now()->format('Y/m/d');

                                  if ($objPartyContactMech->save()) {

                                      $model = PartyContactMech::all()->last();
                                      crear_log("party_contact_mech", $model->contact_mech_id, $objParty->party_id, 'Creación de una nueva relación entre mail y el party_id');
                                      $objContactMetch = new ContactMech;
                                      $objContactMetch->contact_mech_id = getSequenceValueItem('ContactMech');
                                      $objContactMetch->contact_mech_type_id = 'TELECOM_NUMBER';

                                      if ($objContactMetch->save()) {

                                          setSequenceValueItem('ContactMech');
                                          $model = ContactMech::all()->last();
                                          crear_log("contact_mech", $model->contact_mech_id, $objParty->party_id, 'Creación de una nuevo contact_mech para número de teléfono');
                                          $objTelecomNumber = new TelecomNumber;
                                          $objTelecomNumber->contact_mech_id = $objContactMetch->contact_mech_id;
                                          $objTelecomNumber->country_code = "593";
                                          $objTelecomNumber->contact_number = $request->telefono;

                                          if ($objTelecomNumber->save()) {

                                              $model = TelecomNumber::all()->last();
                                              crear_log("telecom_number", $model->contact_mech_id, $objParty->party_id, 'Creación de un nuevo número de teléfono');
                                              $objPartyContactMech = new PartyContactMech;
                                              $objPartyContactMech->party_id = $objUserLogin->party_id;
                                              $objPartyContactMech->contact_mech_id = $objContactMetch->contact_mech_id;
                                              //$objPartyContactMech->role_type_id = $request->rol;
                                              $objPartyContactMech->from_date = now()->format('Y/m/d');

                                              if ($objPartyContactMech->save()) {

                                                  $model = PartyContactMech::all()->last();
                                                  crear_log("party_contact_mech", $model->contact_mech_id, $objParty->party_id, 'Creación de una nueva relación entre teléfono y el party_id');
                                                  ////$objContactMetch = new ContactMech;
                                                  ////$objContactMetch->contact_mech_id = getSequenceValueItem('ContactMech');
                                                  ////$objContactMetch->contact_mech_type_id = 'POSTAL_ADDRESS';

                                                  ////if ($objContactMetch->save()) {

                                                      ////setSequenceValueItem('ContactMech');
                                                      ////$model = ContactMech::all()->last();
                                                      ////crear_log("contact_mech", $model->contact_mech_id, $objParty->party_id, 'Creación de una nuevo contact_mech pára dirección');
                                                      ////$objPostalAddress = new PostalAddress;
                                                      ////$objPostalAddress->address1 = $request->direccion;
                                                      ////$objPostalAddress->country_geo_id = $request->pais;
                                                      ////$objPostalAddress->contact_mech_id = $objContactMetch->contact_mech_id;
                                                      //$objPostalAddres->state_province_geo_id = $request->id_provincia;
                                                      //$objPostalAddres->city                  = $request->ciudad;
                                                      ////if ($objPostalAddress->save()) {
                                                          ////$model = PostalAddress::all()->last();
                                                          ////crear_log("postal_address", $model->contact_mech_id, $objParty->party_id, 'Creación de una nueva direccion del usuario');
                                                          ////$objPartyContactMech = new PartyContactMech;
                                                          ////$objPartyContactMech->party_id = $objUserLogin->party_id;
                                                          ////$objPartyContactMech->party_contact_mech_id = $objContactMetch->contact_mech_id;
                                                          ////$objPartyContactMech->role_type_id = $request->rol;
                                                          ////$objPartyContactMech->from_date = now()->format('Y/m/d');
                                                          ////if ($objPartyContactMech->save()) {

                                                              if (setSequenceValueItem('Party'))
                                                                  $model = PartyContactMech::all()->last();

                                                              crear_log("party_contact_mech", $model->party_id, $objParty->party_id, 'Creación de una nueva relación entre dirección y el party_id');
                                                                              //$request->correo;
                                                              Mail::to($request->correo)->send(new ConfirmarNuevoUsuario($objUserLogin->token, $objUserLogin->party_id));
                                                              flash('Un correo electrónico de confirmación ha sido enviado al ingresado en el registro, por favor verifiquelo y siga las instrucciones para ingresar en su cuenta')->success()->important();
                                                              /*$basic  = new \Nexmo\Client\Credentials\Basic(env('NEXMO_KEY'), env('NEXMO_SECRET'));
                                                              $client = new \Nexmo\Client($basic);
                                                              //$url = url(url('/')."/autenticar/".$objUserLogin->party_id."/".$objUserLogin->token);
                                                              //$shortUrl = Bitly::getUrl($url);
                                                              $client->message()->send([
                                                                  'to' => '593983537432',
                                                                  'from' => 'Innoclinica',
                                                                  'text' => 'Bienvenido a Innoclinica haga clic aquí para activar su cuenta"'
                                                              ]);*/
                                                              return redirect('');

                                                          ////} else {
                                                              ////setSequenceValueItem('ContactMech', 'disminuir');
                                                              ////PostalAddress::destroy($objContactMetch->contact_mech_id);
                                                              ////TelecomNumber::destroy($objContactMetch->contact_mech_id);
                                                              ////PartyContactMech::destroy($objUserLogin->party_id);
                                                              ////ContactMech::destroy($objContactMetch->contact_mech_id);
                                                              ////PartyIdentification::destroy($objUserLogin->party_id);
                                                              ////Person::destroy($objUserLogin->party_id);
                                                              ////PartyRole::destroy($objUserLogin->party_id);
                                                              ////Party::destroy($objUserLogin->party_id);
                                                              ////UserLogin::destroy($request->usuario);
                                                          //}
                                                      ////} else {
                                                          ////setSequenceValueItem('ContactMech', 'disminuir');
                                                          ////TelecomNumber::destroy($objContactMetch->contact_mech_id);
                                                          ////PartyContactMech::destroy($objUserLogin->party_id);
                                                          ////ContactMech::destroy($objContactMetch->contact_mech_id);
                                                          ////PartyIdentification::destroy($objUserLogin->party_id);
                                                          ////Person::destroy($objUserLogin->party_id);
                                                          ////PartyRole::destroy($objUserLogin->party_id);
                                                          ////Party::destroy($objUserLogin->party_id);
                                                          ////UserLogin::destroy($request->usuario);
                                                      //}
                                                  ////} else {
                                                      ////setSequenceValueItem('ContactMech', 'disminuir');
                                                      ////TelecomNumber::destroy($objContactMetch->contact_mech_id);
                                                      ////PartyContactMech::destroy($objUserLogin->party_id);
                                                      ////ContactMech::destroy($objContactMetch->contact_mech_id);
                                                      ////PartyIdentification::destroy($objUserLogin->party_id);
                                                      ////Person::destroy($objUserLogin->party_id);
                                                      ////PartyRole::destroy($objUserLogin->party_id);
                                                      ////Party::destroy($objUserLogin->party_id);
                                                      ////UserLogin::destroy($request->usuario);
                                                  ////}
                                              } else {
                                                  setSequenceValueItem('ContactMech', 'disminuir');
                                                  TelecomNumber::destroy($objContactMetch->contact_mech_id);
                                                  PartyContactMech::destroy($objUserLogin->party_id);
                                                  ContactMech::destroy($objContactMetch->contact_mech_id);
                                                  PartyIdentification::destroy($objUserLogin->party_id);
                                                  Person::destroy($objUserLogin->party_id);
                                                  PartyRole::destroy($objUserLogin->party_id);
                                                  Party::destroy($objUserLogin->party_id);
                                                  UserLogin::destroy($request->usuario);
                                              }
                                          } else {
                                              setSequenceValueItem('ContactMech', 'disminuir');
                                              PartyContactMech::destroy($objUserLogin->party_id);
                                              ContactMech::destroy($objContactMetch->contact_mech_id);
                                              PartyIdentification::destroy($objUserLogin->party_id);
                                              Person::destroy($objUserLogin->party_id);
                                              PartyRole::destroy($objUserLogin->party_id);
                                              Party::destroy($objUserLogin->party_id);
                                              UserLogin::destroy($request->usuario);
                                          }
                                      } else {
                                          setSequenceValueItem('ContactMech', 'disminuir');
                                          PartyContactMech::destroy($objUserLogin->party_id);
                                          ContactMech::destroy($objContactMetch->contact_mech_id);
                                          PartyIdentification::destroy($objUserLogin->party_id);
                                          Person::destroy($objUserLogin->party_id);
                                          PartyRole::destroy($objUserLogin->party_id);
                                          Party::destroy($objUserLogin->party_id);
                                          UserLogin::destroy($request->usuario);
                                      }
                                  } else {
                                      setSequenceValueItem('ContactMech', 'disminuir');
                                      ContactMech::destroy($objContactMetch->contact_mech_id);
                                      PartyIdentification::destroy($objUserLogin->party_id);
                                      Person::destroy($objUserLogin->party_id);
                                      PartyRole::destroy($objUserLogin->party_id);
                                      Party::destroy($objUserLogin->party_id);
                                      UserLogin::destroy($request->usuario);
                                  }
                              } else {
                                  PartyIdentification::destroy($objUserLogin->party_id);
                                  Person::destroy($objUserLogin->party_id);
                                  PartyRole::destroy($objUserLogin->party_id);
                                  Party::destroy($objUserLogin->party_id);
                                  UserLogin::destroy($request->usuario);
                              }
                          //} else {
                          //    Person::destroy($objUserLogin->party_id);
                          //    PartyRole::destroy($objUserLogin->party_id);
                          //    Party::destroy($objUserLogin->party_id);
                          //    UserLogin::destroy($request->usuario);
                          //}
                      } else {
                          PartyRole::destroy($objUserLogin->party_id);
                          Party::destroy($objUserLogin->party_id);
                          UserLogin::destroy($request->usuario);
                      }
                  //} else {
                  //Party::destroy($objUserLogin->party_id);
                  //UserLogin::destroy($request->usuario);
              //}
          }else {
              Party::destroy($objParty->party_id);
          }
      }else{
          flash('Hubo un error al tratar de guardar el usuario intente nuevamente')->error()->important();
          return redirect('registro');
      }
  }

  public function autenticarUsuario(Request $request,$party_id,$token){

      $usuario = UserLogin::where('party_id',$party_id)->first();
      if($usuario !== null){
          if(Carbon::parse($usuario->created_stamp)->diffInDays(now()->toDateString()) < 3){
              if ($usuario->token === $token) {

                  $userLogin = UserLogin::find($usuario->user_login_id);
                  $userLogin->update(['token' => "", 'enabled' => "Y"]);
                  session::put('logeado', true);
                  session::put('party_id', $party_id);
                  crear_log("user_login", $usuario->user_login_id, $party_id, "Autenticación exitosa un nuevo usuario");
                  flash('Bienvenido a nuestro sistema, su usuario ha sido confimado con éxito')->success()->important();
                  return redirect('');

              } else {
                  flash('El token enviado por correo electrónico no coincide con el guardado en nuestro sistema para este usuario')->error()->important();
                  return redirect('');
              }
          }else{
              flash('El token enviado para a confirmación de su cuenta ha caducado')->error()->important();
              return redirect('');
          }
      }else{
          flash('El usuario no está aún registrado en nuestro sistema')->error()->important();
          return redirect('');
      }

  }

  public function login(Request $request){


      Validator::make($request->all(), [
          'g-recaptcha-response' => 'required|captcha',
          'usuario' => 'required|min:4',
          'contrasena' => 'required|min:4|max:10',
     ],
     [
         'g-recaptcha-response.required' => 'El captcha es obligatorio.',
         'g-recaptcha-response.captcha' => 'El código ingresado es incorrecto.'
     ])->validate();

     $usuario = UserLogin::where('email',$request->usuario)->get();
     dd($usuario);
     if($usuario !== null){
         if($usuario->enabled === "Y"){
             if($usuario->current_password === "{SHA}".sha1($request->contrasena)){
                 session::put('logeado',true);
                 session::put('party_id',$usuario->party_id);
                 session::put('id_sesion',substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10));
                 session::put('inicio', microtime(true));
                 session::put('activa', 300);
                 crear_log("user_login", $usuario->party_id, $usuario->party_id, "Entrada al sistema del usuario ".getParty($usuario->party_id)->person->first_name." ".getParty($usuario->party_id)->person->last_name);
             }else{
                 flash('El usuario o contraseña no coinciden')->error()->important();
             }
         }else{
             $request->session()->forget(['logueado', 'party_id']);
             flash('El usuario no ha verificado aún su cuenta, o su cuenta esta deshabilitada')->error()->important();
         }
     }else{
         $request->session()->forget(['logueado', 'party_id']);
         flash('El usuario no existe')->error()->important();
     }
      return redirect('')->with('log',true);

  }

  public function logout(Request $request)
  {

      if ($request->session()->has('logeado') && $request->session()->get('logeado')) {
          $archivos = glob("*.pdf");
          foreach ($archivos as $archivo)
              if(file_exists(public_path().'/'.$archivo))
                  unlink(public_path().'/'.$archivo);

          Session::put('logeado', false);
          Session::put('party_id', null);
          Session::flush();
          DB::disconnect();

      };
      return redirect('');
  }

  public function reiniciarContrasena(Request $request){
    return view('login.partials.recuperar_contrasena');
  }

  public function restPass(Request $request){

      Validator::make($request->all(), [
          'g-recaptcha-response' => 'required|captcha',
          'correo' => 'required',
      ], [
          'correo.required' => 'El correo es obligatorio',
          'g-recaptcha-response.required' => 'El captcha es obligatorio.',
          'g-recaptcha-response.captcha' => 'El código ingresado es incorrecto.'
      ])->validate();

      $mail = ContactMech::where('info_string',$request->correo)->first();
      if($mail !== null){
          $usuario = getParty(getContactMech($mail->contact_mech_id)->party_contact_mech->party_id);
          if($usuario->user_login[0]->enabled === "Y"){
              $pass =  substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 7);
              $pass_hash = "{SHA}".sha1($pass);
              $user = UserLogin::find($usuario->user_login[0]->user_login_id);
              $user->update(["current_password"=>$pass_hash]);
                         //$request->correo;
              Mail::to($request->correo)->send(new ReiniciarContrasena($pass,$usuario->user_login[0]->user_login_id));
              flash('Un correo electrónico le ha sido enviado con una nueva contraseña, usted puede cambiar la misma desde la sección perfil')->success()->important();
              crear_log("user_login", $usuario->user_login[0]->user_login_id, $usuario->party_id, "Reinicio de contraseña existosa");
              return redirect('reset_password');
          }else{
              flash('El usuario está deshabilitado')->error()->important();
              return redirect('reset_password');
          }
      }else{
          flash('No existe un usuario registrado con el correo ingresado')->error()->important();
          return redirect('reset_password');
      }
  }

  public function verificarRol(){
        return getParty((int)Session::get('party_id'))->party_role;
  }

  public function addRol()
  {
      return view('usuario.partials.add_rol', [
          'roles' => RoleType::where([
              ['role_type_id', '!=', "ADMIN"],
              ['role_type_id', '!=', "MEDICO_USUARIO"]
          ])->where(function ($q){
              $q->orWhere('role_type_id',"REPRESENTANTE_LEGAL")
                  ->orWhere('role_type_id',"END_USER_CUSTOMER");
                  //->orWhere('role_type_id',"BIOANALISTA")
                  //->orWhere('role_type_id',"CALL_CENTER");
          })->get(),
      ]);
  }

  public function storeRol(Request $request){

      $validar = Validator::make($request->all(), [
          'rol' => 'required',
      ]);
      $success = false;

      if (!$validar->fails()) {

          $objPartyRole = new PartyRole;
          $objPartyRole->party_id = session::get('party_id');
          $objPartyRole->role_type_id = $request->rol;
          $objPartyRole->status = true;

          if($objPartyRole->save()){
              $model = PartyRole::all()->last();
              crear_log("party_role",$model->party_id,$model->party_id,"Creación del rol para el usuario");
              $success = true;
              $msg = '<div class="alert alert-success" role="alert" style="margin: 0">
                      Se ha guardado el rol con éxito, puede continuar.!
                </div>';
          }else{
              $msg = '<div class="alert alert-danger" role="alert" style="margin: 0">
                      ha ocurrido un inconveniente al guardar el rol, intente nuevamente.!
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

  public function acortarurl($url){
        $longitud = strlen($url);
        if($longitud > 10){
            $longitud = $longitud - 30;
            $parte_inicial = substr($url, 0, -$longitud);
            $parte_final = substr($url, -15);
            $nueva_url = $parte_inicial."[ ... ]".$parte_final;
            return $nueva_url;
        }else{
            return $url;
        }
    }

  public  function iconos(){
      return view('usuario.partials.icono',[
            'iconos' => Icono::all()
      ]);
  }

}

<?php

namespace App\Http\Controllers;

use App\Modelos\DocumentoConsolidadoRoleType;
use App\Modelos\PartyRole;
use App\Modelos\PostalAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Modelos\UserLogin;
use App\Modelos\Geo;
use App\Modelos\RoleType;
use App\Modelos\PartyIdentificationType;
use App\Modelos\ContactMech;
use App\Modelos\TelecomNumber;
use App\Modelos\Person;
use App\Modelos\PartyIdentification;
use App\Modelos\PartyRelationship;
use App\Modelos\Party;
use App\Modelos\PartyContactMech;
use App\Modelos\FirmaDigital;
use Validator;


class UsuarioController extends Controller
{
   public function inicio(Request $request){
       return view('usuario.inicio',[
           'url' => $request->path(),
           'titulo' => ['titulo'=>'Configuraciones','sub_titulo'=>'Usuarios'],
           'usuario' => getParty(session('party_id')),
           'usuarios' => UserLogin::where('enabled',$request->estado == null ? "Y" : $request->estado)->orderBy('email','asc')->paginate(20),
           'estado' => $request->estado
       ]);
   }

   public function actualizarEstado(Request $request){
       $success = false;
       $msg = '<div class="alert alert-danger" role="alert" style="margin: 0">
                    ha ocurrido un inconveniente al actualizar el estado del usuario, intente nuevamente.!
               </div>';
       $objUserLogin = UserLogin::where('party_id',$request->party_id);

       if($objUserLogin->update(['enabled'=> $request->estado == "Y" ? "N" : "Y"])){
           crear_log('user_login',$request->party_id,session('party_id'),"Usuario " .getParty($request->party_id)->person->first_name ." ".  getParty($request->party_id)->person->last_name .($request->estado == "Y" ? "deshabilitado" : "habilitado"). " Exitosamente");
           $success = true;
           $msg = '<div class="alert alert-success" role="alert" style="margin: 0">
                    Se ha actualizado el estado del usuario, éxitosamente.!
                </div>';
       }

       return [
           'success' =>$success,
           "msg" => $msg
       ];
   }

   public function perfilUsuario(Request $request, $partyId=null){

       isset($request->partyId)
           ? $partyId = $request->partyId
           : $partyId = $partyId;

       if($partyId !== null && $partyId !== session('party_id')){

           if(isset(getParty(session('party_id'))->party_role)){
               if(getParty(session('party_id'))->party_role->role_type->role_type_id !== "ADMIN"){
                   return view('errors.acceso_denegado',[
                       'url' => $request->path(),
                       'titulo' => ['titulo'=>'Permiso','sub_titulo'=>'Acceso denegado'],
                       'usuario' => getParty(session('party_id'))
                   ]);
               }
           }else{
               return view('errors.acceso_denegado',[
                   'url' => $request->path(),
                   'titulo' => ['titulo'=>'Permiso','sub_titulo'=>'Vaya a la página principal y seleccione su rol'],
                   'usuario' => getParty(session('party_id'))
               ]);
           }
       }

       isset($request->partyId)
           ? $vista = "alerta.partials.datos_usuario"
           : $vista = "usuario.partials.perfil";

       $fir = [];
       $firma = DocumentoConsolidadoRoleType::where('firma',true)->get();

       foreach ($firma as $f) {
           $fir[] = $f->role_type_id;
       }

        return view($vista,[
            'url' => $request->path(),
            'titulo' => ['titulo'=>'Configuraciones','sub_titulo'=>'Usuarios'],
            'usuario' => getParty(session('party_id')),
            'user' => UserLogin::where('party_id',$request->party_id !== null ? $request->party_id : (isset($request->partyId) ? $request->partyId : session('party_id')))->first(),
            'pais'=> Geo::where('geo_type_id','COUNTRY')->get(),
            'rol' => RoleType::where('role_type_id','!=',"ADMIN")->get(),
            'tipoIdentificacion' => PartyIdentificationType::all(),
            'partyId' => $request->partyId,
            'rolesfirma' => $fir
        ]);
   }

   public function actualizarDatosUsuario(Request $request){

       $validar = Validator::make($request->all(), [
           'nombre' => 'required|min:4|max:30',
           'apellido' => 'required',
           'telefono' => 'required',
           'direccion' => 'required',
           'correo' => 'required|email',
           'nacionalidad' => 'required',
           'tipo_identificacion' => 'required',
           'identificacion' => 'required',
           'telefono' => 'required',
           'direccion' => 'required',
           'fecha_nacimiento' => 'required'
       ]);
       $msg = '<div class="alert alert-danger" role="alert" style="margin: 0">
                      ha ocurrido un inconveniente al actualizar los datos, intente nuevamente.!
                </div>';
       $success = false;

       if (!$validar->fails()) {

           $objPerson = Person::where('party_id', $request->party_id);
           $savePerson = $objPerson->update([
               'first_name' => $request->nombre,
               'last_name' => $request->apellido,
               'birth_date' => $request->fecha_nacimiento,
               'nacionalidad' => $request->nacionalidad
           ]);

           if ($savePerson) {
               $objPartyIdentification = PartyIdentification::where('party_id', $request->party_id)->first();

               if($objPartyIdentification == null){
                   $savePartyIdentification = new PartyIdentification;
                   $savePartyIdentification->party_id = $request->party_id;
               }else{
                   $savePartyIdentification = PartyIdentification::find($objPartyIdentification->party_id);
               }
               $savePartyIdentification->party_identification_type_id = $request->tipo_identificacion;
               $savePartyIdentification->id_value = $request->identificacion;


               if ($savePartyIdentification->save()) {

                   $user = UserLogin::where('party_id', $request->party_id)->first();

                   foreach ($user->party->party_contact_mech as $contact_mech) {
                       if ($contact_mech->contact_mech->contact_mech_type_id === "EMAIL_ADDRESS")
                           $idContactMechEmail = $contact_mech->contact_mech->contact_mech_id;
                       if ($contact_mech->contact_mech->contact_mech_type_id === "TELECOM_NUMBER")
                           $idContactMechNumber = $contact_mech->contact_mech->contact_mech_id;
                       if ($contact_mech->contact_mech->contact_mech_type_id === "POSTAL_ADDRESS")
                           $idContactMechPostaladdress = $contact_mech->contact_mech->contact_mech_id;
                   }

                   if(isset($idContactMechEmail)){

                       $objContactMetch = ContactMech::find($idContactMechEmail);
                       $objContactMetch->info_string = $request->correo;
                       $objContactMetch->save();

                   }else{

                       $objContactMetch = new ContactMech;
                       $objContactMetch->contact_mech_id = getSequenceValueItem('ContactMech');
                       $objContactMetch->contact_mech_type_id = 'EMAIL_ADDRESS';
                       $objContactMetch->info_string = $request->correo;

                       if($objContactMetch->save()){
                           setSequenceValueItem('ContactMech');
                           $objPartyContactMech = new PartyContactMech;
                           $objPartyContactMech->party_id = $request->party_id;
                           $objPartyContactMech->contact_mech_id = $objContactMetch->contact_mech_id;
                           //$objPartyContactMech->role_type_id = $request->rol;
                           $objPartyContactMech->from_date = now()->format('Y/m/d');
                           $objPartyContactMech->save();
                       }
                   }

                   if(isset($idContactMechNumber)){
                       $objTelecomNumber = TelecomNumber::find($idContactMechNumber);
                       $objTelecomNumber->contact_number = $request->telefono;
                       $objTelecomNumber->save();
                   }else{
                       $objContactMetch = new ContactMech;
                       $objContactMetch->contact_mech_id = getSequenceValueItem('ContactMech');
                       $objContactMetch->contact_mech_type_id = 'TELECOM_NUMBER';

                       if($objContactMetch->save()){
                           setSequenceValueItem('ContactMech');
                           $objTelecomNumber = new TelecomNumber;
                           $objTelecomNumber->contact_mech_id = $objContactMetch->contact_mech_id;
                           $objTelecomNumber->contact_number = $request->telefono;
                           $objTelecomNumber->country_code = "593";

                           if($objTelecomNumber->save()){
                               setSequenceValueItem('ContactMech');
                               $objPartyContactMech = new PartyContactMech;
                               $objPartyContactMech->party_id = $request->party_id;
                               $objPartyContactMech->contact_mech_id = $objContactMetch->contact_mech_id;
                               $objPartyContactMech->from_date = now()->format('Y/m/d');
                               $objPartyContactMech->save();
                           }

                       }
                   }

                   if(isset($idContactMechPostaladdress)){

                       $objPostalAddress = PostalAddress::find($idContactMechPostaladdress);
                       $objPostalAddress->address1 = $request->direccion;
                       $objPostalAddress->country_geo_id = $request->pais;
                       $objPostalAddress->save();

                   }else{
                       $objContactMetch = new ContactMech;
                       $objContactMetch->contact_mech_id = getSequenceValueItem('ContactMech');
                       $objContactMetch->contact_mech_type_id = 'POSTAL_ADDRESS';

                       if($objContactMetch->save()){
                           setSequenceValueItem('ContactMech');
                           $objPostalAddress = new PostalAddress;
                           $objPostalAddress->address1 = $request->direccion;
                           $objPostalAddress->country_geo_id = $request->pais;
                           $objPostalAddress->contact_mech_id = $objContactMetch->contact_mech_id;

                           if ($objPostalAddress->save()) {

                               $objPartyContactMech = new PartyContactMech;
                               $objPartyContactMech->party_id =  $request->party_id;
                               $objPartyContactMech->contact_mech_id = $objContactMetch->contact_mech_id;
                               $objPartyContactMech->from_date = now()->format('Y/m/d');
                               $objPartyContactMech->save();
                           }
                       }
                   }

                     $success = true;
                   $msg = '<div class="alert alert-success" role="alert" style="margin: 0">
                                Se ha actualizado los datos con éxito.!
                           </div>';
               }
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

   public function actualizarContrasenaDatosUsuario(Request $request){

       $validar = Validator::make($request->all(), [
           'contrasena'              => 'required|min:4|max:10|confirmed',
           'contrasena_confirmation' => 'required|min:4|max:10'
       ]);
       $success = false;
       $msg = '<div class="alert alert-danger" role="alert" style="margin: 0">
                    Ha ocurrido un inconveniente al actualizar la contraseña, intente nuevamente.!
               </div>';

       if(!$validar->fails()) {
           $objUserLogin = UserLogin::where('party_id',$request->party_id);

           $contrasena = "{SHA}" . sha1($request->contrasena);
           if($objUserLogin->update(['current_password'=> $contrasena])){
               $success = true;
               $msg = '<div class="alert alert-success" role="alert" style="margin: 0">
                    Se ha actualizado la contraseña, éxitosamente.!
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

   public function guardarDatosPaciente(Request $request){
       $validar = Validator::make($request->all(), [
           'nombres_paciente' => 'required|min:4|max:30',
           'apellidos_paciente' => 'required',
           'telefono_paciente' => 'required',
           'direccion_paciente' => 'required',
           'nacionalidad_paciente' => 'required',
           'tipo_identificacion_paciente' => 'required',
           'identificacion_paciente' => 'required',
           'telefono_paciente' => 'required',
           'direccion_paciente' => 'required',
           'pais_paciente' => 'required',
           'fecha_nacimiento_paciente' => 'required'
       ]);
       $msg = '<div class="alert alert-danger" role="alert" style="margin: 0">
                      ha ocurrido un inconveniente al actualizar los datos, intente nuevamente.!
                </div>';
       $success = false;


       if (!$validar->fails()) {

           $msg = '<div class="alert alert-danegr" role="alert" style="margin: 0">
                        ha ocurrido un inconveniente al guardar los datos del paciente, intente nuevamente.!
                    </div>';

           $objParty = new Party;
           $objParty->party_id = getSequenceValueItem('Party');;
           $objParty->party_type_id = "PERSON";
           $objParty->created_by_user_login = session('party_id');

           if($objParty->save()) {

               $objPartRole = new PartyRole;
               $objPartRole->party_id = $objParty->party_id;
               $objPartRole->role_type_id = "END_USER_CUSTOMER";

               if($objPartRole->save()){
                   $objUserLogin = new UserLogin;
                   $objUserLogin->user_login_id = $objParty->party_id;
                   $objUserLogin->enabled = "Y";
                   $objUserLogin->created_stamp = now()->toDateString();
                   $objUserLogin->party_id = $objParty->party_id;

                   if($objUserLogin->save()){

                       $objPartyRelationship = new PartyRelationship;
                       $objPartyRelationship->party_id_from = session('party_id');
                       $objPartyRelationship->party_id_to = $objParty->party_id;
                       $objPartyRelationship->role_type_id_from = getParty(session('party_id'))->party_role->role_type_id;
                       $objPartyRelationship->role_type_id_to = "END_USER_CUSTOMER";
                       $objPartyRelationship->from_date = now()->toDateString();

                       if($objPartyRelationship->save()){

                           $model = Party::all()->last();
                           crear_log("party", $model->party_id, session('party_id'), 'Creación de nuevo party_id');

                           $objPerson = new Person;
                           $objPerson->party_id = $objParty->party_id;
                           $objPerson->first_name = $request->nombres_paciente;
                           $objPerson->last_name = $request->apellidos_paciente;
                           $objPerson->birth_date = $request->fecha_nacimiento_paciente;
                           $objPerson->nacionalidad = $request->nacionalidad_paciente;

                           if ($objPerson->save()) {

                               $model = Person::all()->last();
                               crear_log("person", $model->party_id, session::get('party_id'), 'Creación de una nueva persona');

                               $objContactMetch = new ContactMech;
                               $objContactMetch->contact_mech_id = getSequenceValueItem('ContactMech');
                               $objContactMetch->contact_mech_type_id = 'TELECOM_NUMBER';

                               if ($objContactMetch->save()) {

                                   setSequenceValueItem('ContactMech');
                                   $model = ContactMech::all()->last();
                                   crear_log("contact_mech", $model->contact_mech_id, session::get('party_id'), 'Creación de una nuevo contact_mech para número de teléfono');
                                   $objTelecomNumber = new TelecomNumber;
                                   $objTelecomNumber->contact_mech_id = $objContactMetch->contact_mech_id;
                                   $objTelecomNumber->country_code = "593";
                                   $objTelecomNumber->contact_number = $request->telefono_paciente;

                                   if ($objTelecomNumber->save()) {

                                       $model = TelecomNumber::all()->last();
                                       crear_log("telecom_number", $model->contact_mech_id, session::get('party_id'), 'Creación de un nuevo número de teléfono');

                                       $objPartyContactMech = new PartyContactMech;
                                       $objPartyContactMech->party_id = $objParty->party_id;
                                       $objPartyContactMech->contact_mech_id = $objContactMetch->contact_mech_id;
                                       $objPartyContactMech->role_type_id = "END_USER_CUSTOMER";
                                       $objPartyContactMech->from_date = now()->format('Y/m/d');

                                       if ($objPartyContactMech->save()) {

                                           $model = PartyContactMech::all()->last();
                                           crear_log("party_contact_mech", $model->contact_mech_id, session::get('party_id'), 'Creación de una nueva relación entre teléfono y el party_id');

                                           $objPartyIdentification = new PartyIdentification;
                                           $objPartyIdentification->party_id = $objParty->party_id;
                                           $objPartyIdentification->party_identification_type_id = $request->tipo_identificacion_paciente;
                                           $objPartyIdentification->id_value = $request->identificacion_paciente;

                                           if ($objPartyIdentification->save()) {

                                               $objContactMetch = new ContactMech;
                                               $objContactMetch->contact_mech_id = getSequenceValueItem('ContactMech');
                                               $objContactMetch->contact_mech_type_id = 'POSTAL_ADDRESS';

                                               if ($objContactMetch->save()) {

                                                   setSequenceValueItem('ContactMech');
                                                   $model = ContactMech::all()->last();
                                                   crear_log("contact_mech", $model->contact_mech_id, $objParty->party_id, 'Creación de una nuevo contact_mech pára dirección');
                                                   $objPostalAddress = new PostalAddress;
                                                   $objPostalAddress->address1 = $request->direccion_paciente;
                                                   $objPostalAddress->country_geo_id = $request->pais_paciente;
                                                   $objPostalAddress->contact_mech_id = $objContactMetch->contact_mech_id;

                                                   if ($objPostalAddress->save()) {
                                                       $model = PartyIdentification::all()->last();
                                                       crear_log("postal_address", $model->party_id, session::get('party_id'), 'Creación de una nueva dirección del paciente');

                                                       $objPartyContactMech = new PartyContactMech;
                                                       $objPartyContactMech->party_id = $objParty->party_id;
                                                       $objPartyContactMech->contact_mech_id = $objContactMetch->contact_mech_id;
                                                       $objPartyContactMech->role_type_id = "END_USER_CUSTOMER";
                                                       $objPartyContactMech->from_date = now()->format('Y/m/d');

                                                       if ($objPartyContactMech->save()) {

                                                           if (setSequenceValueItem('Party')) {
                                                               $model = PartyContactMech::all()->last();
                                                               $success = true;
                                                               $msg = '<div class="alert alert-success" role="alert" style="margin: 0">
                                                                Se ha guardado os datos del paciente con éxito.!
                                                            </div>';
                                                               crear_log("party_contact_mech", $model->party_id, $objParty->party_id, 'Creación de una nueva relación entre dirección y el party_id');
                                                           }
                                                       }else{

                                                       }
                                                   }else{

                                                   }
                                               }else{

                                               }
                                           }else{

                                           }

                                       }else{

                                       }
                                   }else{

                                   }
                               }else{

                               }

                           }else{

                           }

                       }else{

                       }
                   }
               }else{

               }
           }else{

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

   public function actualizarDatosPaciente(Request $request){

       $validar = Validator::make($request->all(), [
           'nombres_paciente' => 'required|min:4|max:30',
           'apellidos_paciente' => 'required',
           'telefono_paciente' => 'required',
           'direccion_paciente' => 'required',
           'nacionalidad_paciente' => 'required',
           'tipo_identificacion_paciente' => 'required',
           'identificacion_paciente' => 'required',
           'telefono_paciente' => 'required',
           'direccion_paciente' => 'required',
           'pais_paciente' => 'required',
           'fecha_nacimiento_paciente' => 'required'
       ]);
       $msg = '<div class="alert alert-danger" role="alert" style="margin: 0">
                      ha ocurrido un inconveniente al actualizar los datos, intente nuevamente.!
                </div>';
       $success = false;


       if (!$validar->fails()) {

           $msg = '<div class="alert alert-danegr" role="alert" style="margin: 0">
                        ha ocurrido un inconveniente al guardar los datos del paciente, intente nuevamente.!
                    </div>';

           $objPerson = Person::find($request->party_id);
           $objPerson->first_name = $request->nombres_paciente;
           $objPerson->last_name = $request->apellidos_paciente;
           $objPerson->birth_date = $request->fecha_nacimiento_paciente;
           $objPerson->nacionalidad = $request->nacionalidad_paciente;

           if ($objPerson->save()) {

               $model = Person::all()->last();
               crear_log("person", $model->party_id, session::get('party_id'), 'Creación de una nueva persona');

               $objPartyIdentification = PartyIdentification::find($request->party_id);
               $objPartyIdentification->party_identification_type_id = $request->tipo_identificacion_paciente;
               $objPartyIdentification->id_value = $request->identificacion_paciente;

               if($objPartyIdentification->save()){

                   foreach(getParty($request->party_id)->party_contact_mech as $cm){

                       if($cm->contact_mech->contact_mech_type_id === "TELECOM_NUMBER"){
                           $idTelecomNumber = $cm->contact_mech->telecom_number->contact_mech_id;
                       }elseif($cm->contact_mech->contact_mech_type_id === "POSTAL_ADDRESS"){
                           $idPostalAddress = $cm->contact_mech->posta_address->contact_mech_id;
                       }
                   }

                   if(isset($idTelecomNumber)){
                       $objTelecomNumber = TelecomNumber::find($idTelecomNumber);
                       $objTelecomNumber->contact_number = $request->telefono_paciente;
                       $objTelecomNumber->save();
                   }

                   if(isset($idPostalAddress)){
                       $objPostalAddress = PostalAddress::find($idPostalAddress);
                       $objPostalAddress->address1 = $request->direccion_paciente;
                       $objPostalAddress->country_geo_id = $request->pais_paciente;
                       $objPostalAddress->save();
                   }

               }
               $success=true;
               $msg = '<div class="alert alert-success" role="alert" style="margin: 0">
                             Se ha guardado los datos del paciente con éxito.!
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

   public function editarlUsuario(){
       return view('usuario.partials.perfil');
   }

   public function usuarioNuevo(Request $request){
       return view('usuario.partials.add_usuario',[
           'url' => $request->path(),
           'titulo' => ['titulo'=>'Configuraciones','sub_titulo'=>'Usuarios'],
           'usuario' => getParty(session('party_id')),
           'roles' => RoleType::all(),
           'tipoIdentificacion' => PartyIdentificationType::all(),
           'pais'=> Geo::where('geo_type_id','COUNTRY')->get(),
       ]);
   }

   public function storeUsuario(Request $request){

       $validar = Validator::make($request->all(), [
           'nombres' => 'required|min:4|max:30',
           'apellidos' => 'required',
           'telefono' => 'required',
           'direccion' => 'required',
           'correo' => 'required|email|unique:user_login,email',
           'nacionalidad' => 'required',
           'tipo_identificacion' => 'required',
           'identificacion' => 'required',
           'telefono' => 'required',
           'direccion' => 'required',
           'fecha_nacimiento' => 'required',
           'contrasena' => 'required|min:4|max:10',
           'rol' => 'required'
       ],['correo.unique'=>'Ya existe un usuario registrado con el mismo correo, por favor ingrese otro']);

       $msg = '<div class="alert alert-danger" role="alert" style="margin: 0">
                      ha ocurrido un inconveniente al actualizar los datos, intente nuevamente.!
                </div>';
       $success = false;

       if (!$validar->fails()) {

           $objParty = new Party;
           $objParty->party_id = getSequenceValueItem('Party');
           $objParty->party_type_id = "PERSON";

           if($objParty->save()) {

               $objUserLogin = new UserLogin;
               $objUserLogin->user_login_id = $objParty->party_id;
               $objUserLogin->current_password = "{SHA}" . sha1($request->contrasena);
               $objUserLogin->enabled = "N";
               $objUserLogin->created_stamp = now()->toDateString();
               $objUserLogin->party_id = $objParty->party_id;
               $objUserLogin->email = $request->correo;

               if ($objUserLogin->save()) {

                   $objPartyRole = new PartyRole;
                   $objPartyRole->party_id = $objUserLogin->party_id;
                   $objPartyRole->role_type_id = $request->rol;

                   if ($objPartyRole->save()) {
                       $objPerson = new Person;
                       $objPerson->party_id = $objUserLogin->party_id;
                       $objPerson->first_name = $request->nombres;
                       $objPerson->last_name = $request->apellidos;
                       $objPerson->birth_date = $request->fecha_nacimiento;
                       $objPerson->nacionalidad = $request->nacionalidad;

                       if ($objPerson->save()) {

                           $model = Person::all()->last();
                           crear_log("person", $model->party_id, $objParty->party_id, 'Creación de una nuevo usuario');
                           $objPartyIdentification = new PartyIdentification;
                           $objPartyIdentification->party_id = $objUserLogin->party_id;
                           $objPartyIdentification->party_identification_type_id = $request->tipo_identificacion;
                           $objPartyIdentification->id_value = $request->identificacion;

                           if ($objPartyIdentification->save()) {

                               $objContactMetch = new ContactMech;
                               $objContactMetch->contact_mech_id = getSequenceValueItem('ContactMech');
                               $objContactMetch->contact_mech_type_id = 'EMAIL_ADDRESS';
                               $objContactMetch->info_string = $request->correo;

                               if ($objContactMetch->save()) {

                                   setSequenceValueItem('ContactMech');
                                   $objPartyContactMech = new PartyContactMech;
                                   $objPartyContactMech->party_id = $objUserLogin->party_id;
                                   $objPartyContactMech->contact_mech_id = $objContactMetch->contact_mech_id;
                                   $objPartyContactMech->role_type_id = $request->rol;
                                   $objPartyContactMech->from_date = now()->format('Y/m/d');

                                   if ($objPartyContactMech->save()) {

                                       $objContactMetch = new ContactMech;
                                       $objContactMetch->contact_mech_id = getSequenceValueItem('ContactMech');
                                       $objContactMetch->contact_mech_type_id = 'TELECOM_NUMBER';

                                       if ($objContactMetch->save()) {

                                           setSequenceValueItem('ContactMech');
                                           $objTelecomNumber = new TelecomNumber;
                                           $objTelecomNumber->contact_mech_id = $objContactMetch->contact_mech_id;
                                           $objTelecomNumber->country_code = "593";
                                           $objTelecomNumber->contact_number = $request->telefono;

                                           if ($objTelecomNumber->save()) {

                                               $objPartyContactMech = new PartyContactMech;
                                               $objPartyContactMech->party_id = $objUserLogin->party_id;
                                               $objPartyContactMech->contact_mech_id = $objContactMetch->contact_mech_id;
                                               $objPartyContactMech->role_type_id = $request->rol;
                                               $objPartyContactMech->from_date = now()->format('Y/m/d');

                                               if ($objPartyContactMech->save()) {

                                                   $objContactMetch = new ContactMech;
                                                   $objContactMetch->contact_mech_id = getSequenceValueItem('ContactMech');
                                                   $objContactMetch->contact_mech_type_id = 'POSTAL_ADDRESS';

                                                   if ($objContactMetch->save()) {

                                                       setSequenceValueItem('ContactMech');
                                                       $objPostalAddress = new PostalAddress;
                                                       $objPostalAddress->address1 = $request->direccion;
                                                       $objPostalAddress->country_geo_id = $request->pais;
                                                       $objPostalAddress->contact_mech_id = $objContactMetch->contact_mech_id;
                                                       $objPostalAddress->state_province_geo_id = $request->id_provincia;
                                                       $objPostalAddress->city = $request->ciudad;

                                                       if ($objPostalAddress->save()) {
                                                           $objPartyContactMech = new PartyContactMech;
                                                           $objPartyContactMech->party_id = $objUserLogin->party_id;
                                                           $objPartyContactMech->contact_mech_id = $objContactMetch->contact_mech_id;
                                                           $objPartyContactMech->role_type_id = $request->rol;
                                                           $objPartyContactMech->from_date = now()->format('Y/m/d');

                                                           if ($objPartyContactMech->save()) {

                                                               if (setSequenceValueItem('Party'))
                                                                   $msg = '<div class="alert alert-success" role="alert" style="margin: 0">
                                                                                  Se han guardado los datos del usuario con éxito.!
                                                                            </div>';
                                                                   $success = true;
                                                           } else {
                                                               setSequenceValueItem('ContactMech', 'disminuir');
                                                               PostalAddress::destroy($objContactMetch->contact_mech_id);
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
                                                           TelecomNumber::destroy($objContactMetch->contact_mech_id);
                                                           PartyContactMech::destroy($objUserLogin->party_id);
                                                           ContactMech::destroy($objContactMetch->contact_mech_id);
                                                           PartyIdentification::destroy($objUserLogin->party_id);
                                                           Person::destroy($objUserLogin->party_id);
                                                           PartyRole::destroy($objUserLogin->party_id);
                                                           Party::destroy($objUserLogin->party_id);
                                                           UserLogin::destroy($request->usuario);}
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
                           } else {
                               Person::destroy($objUserLogin->party_id);
                               PartyRole::destroy($objUserLogin->party_id);
                               Party::destroy($objUserLogin->party_id);
                               UserLogin::destroy($request->usuario);
                           }
                       } else {
                           PartyRole::destroy($objUserLogin->party_id);
                           Party::destroy($objUserLogin->party_id);
                           UserLogin::destroy($request->usuario);
                       }
                   } else {
                       Party::destroy($objUserLogin->party_id);
                       UserLogin::destroy($request->usuario);
                   }
               }else {
                   Party::destroy($objParty->party_id);
               }
           }else {
               Party::destroy($objParty->party_id);
           }
       /*}else {
           flash('Hubo un error al tratar de guardar el usuario intente nuevamente')->error()->important();
           return redirect('registro');*/

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

   public function storeFirmaDigital(Request $request){

       $msg = '<div class="alert alert-danger" role="alert" style="margin: 0">
                      ha ocurrido un inconveniente al guardar la información en el sistema, intente nuevamente.!
                    </div>';
       $success = false;

       $validar = Validator::make($request->all(), [
           'firma_digital' => 'required|mimes:PNG,png',
           'firma_electronica' => 'required',
           'contrasena_firma_electronica' => 'required'
       ],[
           'firma_digital.mimes' => 'La imagen de la firma debe ser en formato .PNG',
           'firma_digital.required' => 'Debe cargar una imagen con la firma digital',
           'firma_electronica.required' => 'Debe cargar un archivo para la firma electrónica',
           'contrasena_firma_electronica.required' => ' Debe escribir las contraseña del archivo de la firma electrónica'
       ]);

       if (!$validar->fails()) {

           $imagen = $request->file('firma_digital');
           $archivo = $request->file('firma_electronica');

           $nombreImagen = rand(1,2000)."_".$imagen->getClientOriginalName();
           $nombreArchivo = rand(1,2000)."_".$archivo->getClientOriginalName();

           $registroFirma = FirmaDigital::where('party_id',$request->party_id)->first();

           $objFirmaDigital = new FirmaDigital;
           $objFirmaDigital->imagen = $nombreImagen;
           $objFirmaDigital->archivo = $nombreArchivo;
           $objFirmaDigital->party_id = $request->party_id;
           $objFirmaDigital->contrasena = $request->contrasena_firma_electronica;

           if($objFirmaDigital->save()){

               \Storage::disk('firmas_digital')->put($nombreImagen, \File::get($imagen));
               \Storage::disk('firmas_digital')->put($nombreArchivo, \File::get($archivo));

               $msg = '<div class="alert alert-success" role="alert" style="margin: 0">
                          Se ha guardado la información en el sistema con éxito.!
                       </div>';
               $success = true;

               if(isset($registroFirma)){
                   \Storage::disk('firmas_digital')->delete($registroFirma->imagen);
                   \Storage::disk('firmas_digital')->delete($registroFirma->archivo);
                    FirmaDigital::Destroy($registroFirma->id_firma_digital);
               }
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

       return response()->json([
           "success" =>$success,
           "msg" => $msg
       ]);

   }
}

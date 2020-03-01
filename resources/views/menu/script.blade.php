<script>
    function addMenu(id_menu) {
        load("show");
        id_menu !==undefined ? accion = "Editar": accion = "Crear nuevo";
        data = {
            id_menu : id_menu
        },
        $.get('{{url('menu/add_menu')}}', data, function (retorno) {
            modal('add_menu', retorno, '<i class="fa fa-plus" aria-hidden="true"></i> '+accion+' menú',true,'40%',true,function () {
                storeMenu(id_menu);
            });

        }).always(function () {
            load("hide");
        });
    }

    function storeMenu(id_menu) {

       // $("#div_path label").remove();
        //if(checkPattern()){
            if($("#"+$("form.form").attr("id")).valid()){
                data = {
                    nombre : $("#nombre").val(),
                    icono :$("input:radio[name='icono']:checked").val(),
                    path : $("#path").val(),
                    id_menu : id_menu
                };
                peticion_ajax(data,'{{'menu/sotre_menu'}}','POST');
            }
        //}else{
        //    $("#div_path").append("<label id='path-error' class='error' for='path'>Debe escribir un path que coincida Ej: menu_menu (minusculas).</label>");
        //}

    }

    function deleteMenu(id_menu) {
        confirmar = confirm("Esta seguro de borrar este menú?, al borrarlo se borraran tambien los sub menus atadado a él");
        if(confirmar){
            data = {  id_menu : id_menu  };
            peticion_ajax(data,'{{'menu/delete_menu'}}','POST');
        }
    }

    function addSubMenu(id_sub_menu) {
        load("show");
        id_sub_menu !==undefined ? accion = "Editar": accion = "Crear nuevo";
        data = {
            id_sub_menu : id_sub_menu
        };
        $.get('{{url('menu/add_sub_menu')}}',data, function (retorno) {
            modal('add_sub_menu', retorno, '<i class="fa fa-plus" aria-hidden="true"></i> '+accion+' sub menú',true,'40%',true,function () {
                storeSubMenu(id_sub_menu);
            });

        }).always(function () {
            load("hide");
        });
    }

    function storeSubMenu(id_sub_menu) {
        if($("#"+$("form.form").attr("id")).valid()){
            data = {
                nombre : $("#nombre").val(),
                icono :$("input:radio[name='icono']:checked").val(),
                menu : $("#menu").val(),
                path : $("#path").val(),
                id_sub_menu : id_sub_menu
            };
            peticion_ajax(data,'{{'menu/sotre_sub_menu'}}','POST');
        }
    }

    function deletesubMenu(id_sub_menu) {
        confirmar = confirm("Esta seguro de borrar este sub menú?");
        if(confirmar){
            data = {  id_sub_menu : id_sub_menu  };
            peticion_ajax(data,'{{'menu/delete_sub_menu'}}','POST');
        }
    }
    
    function asignarPermisos(id_rol) {
        load("show");
        data = {
            id_rol : id_rol
        },
        $.get('{{url('menu/asignar_permisos')}}', data, function (retorno) {
            modal('add_permisos', retorno, '<i class="fa fa-plus" aria-hidden="true"></i> Permisos',true,'60%',false,function () {
                sotrePermisos(id_rol);
            });
        }).always(function () {
            load("hide");
        });
    }

    function sotrePermisos(id_rol) {
        load("show");
        if($("#"+$("form.form").attr("id")).valid()){
            arrMenu = [];
                $.each($("input:checkbox[name='menu']:checked"),function (i,j) {
                arrMenu.push({ id_menu : $("#"+j.id).val()});
            });
            data = {
                arrMenu : arrMenu,
                id_rol : id_rol
            };
            peticion_ajax(data,'{{'menu/store_permisos'}}','POST');
        }
    }

    /*function checkPattern() {
        console.log($("#path").val());
        var elem = $("#path").val();
        var re = /^[a-z].{1,}_?[a-z].{1,}$/;
        return re.test(elem);
    }*/

</script>
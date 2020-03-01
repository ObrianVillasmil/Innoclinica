<script>

    function storeTratamiento(id_tratamiento){
        if($("#form_tratamiento").valid()){
            load("show");
            var formData = new FormData($("#form_tratamiento")[0]);
            formData.append('tratamiento',id_tratamiento);
            formData.append('icono',$("#icono_proceso").val());
            $.ajax({
                method : 'POST',
                url    : '{{url('/tratamiento/sotre_tratamiento')}}',
                data   : formData,
                processData: false,
                contentType: false,
                success:function (response) {
                    modal('store_tratamiento',response.msg,'Acción',false,'50%',true);
                    load("hide");
                }
            }).always(function () {
                load("hide");
            });
        }
    }

    function reiniciarProcesosTratamiento(){ $( 'div.linea_tratamiento' ).empty() }

    $('.item').draggable({
        helper: 'clone',
        zIndex: 1000
    });

    $('.linea_tratamiento').droppable({
        accept: '.item',
        hoverClass: 'hovering',
        drop: function(ev,ui) {
            var element = $(ui.helper).clone();
            $(this).append( element );
            $(".linea_tratamiento div").css({
                'width':'47%',
                'padding':'10px',
                'left':'auto',
                'position':'initial',
                'background':'#f5f4f4',
                'margin':'10px 5px'
            });
            total_div = $("div.div_procesos").length;
            num = parseInt(total_div)+1;
            element.addClass('div_procesos');
            element.addClass('item_'+num);
            element.removeClass('item');
            element.attr('id','item_'+num);
            element.prepend("<span class='span_text'>"+num+"</span>" +") ");
            element.append("<a href=# style='border:1px  solid #33b35a ;border-radius: 30px;padding: 0px 7px;' onclick=eliminar_proceso('"+num+"')>x</a>");
        }
    });
    
    function storeProcesosTratamiento() {

        arr_procesos = [];
        $.each($(".linea_tratamiento div.div_procesos"),function (i,j) {
            arr_procesos.push({
                id_submenu :  $("div."+j.id+" input#id_sub_menu").val(),
                id_proceso :  $("div."+j.id+" input#id_proceso").val()
            });

        });

        if(arr_procesos.length === 0){
            var error = "<div class='alert alert-danger'> Debe arrastrar al menos un proceso al recuadro de administración de procesos</div>";
            modal('error_linea_tratamiento',error,'<span class="text-danger"><i class="fa fa-exclamation-triangle" ></i> Alerta</span>',false,'50%',false);
            return false;
        }

        data = {
            id_tratamiento : $("#id_tratamiento").val(),
            arr_procesos : arr_procesos
        };

        peticion_ajax(data,'{{'/tratamiento/sotre_proceso_tratamiento'}}','POST');

    }
    
    function update_estado_trtamiento(id_tratamiento,estado) {
        confirmar = confirm("Al "+ (estado == 1 ? "desactivar" : "activar" )+" este tratamiento "+ (estado == 1 ? 'no' : '' )+" será visible para los clientes en la vista principal de sus cuentas");
        if(confirmar){
            data = {
                id_tratamiento : id_tratamiento,
                estado : estado
            };
            peticion_ajax(data,'{{'/tratamiento/updates_estado_tratamiento'}}','POST');
        }
    }

    function delete_trtamiento(id_tratamiento) {
        confirmar = confirm("Desea eliminar este tratamiento?");
        if(confirmar){
            data = {
                id_tratamiento : id_tratamiento,
            };
            peticion_ajax(data,'{{'/tratamiento/delete_tratamiento'}}','POST');
        }
    }

    function eliminar_proceso(id) {
        $("div.item_"+id).remove();
        $.each($("span.span_text"),function (i,j) { $(j).html(i+1); });
    }

    $(document).ready(function(){
        $( ".form-row" ).sortable({
            update : function () {
                $.each($("span.span_text"),function (i,j) { $(j).html(i+1); });
            }
        });

    });

</script>
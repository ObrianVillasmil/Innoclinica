 <div class="form-group col-md-6 div_texto div_texto_{{$cant}}" id="div_texto_{{$cant}}" >
     <label>Campo de texto</label>
     <div class="input-group">
         <div class="input-group-append">
             <button type="button" class="btn btn-outline-light">
                 <input id="text_requerido_{{$cant}}" type="checkbox" value=""readonly class="form-control-custom">
                 <label for="text_requerido_{{$cant}}" title="Hacer que el campo sea obligatorio" style="bottom: 12px;"></label>
             </button>
         </div>
         <input type="text" id="campo_texto_{{$cant}}" name="campo_texto_{{$cant}}" placeholder="Label" class="form-control" required>
         <div class="input-group-append">
             <button type="button" class="btn btn-danger" title="Eliminar campo" onclick="deleteCampo('div_texto_{{$cant}}')">
                 <i class="fa fa-trash"></i>
             </button>
         </div>
     </div>
 </div>

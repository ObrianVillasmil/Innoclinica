<form class="form form-horizontal">
    <div class="form-group row">
        <div class="col-sm-12">
            <div class="form-group">
                <div class="form-group">
                    <select id="rol" name="rol" required class="form-control">
                        @foreach($roles as $r)
                            <option value="{{$r->role_type_id}}">{{$r->description}}</option>
                        @endforeach
                    </select>

                </div>
                <div class="form-group text-right">
                    <button type="button" class="btn btn-primary btn_store">
                        <i class="fa fa-floppy-o" aria-hidden="true"></i>
                        Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
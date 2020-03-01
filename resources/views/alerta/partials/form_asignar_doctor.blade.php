<form class="form form-horizontal">
    <div class="form-group row">
        <div class="col-sm-12">
            <div class="form-group">
                <div class="form-group">
                    <label for="party_id">Seleccione al doctor</label>
                    <select id="party_id" name="party_id" required class="form-control">
                        <option value="" disabled selected>Seleccione</option>
                        @foreach($doctores as $doctor)
                            <option value="{{$doctor['party_id']}}">{{$doctor['nombre']}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group text-right">
                    <button type="button" class="btn btn-primary" onclick="storedoctorAsignado('{{$id_tratamiento_solicitado}}')">
                        <i class="fa fa-floppy-o" aria-hidden="true"></i>
                        Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
<form class="form form-horizontal">
	<input type="hidden" id="id_sub_menu" value="{{isset($menu->id_sub_menu) ? $menu->id_sub_menu : ""}}">
	<div class="form-group row">
		<div class="col-sm-12">
			<div class="form-group">
				<div class="form-group">
					<label for="icono">Selecciona un ícono</label><br /><br />
					<button type="button" class="btn btn-default">
						<input type="radio" value="fa-cogs" id="icono" name="icono" {{(isset($subMenu->icono) && $subMenu->icono == "fa-cogs" ) ? "checked" : ""}}> <i class="fa fa-cogs"></i>
					</button>
					<button type="button" class="btn btn-default">
						<input type="radio" value="fa-cog" id="icono" name="icono" {{(isset($subMenu->icono) && $subMenu->icono == "fa-cog" ) ? "checked" : ""}}> <i class="fa fa-cog"></i>
					</button>
					<button type="button" class="btn btn-default">
						<input type="radio" value="fa-circle-o" id="icono" name="icono" {{(isset($subMenu->icono) && $subMenu->icono == "fa-circle-o" ) ? "checked" : ""}}> <i class="fa fa-circle-o"></i>
					</button>
					<button type="button" class="btn btn-default">
						<input type="radio" value="fa-cubes" id="icono" name="icono" {{(isset($subMenu->icono) && $subMenu->icono == "fa-cubes" ) ? "checked" : ""}}> <i class="fa fa-cubes"></i>
					</button>
					<button type="button" class="btn btn-default">
						<input type="radio" value="fa-id-card-o" id="icono" name="icono" {{(isset($subMenu->icono) && $subMenu->icono == "fa-id-card-o" ) ? "checked" : ""}}> <i class="fa fa-id-card-o"></i>
					</button>
					<button type="button" class="btn btn-default">
						<input type="radio" value="fa-building" id="icono" name="icono" {{(isset($subMenu->icono) && $subMenu->icono == "fa-building" ) ? "checked" : ""}}> <i class="fa fa-building"></i>
					</button>
					<button type="button" class="btn btn-default">
						<input type="radio" value="fa-user-o" id="icono" name="icono" {{(isset($subMenu->icono) && $subMenu->icono == "fa-user-o" ) ? "checked" : ""}}> <i class="fa fa-user-o"></i>
					</button>
					<button type="button" class="btn btn-default">
						<input type="radio" value="fa-bell" id="icono" name="icono" {{(isset($subMenu->icono) && $subMenu->icono == "fa-bell" ) ? "checked" : ""}}> <i class="fa fa-bell"></i>
					</button>
				</div>
				<div class="form-group">
					<div class="input-group">
						<div class="input-group-prepend"><span class="input-group-text">Menú</span></div>
					<select class="form-control" id="menu" name="menu" required>
						<option selected disabled value="">Seleccione un menú</option>
						@foreach($menu as $m)
							<option {{isset($subMenu->id_menu) ? ($m->id_menu == $subMenu->id_menu ? "selected" : "") : "" }} value="{{$m->id_menu}}">{{$m->nombre}}</option>
						@endforeach
					</select>
					</div>
				</div>
				<div class="form-group">
					<div class="input-group">
						<div class="input-group-prepend"><span class="input-group-text">Sub menú</span></div>
					<input type="text" class="form-control " minlength="2" maxlength="100" id="nombre" name="nombre"
						   placeholder="Ingrese el nombre del sub menú" value="{{isset($subMenu->nombre) ? $subMenu->nombre : ""}}" required>
					</div>
				</div>
				<div class="form-group">
					<div class="input-group">
						<div class="input-group-prepend"><span class="input-group-text">Path</span></div>
					<input type="text" class="form-control" minlength="2" maxlength="100" id="path"
						   placeholder="Ingrese el path, Ej: submenu_submenu" placeholder="Ingrese el path" value="{{isset($subMenu->path) ? $subMenu->path : ""}}" required>
					</div>
				</div>
				<div class="form-group text-right	">
					<button type="button" class="btn btn-default" data-dismiss="modal">
						<i class="fa fa-ban" aria-hidden="true"></i>
						Cerrar
					</button>
					<button type="button" class="btn btn-primary btn_store">
						<i class="fa fa-floppy-o" aria-hidden="true"></i>
						Guardar
					</button>
				</div>
			</div>
		</div>
	</div>
</form>
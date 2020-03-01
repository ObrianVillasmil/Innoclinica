<form class="form form-horizontal">
	<input type="hidden" id="id_menu" value="{{isset($menu->id_menu) ? $menu->id_menu : ""}}">
	<div class="form-group row">
		<div class="col-sm-12">
			<div class="form-group">
				<div class="form-group">
					<label for="icono">Selecciona un ícono</label><br />
					<button type="button" class="btn btn-default">
						<input type="radio" value="fa-cogs" id="icono" name="icono" {{(isset($menu->icono) && $menu->icono == "fa-cogs" ) ? "checked" : ""}}> <i class="fa fa-cogs"></i>
					</button>
					<button type="button" class="btn btn-default">
						<input type="radio" value="fa-cog" id="icono" name="icono" {{(isset($menu->icono) && $menu->icono == "fa-cog" ) ? "checked" : ""}}> <i class="fa fa-cog"></i>
					</button>
					<button type="button" class="btn btn-default">
						<input type="radio" value="fa-circle-o" id="icono" name="icono" {{(isset($menu->icono) && $menu->icono == "fa-circle-o" ) ? "checked" : ""}}> <i class="fa fa-circle-o"></i>
					</button>
					<button type="button" class="btn btn-default">
						<input type="radio" value="fa-cubes" id="icono" name="icono" {{(isset($menu->icono) && $menu->icono == "fa-cubes" ) ? "checked" : ""}}> <i class="fa fa-cubes"></i>
					</button>
					<button type="button" class="btn btn-default">
						<input type="radio" value="fa-id-card-o" id="icono" name="icono" {{(isset($menu->icono) && $menu->icono == "fa-id-card-o" ) ? "checked" : ""}}> <i class="fa fa-id-card-o"></i>
					</button>
					<button type="button" class="btn btn-default">
						<input type="radio" value="fa-building" id="icono" name="icono" {{(isset($menu->icono) && $menu->icono == "fa-building" ) ? "checked" : ""}}> <i class="fa fa-building"></i>
					</button>
					<button type="button" class="btn btn-default">
						<input type="radio" value="fa-user-o" id="icono" name="icono" {{(isset($menu->icono) && $menu->icono == "fa-user-o" ) ? "checked" : ""}}> <i class="fa fa-user-o"></i>
					</button>
					<button type="button" class="btn btn-default">
						<input type="radio" value="fa-bell" id="icono" name="icono" {{(isset($subMenu->icono) && $subMenu->icono == "fa-bell" ) ? "checked" : ""}}> <i class="fa fa-bell"></i>
					</button>
				</div>
				<div class="form-group" style="margin-bottom: 30px">
					<div class="input-group">
						<div class="input-group-prepend"><span class="input-group-text">Nombre menú</span></div>
							<input type="text" class="form-control" minlength="2" maxlength="100" id="nombre"
								   placeholder="Ingrese el nombre del menú" value="{{isset($menu->nombre) ? $menu->nombre : ""}}" required>
					</div>
				</div>
				<div class="form-group" id="div_path" style="margin-bottom: 20px">
					<div class="input-group">
						<div class="input-group-prepend"><span class="input-group-text">Path</span></div>
							<input type="text" class="form-control" minlength="2" maxlength="100" id="path"
								   placeholder="Ingrese el path, Ej: menu_menu" value="{{isset($menu->path) ? $menu->path : ""}}" pattern="/^[a-z]_[a-z]+$/" required>
					</div>
				</div>
				<div class="col-sm-12 form-group text-right">
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
<style>
	#nombre-error, #path-error{
		position: absolute;
		top: 40px
	}
</style>

<form class="form form-horizontal">
    <div class="col-md-12">
        <div class="row">
            @foreach($menus as $key => $m)
                <div class="col-md-4 card income">
                    <ul id="side-main-menu" class="side-menu list-unstyled" style="margin: 0">
                        <li>
                            <div class="row">
                            <div class="col-md-10">
                                <a href="#menu_dopdown_{{$key+1}}" aria-expanded="false" data-toggle="collapse" style="padding: 15px;width: 100%;border-bottom: 1px solid #c0c0c038">
                                    <i class="fa fa-cogs" aria-hidden="true"></i> {{$m->nombre}} <i class="fa fa-sort-desc" aria-hidden="true"></i>
                                </a>
                            </div>
                            <div class="col-md-2">
                                @php
                                    $selected="";
                                    foreach ($permisos as $p) {
                                        if($p->id_menu === $m->id_menu)
                                        $selected="checked";
                                    }
                                @endphp
                                <input {{$selected}} type="checkbox" name="menu" id="checkbox_{{$key+1}}" value="{{$m->id_menu}}" class="form-control-custom">
                                <label   for="checkbox_{{$key+1}}" style="position: relative;bottom: 21px;right: 18px;"></label>
                            </div>
                            </div>
                            <ul id="menu_dopdown_{{$key+1}}" class="collapse list-unstyled" style="padding-left: 15px;">
                               @foreach(getMenu($m->id_menu)->subMenu as $sb)
                                <li>
                                    <a href="#">
                                        <i class="fa fa-circle-thin" aria-hidden="true"></i>
                                        {{$sb->nombre}}
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </li>
                    </ul>
                </div>
            @endforeach
        </div>
    </div>
    <div class="row" style="border-top: solid 1px #e9ecef;">
        <div class="col-md-12">
            <div class="form-group text-right" style="margin-top: 10px">
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
</form>
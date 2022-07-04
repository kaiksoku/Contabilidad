<input type="hidden" id="empPath" value="{{url('parametros/terminal')}}">
<input type="hidden" id="empSalPath" value="{{url('planillas/empleados/salarios/puesto-empresa')}}">
<input type="hidden" id="puesCod" value="{{old('sal_puesto',$salario->sal_puesto??'')}}">
<input type="hidden" id="empCod" value="{{old('sal_empresa',$salario->sal_empresa??'')}}">
<input type="hidden" id="terCod" value="{{old('sal_terminal',$salario->sal_terminal??'')}}">
<div class="form-group row">
    <input type="hidden" id="sal_empleado" name="sal_empleado" value="{{$id}}">

    <div class="col-lg-6 col-sm-12 mb-3">
        <div class="row">
            <label for="prod_empresa"
                   class="col-md-12 col-sm-12 col-lg-4 text-sm-left text-lg-right requerido">Empresa</label>
            <div class="col-sm-12 col-lg-7">
                <select name="sal_empresa" id="prod_empresa" class="form-control select2" placeholder="Empresa"
                        required>
                    <option value=""></option>
                    @foreach (auth()->user()->Empresas as $item)
                        <option value="{{$item->emp_id}}"
                            {{old('sal_empresa',$salario->sal_empresa ?? '') == $item->emp_id ? 'selected':''}}>
                            {{$item->emp_siglas}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-sm-12 mb-3">
        <div class="row">
            <label for="sal_terminal"
                   class="col-md-12 col-sm-12 col-lg-3 text-sm-left text-lg-right requerido">Terminal</label>
            <div class="col-sm-12 col-lg-7">
                <select name="sal_terminal" id="prod_terminal" class="form-control select2" placeholder="Terminal" required>
                </select>
            </div>
        </div>
    </div>
</div>

<fieldset class="border p-2 col-sm-12 col-lg-12">
    <legend class="w-auto">Informacion Laboral</legend>
    <div class="form-group row">

        <div class="col-lg-6 col-sm-12 mb-3">
            <div class="row">
                <label for="sal_inicio" class="col-md-12 col-sm-12 col-lg-4 text-sm-left text-lg-right requerido">Fecha
                    Inicio</label>
                <div class="input-group col-lg-7 col-md-12">
                    <div class="input-group-prepend">
                    <span class="input-group-text">
                        <i class="far fa-calendar-alt"></i>
                    </span>
                    </div>
                    <input type="text" class="form-control" name="sal_inicio"
                           {!!$salario->sal_inicio ?? null?'readonly':'id="sal_inicio"'!!}
                           value="{{old('sal_inicio', Carbon\Carbon::parse($salario->sal_inicio??'')->format('d/m/Y'))}}"
                           required>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-sm-12 mb-3">
            <div class="row">
                <label for="sal_fin" class="col-md-12 col-sm-12 col-lg-3 text-sm-left text-lg-right">Fecha
                    Fin</label>
                <div class="input-group col-lg-7 col-md-12">
                    <div class="input-group-prepend">
                    <span class="input-group-text">
                        <i class="far fa-calendar-alt"></i>
                    </span>
                    </div>
                    <input type="text" class="form-control" name="sal_fin"
                           {!!$salario->sal_fin ?? null?'readonly':'id="sal_fin"'!!}
                           value="{{old('empl_retiro', Carbon\Carbon::parse($salario->sal_fin??'')->format('d/m/Y'))}}">
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-sm-12 mb-3">
            <div class="row">
                <label for="sal_puesto"
                       class="col-md-12 col-sm-12 col-lg-4 text-sm-left text-lg-right">Puesto</label>
                <div class="col-md-12 col-lg-7">
                    <select name="sal_puesto" id="sal_puesto" class="form-control select2"  required>
                    </select>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-sm-12 mb-3">
            <div class="row">
                <label for="sal_tipo" class="col-md-12 col-sm-12 col-lg-3 text-sm-left text-lg-right requerido">Tipo
                    Salario</label>
                <div class="col-md-12 col-lg-7">
                    <select name="sal_tipo" id="sal_tipo" class="form-control select2 w-100" required>
                        <option value="M" {{old('sal_tipo',$salario->sal_tipo ?? null)=="M" ? 'selected':''}}>Mensual</option>
                        <option value="T" {{old('sal_tipo',$salario->sal_tipo ?? null)=="T" ? 'selected':''}}>Turnos</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-sm-12 mb-3">
            <div class="row">
                <label for="sal_salario"
                       class="col-md-12 col-sm-12 col-lg-4 text-sm-left text-lg-right">Salario</label>
                <div class="col-md-12 col-lg-7">
                    <input type="number" class="form-control w-100" id="sal_salario" name="sal_salario"
                           onkeypress='return validaNumericos(event,"D",this.value);'
                           value="{{old('sal_salario', $salario->sal_salario ?? 0)}}" Step=".01" required>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-sm-12 mb-3">
            <div class="row">
                <label for="sal_igss" class="col-sm-12 col-lg-5 control-label text-sm-left text-lg-right">Aplica IGSS</label>
                <div class="col-md-12 col-lg-6">
                    <input type="checkbox" name="sal_igss" data-bootstrap-switch
                           data-off-color="danger"
                           data-on-color="success" data-on-text="SI" data-off-text="NO" value="1"  {{ ($salario->sal_igss ??  1)==0?'':'checked'}}>
                </div>
            </div>
        </div>
    </div>

</fieldset>

<input type="hidden" id="empPath" value="{{url('parametros/terminal')}}">
<input type="hidden" id="empCod" value="{{old('empresa')}}">
<input type="hidden" id="terCod" value="{{old('terminal')}}">
<input type="hidden" id="empleadoPath" value="{{url('planillas/empleados/get')}}">

<fieldset class="border p-2 col-sm-12 col-lg-12">
    <legend class="w-auto">Informacion de empleados</legend>
    <div class="form-group row">
        <div class="col-lg-6 col-sm-12 mb-3">
            <div class="row">
                <label for="dett_empleado" class="col-md-12 col-sm-12 col-lg-4 text-sm-left text-lg-right requerido">Empleado </label>
                <div class="col-sm-12 col-lg-7">
                    @if($detalle->dett_salario)
                        <input type="text" class="form-control" name="dett_salario" id="dett_salario" value="{{old('dett_salario',$detalle->dett_salario)}}" hidden>
                        <input type="text" class="form-control" value="{{strtoupper($empleado->getNombreCompleto( $empleado->getIdBySal($detalle->dett_salario)))}}" disabled>
                    @else
                        <select id="dett_salario" name="dett_salario" class="select2 form-control"
                                required>
                            @foreach(session('dataEmpleados')??[] as $emp)
                                <option
                                    value="{{$emp->sal_id}}">{{strtoupper($empleado->getNombreCompleto($emp->sal_empleado))}}</option>
                            @endforeach

                        </select>
                    @endif

                </div>
            </div>
        </div>
        <div class="col-lg-6 col-sm-12 mb-3">
            <div class="row">
                <label for="dett_turnos" class="col-md-12 col-sm-12 col-lg-3 text-sm-left text-lg-right">Turnos</label>
                <div class="input-group col-md-12 col-lg-7">
                    <input type="text" class="form-control" name="dett_turnos" id="dett_turnos"
                           value="{{old('dett_turnos',$detalle->dett_turnos)}}" required
                           onkeypress='return validaNumericos(event,"N",this.value);'>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-sm-12 mb-3">
            <div class="row">
                <label for="dett_extras" class="col-md-12 col-sm-12 col-lg-4 text-sm-left text-lg-right">Horas
                    Extras</label>
                <div class="input-group col-md-12 col-lg-7">
                    <input type="text" class="form-control" name="dett_extras" id="dett_extras"
                           value="{{old('dett_extras',$detalle->dett_extras)}}"
                           onkeypress='return validaNumericos(event,"N",this.value);'>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-sm-12 mb-3">
            <div class="row">
                <label for="dett_ordinales" class="col-md-12 col-sm-12 col-lg-3 text-sm-left text-lg-right">Horas
                    Ordinales</label>
                <div class="input-group col-md-12 col-lg-7">
                    <input type="text" class="form-control" name="dett_ordinales" id="dett_ordinales"
                           value="{{old('dett_ordinales',$detalle->dett_ordinales)}}"
                           onkeypress='return validaNumericos(event,"N",this.value);'>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-sm-12 mb-3">
            <div class="row">
                <label for="dett_descripcion"
                       class="col-md-2 col-sm-12 col-lg-2 text-sm-left text-lg-right">Descripcion</label>
                <div class="input-group col-md-12 col-lg-9">
                    <input type="text" class="form-control" name="dett_descripcion" id="dett_descripcion"
                           value="{{old('dett_descripcion',$detalle->dett_descripcion)}}" required>
                </div>
            </div>
        </div>
    </div>
</fieldset>

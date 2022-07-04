<input type="hidden" id="moviPath" value="{{url('contabilidad/cuentabancaria')}}">
<input type="hidden" id="moviCod" value="{{old('empresa',$data->CuentaContable->Empresa->emp_id??'')}}">
<input type="hidden" id="ctaNivel4" value="{{url('contabilidad/ctaExcenta')}}">

<div class="form-group row">
    <label for="empresa"
        class="col-sm-12 col-lg-3 control-label text-sm-left text-lg-right requerido">Empresa</label>
        <div class="col-sm-12 col-lg-7">
            <input type= "text" name="empresa" id="empresa" class="form-control" placeholder="Empresa" required>
            <datalist id="lst_empresa">
                @foreach (auth()->user()->Empresas as $item)
                <option value= "{{$item->emp_NIT}}" data-id="{{$item->emp_id}}" data-nombre="{{$item->emp_siglas}}"></option>
                @endforeach
            </datalist>
            <input type="hidden" name="com_empresa" id="com_empresa" value="">
        </div>
        <div class="col-sm-12 col-lg-2">
            <label id="nom_empresa" class="col-form-label-lg"></label>
        </div>
</div>

<div class="form-group row">
    <label for="movb_descripcion" class="col-sm-12 col-lg-3 control-label text-sm-left text-lg-right requerido">Nombre</label>
    <div class="col-lg-8">
        <input type="text" name="movb_descripcion" class="form-control" id="movb_descripcion" placeholder="Nombre"
            value="{{old('movb_descripcion', $data->movb_descripcion ?? '')}}" onkeyup="javascript:this.value=this.value.toUpperCase();" required>
    </div>
</div>

<div class="form-group row">
    <label for="movb_cuentacontable"
        class="col-sm-12 col-lg-3 control-label text-sm-left text-lg-right requerido">Cuenta Contable</label>
    <div class="col-sm-12 col-lg-8">
        <select name="movb_cuentacontable" id="movb_cuentacontable" class="form-control select2" placeholder="Cuenta Contable" required>
        </select>
    </div>
</div>


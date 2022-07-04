<div class="form-group row">

    <label for="empresa"
        class="col-sm-12 col-lg-3 control-label text-sm-left text-lg-right requerido">Empresa</label>
    <div class="col-sm-12 col-lg-2">
        <input type= "text" name="empresa" id="empresa" class="form-control" placeholder="Empresa" required>
        <datalist id="lst_empresa">
            @foreach (auth()->user()->Empresas as $item)
            <option value= "{{$item->emp_NIT}}" data-id="{{$item->emp_id}}" data-nombre="{{$item->emp_siglas}}"></option>
            @endforeach
        </datalist>
        <input type="hidden" name="cla_empresa" id="cla_empresa" value="">
    </div>
    <div class="col-sm-12 col-lg-2">
        <label id="nom_empresa" class="col-form-label-lg"></label>
    </div>

</div>

<div class="form-group row">
    <label for="cla_UsuarioFirma" class="col-sm-12 col-lg-3 control-label text-sm-left text-lg-right requerido">Usuario Firma</label>
    <div class="col-lg-8">
        <input type="text" name="cla_UsuarioFirma" class="form-control" id="cla_UsuarioFirma" placeholder="Nombre"
            value="{{old('cla_UsuarioFirma', $data->cla_UsuarioFirma ?? '')}}" required>
    </div>
</div>

<div class="form-group row">
    <label for="cla_LlaveFirma" class="col-sm-12 col-lg-3 control-label text-sm-left text-lg-right requerido">Llave Firma</label>
    <div class="col-lg-8">
        <input type="text" name="cla_LlaveFirma" class="form-control" id="cla_LlaveFirma" placeholder="Nombre"
            value="{{old('cla_LlaveFirma', $data->cla_LlaveFirma ?? '')}}" required>
    </div>
</div>


<div class="form-group row">
    <label for="cla_UsuarioApi" class="col-sm-12 col-lg-3 control-label text-sm-left text-lg-right requerido">Usuario Api</label>
    <div class="col-lg-8">
        <input type="text" name="cla_UsuarioApi" class="form-control" id="cla_UsuarioApi" placeholder="Nombre"
            value="{{old('cla_UsuarioApi', $data->cla_UsuarioApi ?? '')}}" required>
    </div>
</div>


<div class="form-group row">
    <label for="cla_LlaveApi" class="col-sm-12 col-lg-3 control-label text-sm-left text-lg-right requerido">Llave Api</label>
    <div class="col-lg-8">
        <input type="text" name="cla_LlaveApi" class="form-control" id="cla_LlaveApi" placeholder="Nombre"
            value="{{old('cla_LlaveApi', $data->cla_LlaveApi ?? '')}}" required>
    </div>
</div>





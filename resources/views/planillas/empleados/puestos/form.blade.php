<input type="hidden" id="empPath" value="{{url('parametros/terminal')}}">
<input type="hidden" id="empCod" value="{{old('pues_empresa',$puesto->pues_empresa??'')}}">
<input type="hidden" id="terCod" value="{{old('pues_terminal',$puesto->pues_terminal??'')}}">

<fieldset class="border p-2 col-sm-12 col-lg-12">
    <legend class="w-auto">Datos del Puesto</legend>
    <div class="form-group row">
        <div class="col-lg-6 col-sm-12 mb-3">
            <div class="row">
                <label for="pues_desc_lg" class="col-md-12 col-sm-12 col-lg-4 text-sm-left text-lg-right requerido">Descripcion Larga</label>
                <div class="col-md-12 col-lg-8">
                    <textarea class="form-control" name="pues_desc_lg" id="pues_desc_lg" cols="30" rows="3">{{old('pues_desc_lg', $puesto->pues_desc_lg ?? '')}}</textarea>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-sm-12 mb-3">
            <div class="row">
                <label for="pues_desc_ct" class="col-md-12 col-sm-12 col-lg-4 text-sm-left text-lg-right requerido">Descripcion Corta</label>
                <div class="col-md-12 col-lg-7">
                    <input type="text" class="form-control" id="pues_desc_ct" name="pues_desc_ct" maxlength="15"
                           value="{{old('pues_desc_ct', $puesto->pues_desc_ct ?? '')}}" required>
                </div>
            </div>
        </div>

    </div>

</fieldset>


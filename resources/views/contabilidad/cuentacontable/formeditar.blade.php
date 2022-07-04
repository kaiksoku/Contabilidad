<input type="hidden" id="empPath" value="{{url('parametros/terminal')}}">
<input type="hidden" id="ctaNivel1" value="{{url('contabilidad/ctaNivel1')}}">
<input type="hidden" id="ctaNivel2" value="{{url('contabilidad/ctaNivel2')}}">
<input type="hidden" id="ctaNivel3" value="{{url('contabilidad/ctaNivel3')}}">
<input type="hidden" id="ctaNivel4" value="{{url('contabilidad/ctaNivel4')}}">
<input type="hidden" id="ctrcsts" value="{{url('contabilidad/centrocostos')}}">
<input type="hidden" id="ctaExcenta" value="{{url('contabilidad/ctaExcenta')}}">
<input type="hidden" id="actPath" value="{{url('activos/listaActivos')}}">
<input type="hidden" id="empCod" value="{{old('com_empresa',$data->com_empresa??'')}}">
<input type="hidden" id="terCod" value="{{old('com_terminal',$data->com_terminal??'')}}">
<input type="hidden" id="acuCod" value="{{old('act_cuentaDepAcum',$data->act_cuentaDepAcum??'')}}">
<input type="hidden" id="ctaPathAcum" value="{{url('contabilidad/ctaDepAcum')}}">


<input type="hidden" name="nomProveedor" id="nomProveedor">
<input type="hidden" id="linea" value="0">
<section class="row">
    <div class="col">
        <div class="card">
            <div class="card-body">
                <div class="form-group row">

                    <label for="empresa"
                        class="col-sm-12 col-lg-2 control-label text-sm-left text-lg-right requerido">Empresa</label>
                    <div class="col-sm-12 col-lg-9">
                        <input type= "text" name="empresa" id="empresa" class="form-control" placeholder="Empresa"
                        disabled value="{{$data->emp_NIT.' - '.$data->emp_siglas}}" data-nombre="{{$data->emp_siglas}}">
                        <input type="hidden" name="com_empresa" id="com_empresa" value="">
                    </div>
                    <div class="col-sm-12 col-lg-2">
                        <label id="nom_empresa" class="col-form-label-lg"></label>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="com_persona"
                        class="col-sm-12 col-lg-2 control-label text-sm-left text-lg-right requerido">Descripcion de la cuenta</label>
                        <div class="col-sm-12 col-lg-9">
                            <input type="text" name="cta_descripcion" id="cta_descripcion" class="form-control"
                            placeholder="Descripción" minlength="10" required value="{{old('cta_descripcion',$data->cta_descripcion??'')}}">
                        </div>
                    <div class="col-sm-12 col-lg-4">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="com_numDoc"
                        class="col-sm-12 col-lg-2 control-label text-sm-left text-lg-right requerido">
                        Centro de costos</label>
                    <div class="col-sm-12 col-lg-4">
                        <select id="cta_centrocosto" name="cta_centrocosto" class="form-control select2" placeholder="Centro de costos" required>
                        <option value="{{$centrocosto->cco_codigo}}">{{$centrocosto->cco_descripcion}}</option>
                        @foreach ($centrocostos as $Ctcsts)
                        <option value="{{$Ctcsts->cco_codigo}}">{{$Ctcsts->cco_descripcion}}</option>
                        @endforeach
                        </select>
                    </div>
                    <label for="com_serie"
                        class="col-sm-12 col-lg-1 control-label text-sm-left text-lg-right ">Excento</label>
                        <div class="col-sm-12 col-lg-3">
                            <input type="checkbox" name="cta_excento" id="cta_excento"
                                {{old('cta_excento',$data->cta_excento ?? '1')==1?"checked":""}} data-bootstrap-switch data-off-color="danger"
                                data-on-color="success" data-on-text="SI" data-off-text="NO">
                        </div>
                </div>

                <div class="form-group row">
                    <label for="com_descripcion"
                        class="col-sm-12 col-lg-2 control-label text-sm-left text-lg-right ">Observacion</label>
                    <div class="col-sm-12 col-lg-3">
                        <input type="text" name="cta_obs1" id="cta_obs1" class="form-control"
                            placeholder="Observacion" maxlength="5" value="{{old('cta_obs1',$data->cta_obs1??'')}}">
                    </div>
                    <label for="com_descripcion"
                        class="col-sm-12 col-lg-2 control-label text-sm-left text-lg-right ">Observacion</label>
                    <div class="col-sm-12 col-lg-4">
                        <input type="text" name="cta_obs2" id="cta_obs2" class="form-control"
                            placeholder="Observacion" maxlength="5" value="{{old('cta_obs2',$data->cta_obs2??'')}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="com_descripcion"
                        class="col-sm-12 col-lg-2 control-label text-sm-left text-lg-right ">Observacion</label>
                    <div class="col-sm-12 col-lg-4">
                        <input type="text" name="cta_obs3" id="cta_obs3" class="form-control"
                            placeholder="Observacion" maxlength="5" value="{{old('cta_obs1',$data->cta_obs3??'')}}">
                    </div>
                    <label for="com_serie"
                        class="col-sm-12 col-lg-1 control-label text-sm-left text-lg-right">Saldo</label>
                        <div class="col-sm-12 col-lg-3">
                            <input type="checkbox" name="cta_tipoSaldo" id="cta_tipoSaldo"
                            {{old('cta_tipoSaldo',$data->cta_tipoSaldo ?? '1')=='1'?"checked":""}} data-bootstrap-switch data-off-color="danger"
                                data-on-color="success" data-on-text="Deudor" data-off-text="Acreedor">
                        </div>
                </div>
            </div>
        </div>
    </div>
</section>

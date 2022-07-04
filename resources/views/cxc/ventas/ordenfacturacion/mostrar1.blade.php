<input type="hidden" id="empPath" value="{{ url('parametros/terminal') }}">
<input type="hidden" id="empCod" value="{{ old('ordf_empresa', $data->ordf_empresa ?? '') }}">
<input type="hidden" id="terCod" value="{{ old('ordf_terminal', $data->ordf_terminal ?? '') }}">

<section class="row">
    <div class="col">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Detalle Orden de Facturación</h3>
            </div>

            <div class="card-body">
                <form action="row"></form>
                <table class="table" style="width:100%">
                    <table style="width:100%">
                        <tr>
                            <th>Cliente</th>
                            <td>{{ $data->Cliente->per_nombre }}</td>
                        </tr>
                        <tr>
                            <th>NIT </th>
                            <td> {{ $data->Cliente->per_nit }}</td>
                        </tr>

                        <tr>
                            <th>Empresa</th>
                            <td>{{ $data->Empresa->emp_siglas }}</td>
                        </tr>
                        <tr>
                            <th>Terminal</th>
                            <td>{{ $data->Terminal->ter_nombre }}</td>
                        </tr>

                        <tr>
                            <th>Correlativo Interno</th>
                            <td>{{ $data->Correlativo->corr_correlativo }}</td>
                        </tr>

                        <tr>
                            <th>ETA</th>
                            <td>{{ \Carbon\Carbon::parse($data->ordf_eta)->format('d/m/Y') }}</td>
                        </tr>

                        <tr>
                            <th>Buque</th>
                            <td>{{ $data->ordf_buque }}</td>
                        </tr>

                        <tr>
                            <th>Viaje</th>
                            <td>{{ $data->ordf_viaje }}</td>
                        </tr>

                        <tr>
                            <th>Moneda</th>
                            <td>{{ $data->Moneda->mon_nombre }}</td>
                        </tr>

                        <tr>
                            <th>Tipo de Cambio</th>
                            <td>{{ Str::decimal($data->ordf_tipoCambio) }}</td>
                        </tr>
                        <tr>
                            <th>Descripción</th>
                            <td>{{ $data->ordf_descripcion }}</td>
                        </tr>
                        <tr>
                            <th>Total Orden de Facturación</th>
                            <td>{{ Str::decimal($data->ordf_total) }}</td>
                        </tr>
                    </table>
                </table>
            </div>
        </div>
    </div>
</section>



<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <table class="table">
            <thead>

                <th colspan="8" class="text-center">Detalle Servicios</th>
                </tr>

                <th>Servicio</th>
                <th>Cantidad</th>
                <th>Tarifa</th>
                <th>Sub total sin IVA</th>
                <th>IVA</th>
                <th>Total</th>
                <th>Total</th>

                </tr>
            </thead>
            <tbody>
                <tr>
                    {{ $SumaCantidad = 0, $SumaSubTotal = 0, $SumaIva = 0, $SumaTotal = 0 }}
                    @foreach ($data->detalleOrdenFacturacion as $linea)
                <tr>
                    <td>
                        @if ($linea->dof_producto)
                            <span>{{ $linea->Productos->prod_desc_lg }}</span>
                        @endif
                    </td>
                    <td >{{ Str::decimal($linea->dof_cantidad), $SumaCantidad += $linea->dof_cantidad }}</td>

                    <td>{{ Str::money($linea->dof_tarifa, $data->moneda->mon_simbolo . ' ') }}</td>

                    <td>{{ Str::money($linea->dof_tarifa * $linea->dof_cantidad, $data->moneda->mon_simbolo . ' '), $SumaSubTotal += $linea->dof_tarifa * $linea->dof_cantidad }}
                    </td>
                    <td>{{ Str::money($linea->dof_tarifa * $linea->dof_cantidad * 0.12, $data->moneda->mon_simbolo . ' '), $SumaIva += $linea->dof_tarifa * $linea->dof_cantidad * 0.12 }}
                    </td>
                    <td>{{ Str::money($linea->dof_tarifa * $linea->dof_cantidad + $linea->dof_tarifa * $linea->dof_cantidad * 0.12, $data->moneda->mon_simbolo . ' '), $SumaTotal += $linea->dof_tarifa * $linea->dof_cantidad + $linea->dof_tarifa * $linea->dof_cantidad * 0.12 }}
                    </td>
                    <td>Q.
                        {{ Str::decimal(($linea->dof_tarifa * $linea->dof_cantidad + $linea->dof_tarifa * $linea->dof_cantidad * 0.12) * $data->ordf_tipoCambio) }}

                    </td>
                </tr>



                @endforeach
                <tr>
                    <th style="text-align: left" class="letter"><strong>TOTAL DE PROFORMA </strong> </th>
                    <th  >{{ Str::decimal($SumaCantidad) }}</th>
                    <th></th>
                    <th > {{ Str::money($SumaSubTotal, $data->moneda->mon_simbolo . ' ') }} </th>
                    <th > {{ Str::money($SumaIva, $data->moneda->mon_simbolo . ' ') }} </th>
                    <th > {{ Str::money($SumaTotal, $data->moneda->mon_simbolo . ' ') }} </th>
                    <th >Q{{ Str::decimal($data->ordf_total) }} </th>
                </tr>
            </tbody>

        </table>
    </div>
</div>



<input type="hidden" class="form-control float-right" id="ordf_anulada" name="ordf_anulada"
    value="{{ $data->ordf_anulada }}">

<div class="form-group row">
    <div class="col-sm-12 col-lg-10">
        @can('crear cxp/facturas')
            @if ($data->ordf_factura == '')
                @if ($data->ordf_anulada == 1)
                    <p><a href="javascript:mostrar();" type="button" class="btn btn-success">Generar Factura </a></p>
                @else
                    <p><a href="javascript:mostrar();" type="button" class="btn btn-success disabled">Generar Factura </a>
                    </p>
                @endcan
            @endif
        @endif
    </div>
    <div class="col-sm-5 col-lg-2">
        @can('actualizar cxc/ventas/ordenfacturacion')
        @if ($data->ordf_anulada == 1)
            @if ($data->ordf_factura == '')
                <a href="{{ route('ordenfacturacion.anulacion', ['id' => $data->ordf_id]) }}" type="button"
                    id="boton"   class=" btn btn-danger btn-accion-tabla eliminar-ordenf" data-toggle="tooltip"
                 > Anular Orden de Facturación</a>
            @else
                <a href="{{ route('ordenfacturacion.anulacion', ['id' => $data->ordf_id]) }}" type="button"
                    id="boton"   class=" btn btn-danger btn-accion-tabla eliminar-ordenf disabled" data-toggle="tooltip"
                  > Anular Orden de Facturación</a>
                    @endcan
            @endif
        @endif

    </div>

</div>










<div id="flotante" style="display:none;">


    <div class="col-lg-6">
        <div class="card card-outline card-danger">
            <div class="card-header">
                <h3 class="card-title">Convertir Orden de Facturación a <strong>Factura</strong></h3>
            </div>
            <div class="card-body row">
                <table class="table" id="tabla-abono">
                    <thead class="thead-light">
                       <tr>Debe seleccionar la moneda en la que desea realizar la Factura</tr>
                       <td>Para facturar en <strong>Quetzales</strong>  ingrese el tipo de cambio</td>
                    </thead>
                    <tbody>

                    </tbody>
                </table>

                      </div>

        </div>
    </div>

    <div class="form-group row">
        <label for="tipofactura"
             class="col-sm-8 col-lg-8 control-label text-sm-left text-lg-right " id="factura">Formato de Factura</label>
             <div class="col-sm-8 col-lg-3">
                 <input type="checkbox" name="tipofactura" id="tipofactura" value="1"
                     {{old('tipofactura',$data->tipofactura ?? '1')==1?"checked":""}} data-bootstrap-switch data-off-color="danger"
                     data-on-color="success" data-on-text="Normal" data-off-text="Especial">
             </div>
     </div>

    <input type="hidden" class="form-control float-right" id="certificador" name="certificador"
            value="{{ $datas[0] }}">

    <div class="form-group row">

        <label for="ven_moneda"
            class="col-sm-12 col-sm-12 col-lg-1 control-label text-sm-left text-lg-right requerido">Moneda</label>
        <div class="col-sm-12 col-lg-2">
            <select name="ven_moneda" id="ven_moneda" class="form-control select2" placeholder="Moneda"
                required>
                @foreach ($moneda->getMonedas() as $item)
                    <option value="{{ $item->mon_id }}" data-simbolo="{{ $item->mon_simbolo }}"
                        {{ $item->mon_id == 1 ? 'Selected' : '' }}>
                        {{ $item->mon_nombre }}</option>
                @endforeach
            </select>
        </div>

        <label for="ven_tipoCambio"
            class="col-sm-12 col-sm-12 col-lg-3 control-label text-sm-left text-lg-right ">Tipo
            de Cambio</label>
        <div class="col-sm-12 col-lg-2">
            <input for="ven_tipoCambio" type="text" name="ven_tipoCambio" id="ven_tipoCambio" placeholder="Tipo de Cambio"
                class="form-control" value="{{ old('ven_tipoCambio', $data->ven_tipoCambio ?? '') }}"
                onkeypress='return validaNumericos(event,"D",this.value);'>
        </div>


    </div>



    <div class="form-group row">

        <label for="ven_fechaCert" class="col-sm-12 col-lg-1 control-label text-sm-left text-lg-right">Fecha</label>
        <div class="input-group col-sm-12 col-lg-2">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <i class="far fa-calendar-alt"></i>
                </span>
            </div>
            <input class="form-control float-right" id="ven_fechaCert" name="ven_fechaCert"
                value="{{ old('ven_fechaCert', $data->ven_fechaCert ?? '') }}">
        </div>

    </div>

    <div class="form-group row">
        <label for="ordf_numDoc" class="col-sm-12 col-lg-1 control-label text-sm-left text-lg-right" id="NoDoc" >Número
            Documento</label>
        <div class="input-group col-sm-12 col-lg-3">
            <input type="text" class="form-control float-right" id="ordf_numDoc" name="ordf_numDoc"
                placeholder="NUMDOC" value="{{ old('ven_numDoc', $data->ven_numDoc ?? '') }}">
        </div>

        <label for="ven_serie"
           id="serie" class="col-sm-12 col-lg-2 control-label text-sm-left text-lg-right  ">Serie</label>
        <div class="input-group col-sm-12 col-lg-3">
            <input type="text" class="form-control float-right" id="ordf_serie" name="ordf_serie"
                placeholder="SERIE" value="{{ old('ven_serie', $data->ven_serie ?? '') }}">
        </div>
    </div>


    <div class="form-group row">
        <label for="ordf_iiud"
            id="uuid" class="col-sm-12 col-lg-1 control-label text-sm-left text-lg-right">Autorización
        </label>
        <div class="input-group col-sm-12 col-lg-3">
            <input type="text" class="form-control float-right" id="ordf_iiud" name="ordf_iiud"
                placeholder="UUID" onchange="fel()" value="{{ old('ven_iiud', $data->ven_iiud ?? '') }}">
        </div>

        <label for="ven_enlacefactura" id="enlace"
                        class="col-sm-12 col-lg-2 control-label text-sm-left text-lg-right">Enlace Factura </label>
                    <div class="input-group col-sm-10 col-lg-3">
                        <input type="text" class="form-control float-right" id="ordf_enlacefactura"
                            name="ordf_enlacefactura" placeholder="Enlace Factura"
                            value="{{ old('ven_enlacefactura', $data->ven_enlacefactura ?? '') }}">
                    </div>

    </div>


    <div class="form-group row">
        <label for="ven_referencia1" class="col-sm-12 col-lg-1 control-label text-sm-left text-lg-right">Doc.
            Referencia</label>
        <div class="input-group col-sm-12 col-lg-2">

            <select class="form-control" name="sel_quantity" id="sel_quantity" onChange="mostrarValor(this.value);">
                <option value=" "> </option>
                <option value="P.O:">P.O: </option>
                <option value="Statement:">Statement: </option>
            </select>
        </div>
        <div class="input-group col-sm-12 col-lg-4">
            <input type="text" class="form-control float-right" id="ven_referencia" name="ven_referencia" value=" "
                placeholder="Doc. de Referencia" value="{{ old('ven_referencia', $data->ven_referencia ?? '') }}">


        </div>

    </div>

    <div class="form-group row">
        <label for="ven_contenedores"
            class="col-sm-12 col-lg-1 control-label text-sm-left text-lg-right ">Observaciones</label>
        <div class="input-group col-sm-12 col-lg-10">
            <textarea rows="5" cols="10" class="form-control float-right" id="ven_contenedores"
                name="ven_contenedores">
            </textarea>
        </div>
    </div>

    <div class="form-group row">


        <input type="hidden" class="form-control float-right" id="ordf_buque" name="ordf_buque"
            value="{{ $data->ordf_buque }}">

        <input type="hidden" class="form-control float-right" id="ordf_viaje" name="ordf_viaje"
            value="{{ $data->ordf_viaje }}">

        <input type="hidden" class="form-control float-right" id="ordf_eta" name="ordf_eta"
            value="{{ $data->ordf_eta }}">


        <input type="hidden" class="form-control float-right" id="ven_empresa" name="ven_empresa"
            value="{{ $data->ordf_empresa }}{{ old('ven_empresa', $data->ven_empresa ?? '') }}">

        <input type="hidden" class="form-control float-right" id="ven_fecha" name="ven_fecha"
            value="{{ $data->ordf_fecha }}{{ old('ven_fecha', \Carbon\Carbon::parse($data->ven_fecha) ?? '') }}">

        <input type="hidden" class="form-control float-right" id="ven_descripcion" name="ven_descripcion"
            value="{{ $data->ordf_descripcion }}{{ old('ven_descripcion', $data->ven_descripcion ?? '') }}">

        <input type="hidden" class="form-control float-right" id="ordf_eta" name="ordf_eta"
            value="{{ $data->ordf_eta }}">

        <input type="hidden" class="form-control float-right" id="ven_id" name="ven_id"
            value="{{ $data->ordf_id }}{{ old('ven_id', $data->ven_id ?? '') }}">

        <input type="hidden" class="form-control float-right" id="ven_terminal" name="ven_terminal"
            value="{{ $data->ordf_terminal }}{{ old('ven_terminal', $data->ven_terminal ?? '') }}">

        <input type="hidden" class="form-control float-right" id="ven_total" name="ven_total"
            value="{{ $data->ordf_total }}{{ old('ven_total', $data->ven_total ?? '') }}">

        <input type="hidden" class="form-control float-right" id="ven_iva" name="ven_iva"
            value="{{ $data->ordf_total - $data->ordf_total / 1.12 }}{{ old('ven_iva', $data->ven_iva ?? '') }}">

        <input type="hidden" class="form-control float-right" id="ven_persona" name="ven_persona"
            value="{{ $data->ordf_cliente }}{{ old('ven_persona', $data->ven_persona ?? '') }}">

        <input type="hidden" class="form-control float-right" id="ven_iiud" name="ven_iiud"
            value="{{ old('ven_iiud', $data->ven_iiud ?? '') }}">

        <input type="hidden" class="form-control float-right" id="ven_numDoc" name="ven_numDoc"
            value="{{ old('ven_numDoc', $data->ven_numDoc ?? '') }}">

        <input type="hidden" class="form-control float-right" id="ven_serie" name="ven_serie"
            value="{{ old('ven_serie', $data->ven_serie ?? '') }}">

        <input type="hidden" class="form-control float-right" id="ven_enlacefactura" name="ven_enlacefactura"
            value="{{ old('ven_enlacefactura', $data->ven_enlacefactura ?? '') }}">

        <input type="hidden" class="form-control float-right" id="detv_descuento" name="detv_descuento"
            value="{{ old('detv_descuento', $data->detv_descuento ?? '') }}">

        <tr>
            @foreach ($data->detalleOrdenFacturacion as $linea)
        <tr>
            <td>
                @if ($linea->dof_producto)
                    <input type="hidden" class="form-control float-right" id="detv_producto[]" name="detv_producto[]"
                        value="{{ $linea->dof_producto }}{{ old('detv_producto[]', $data->detv_producto ?? '') }}">

                @endif
            </td>
            <td> <input type="hidden" class="form-control float-right" id="detv_cantidad[]" name="detv_cantidad[]"
                    value="{{ $linea->dof_cantidad }}{{ old('detv_cantidad[]', $data->detv_cantidad ?? '') }}">
            </td>
            <td><input type="hidden" class="form-control float-right" id="detv_precioU[]" name="detv_precioU[]"
                    value="{{ $linea->dof_tarifa }}{{ old('detv_precioU[]', $data->detv_precioU ?? '') }}"></td>
        </tr>

        <td><input type="hidden" class="form-control float-right" id="iva[]" name="iva[]"
                value="{{ $linea->dof_tarifa * $linea->dof_cantidad * 0.12 }}{{ old('iva', $data->iva ?? '') }}">
        </td>

        <td><input type="hidden" class="form-control float-right" id="ivac[]" name="ivac[]"
                value="{{ $linea->dof_tarifa * $linea->dof_cantidad - ($linea->dof_tarifa * $linea->dof_cantidad) / 1.12 }}{{ old('ivac', $data->ivac ?? '') }}">
        </td>

        <td><input type="hidden" class="form-control float-right" id="totalq[]" name="totalq[]"
                value="{{ ($linea->dof_tarifa * $linea->dof_cantidad + $linea->dof_tarifa * $linea->dof_cantidad * 0.12) * $data->ordf_tipoCambio }}{{ old('totalq', $data->totalq ?? '') }}">
        </td>





        @endforeach





    </div>





    <div class="row">
        <div class="col-lg-6">
            <button type="submit" onclick="store()" class="btn btn-lg btn-outline-success float-right">Guardar</button>
        </div>
    </div>
</div>
</section>

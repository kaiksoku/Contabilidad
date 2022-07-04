@extends("layout.layout")
@section("titulo")
Pago a Poliza de Importacion
@endsection

@section('styles')
<link href="{{asset("assets/plugins/select2/css/select2.min.css")}}" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css")}}" rel="stylesheet"
    type="text/css" />
<link rel="stylesheet" href="{{asset("assets/plugins/daterangepicker/daterangepicker.css")}}">
@endsection

@section('scripts')
<script src="{{asset("assets/plugins/select2/js/select2.full.min.js")}}"></script>
<script src="{{asset("assets/plugins/select2/js/i18n/es.js")}}"></script>
<script src="{{asset("assets/plugins/inputmask/jquery.inputmask.bundle.js")}}"></script>
<script src="{{asset("assets/plugins/moment/moment.min.js")}}"></script>
<script src="{{asset("assets/plugins/daterangepicker/daterangepicker.js")}}"></script>
<script src="{{asset("assets/pages/scripts/cyb/cajas/anticipo/crear.js")}}" type="text/javascript"></script>
@endsection

@section('breadcrumbs')
{{ Breadcrumbs::render('anticipos.crear') }}
@endsection

@section('contenido')
<input type="hidden" id="routepath" value="{{url('cyb/bancos/anticipos/crear')}}">
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                @include('includes.form-error')
                @include('includes.mensaje')
                <div class="card card-outline card-success">
                    <div class="card-header">
                        <h3 class="card-title">Generar pago a factura<small></small></h3>
                        <div class="card-tools">
                            <a href="{{route('anticipos')}}" class="btn btn-block btn-info btn-sm">
                                Volver al Listado<i class="fas fa-arrow-circle-left pl-1"></i></a>
                        </div>
                    </div>
                        <form action="{{route('anticipos.guardarPI')}}" class="form-horizontal" method="post" id="form-general">
                            <div class="card-body">
                            @csrf
                                <div class="form-group row">
                                        <label for="per_nit" class="col-sm-12 col-lg-2 control-label text-sm-left text-lg-right requerido">Numero de Cheque/Transferencia</label>
                                        <div class="col-sm-12 col-lg-4">
                                            <select name="che_numero" class="form-control select2" id="exampleFormControlSelect1" required>
                                                <option value="">Seleccionar cheque/transferencia</option>
                                                @foreach($cheques as $cheque)
                                                <option value="{{$cheque->che_id}}">{{$cheque->Cheques}}</option>
                                                    @endforeach
                                            </select>
                                        </div>
                                        <label for="per_nit"
                                        class="col-sm-12 col-lg-2 control-label text-sm-left text-lg-right requerido">Poliza de Importacion</label>
                                    <div class="col-sm-12 col-lg-4">
                                        <select name="dant_tipo" class="form-control select2" id="exampleFormControlSelect1" required>
                                            <option value="">Seleccionar Poliza de Importacion</option>
                                            @foreach($movimientos as $movimiento)
                                            <option value="{{$movimiento->poim_id}}">{{$movimiento->Polizas}}</option>
                                                @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="per_nit"
                                        class="col-sm-12 col-lg-2 control-label text-sm-left text-lg-right requerido">Proveedores</label>
                                    <div class="col-sm-12 col-lg-4">
                                        <select name="che_beneficiario" class="form-control select2" id="inputbeneficiario" required>
                                            <option value="">Escoja un proveedor</option>
                                            @foreach($proveedores as $proveedor)
                                            <option value="{{$proveedor['pro_id']}}"
                                                {{old('che_beneficiario')==$proveedor['pro_id'] ? 'selected':''}}>
                                                {{$proveedor['pro_id']}} - {{$proveedor->Persona->per_nombre}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="per_nit" class="col-sm-12 col-lg-2 control-label text-sm-left text-lg-right requerido">Descripción</label>
                                    <div class="col-sm-12 col-lg-10">
                                        <textarea name="che_descripcion" class="form-control" id="inputdescripcion" rows="3"
                                            required
                                            placeholder="Ingrese una descripción...">{{old('che_descripcion')}}</textarea>
                                    </div>
                                </div>
                            </div>
                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col-lg-4"></div>
                                        <div class="col-lg-4 text-center">
                                            @include('includes.boton-form-crear')
                                        </div>
                                    </div>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
</section>

@endsection

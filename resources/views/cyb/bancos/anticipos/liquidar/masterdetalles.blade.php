@extends("layout.layout")
@section("titulo")
    Autorización de Detalles de Anticipos
@endsection

@section('styles')
    <link href="{{asset("assets/plugins/select2/css/select2.min.css")}}" rel="stylesheet" type="text/css" />
    <link href="{{asset("assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css")}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{asset("assets/plugins/daterangepicker/daterangepicker.css")}}">
    <link rel="stylesheet" href="{{asset("assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css")}}">
    <link rel="stylesheet" href="{{asset("assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css")}}">
@endsection

@section("scriptPlugins")
    <script src="{{asset("assets/plugins/datatables/jquery.dataTables.min.js")}}"></script>
    <script src="{{asset("assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js")}}"></script>
    <script src="{{asset("assets/plugins/datatables-responsive/js/dataTables.responsive.min.js")}}"></script>
    <script src="{{asset("assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js")}}"></script>
@endsection


@section('scripts')
    <script src="{{asset("assets/plugins/select2/js/select2.full.min.js")}}"></script>
    <script src="{{asset("assets/plugins/select2/js/i18n/es.js")}}"></script>
    <script src="{{asset("assets/plugins/inputmask/jquery.inputmask.bundle.js")}}"></script>
    <script src="{{asset("assets/plugins/moment/moment.min.js")}}"></script>
    <script src="{{asset("assets/plugins/daterangepicker/daterangepicker.js")}}"></script>
    <script src="{{asset("assets/pages/scripts/cyb/bancos/cuentasbancarias/table.js")}}" type="text/javascript"></script>
    <script src="{{asset("assets/pages/scripts/cyb/bancos/autorizacion/antLiquidar.js")}}" type="text/javascript"></script>
@endsection

@section('breadcrumbs')
    {{ Breadcrumbs::render()}}
@endsection

@section('contenido')
    <input type="hidden" id="routepath" value="{{url('cyb/bancos/anticipos/crear')}}">
    @inject('empleado','App\Models\Planilla\Empleado')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    @include('includes.mensaje')
                    @include('includes.form-error')
                    <div class="card card-outline card-success">
                        <div class="card-header">
                            <h3 class="card-title">Detalles del Anticipo con el id: {{$nombre->ant_id}} y número: {{$nombre->ant_numero}}<small></small></h3>
                            <div class="card-tools">
                                <div class="card-tools">
                                    <a href="{{route('liquidar')}}" class="btn btn-block btn-info btn-sm">
                                        Volver a los Anticipos<i class="fas fa-arrow-circle-left pl-1"></i></a>
                                </div>
                            </div>

                        </div>
                        <div class="card-body">
                            <input type="hidden" id="autPath" value="{{url('cyb/bancos/anticipos/liquidar/revision')}}">

                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="tabla-data">
                                    <thead class='thead-dark'>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Anticipo</th>
                                        <th scope="col">Descripcion Factura</th>
                                        <th scope="col">Documento</th>
                                        <th scope="col">Monto</th>
                                        <th scope="col">Opciones</th>
                                    </thead>
                                    <tbody>
                                    @foreach($detalles as $detalle)
                                        <tr>
                                            <td scope="row">{{$numeral=$numeral+1}}</td>
                                            <td>{{$detalle->DetalleAnticipo->ant_numero}}</td>
                                            <td>{{$detalle->Factura->com_descripcion}}</td>
                                            <td>{{$detalle['dant_documento']}}</td>
                                            <td>{{Str::money($detalle['dant_monto'],"Q ")}}</td>
                                            <td>
                                                @can('eliminar cyb/bancos/anticipos/liquidar')
                                                    <a href="{{route('detalle.anticipos.eliminar', ['id'=>$detalle['dant_linea']])}}"
                                                       class="btn-accion-tabla eliminar-registro" data-toggle="tooltip"
                                                       title="Eliminar este registro">
                                                        <i class="text-danger far fa-trash-alt"></i></a>
                                                @else
                                                    <a href=""
                                                       class="btn-accion-tabla eliminar-registro disabled" data-toggle="tooltip"
                                                       title="Eliminar este registro">
                                                        <i class="text-danger far fa-trash-alt"></i></a>
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection


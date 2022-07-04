@extends("layout.layout")
@section("titulo")
Compras y Servicios
@endsection

@if ($datas->count()>12)


@section("styles")
<link rel="stylesheet" href="{{asset("assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css")}}">
<link rel="stylesheet" href="{{asset("assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css")}}">
@endsection

@section("scriptPlugins")
<script src="{{asset("assets/plugins/datatables/jquery.dataTables.min.js")}}"></script>
<script src="{{asset("assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js")}}"></script>
<script src="{{asset("assets/plugins/datatables-responsive/js/dataTables.responsive.min.js")}}"></script>
<script src="{{asset("assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js")}}"></script>
@endsection

@section("scripts")
<script src="{{asset("assets/pages/scripts/admin/table.js")}}" type="text/javascript"></script>
@endsection

@endif

@section('breadcrumbs')
{{ Breadcrumbs::render('facturas') }}
@endsection


@section('contenido')
<input type="hidden" id="routepath" value="{{url('cxp/facturas')}}">
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                @include('includes.mensaje')
                @include('includes.form-error')
                <div class="card card-outline card-info">
                    <div class="card-header">
                        <h3 class="card-title">Facturas de Compras y Servicios <small></small></h3>
                        <div class="row d-flex justify-content-lg-end justify-content-sm-center">
                            <div class="bd-highlight p-2">
                                @can('crear cxp/facturas')
                                <a href="{{route('facturas.crear')}}" class="btn btn-block btn-success btn-sm" id="crear">
                                    Nuevo registro<i class="fa fa-fw fa-plus-circle pl-1"></i></a>
                                @else
                                <a href="{{route('facturas.crear')}}" class="btn btn-block btn-success btn-sm disabled">
                                    Nuevo registro<i class="fa fa-fw fa-plus-circle pl-1"></i></a>
                                @endcan
                            </div>
                            <div class="bd-highlight p-2">
                                @can('crear cyb/bancos/anticipos/crear')
                                <a href="{{route('anticipos.facturaunica')}}" class="btn btn-block btn-success btn-sm" id="crear">
                                    Asignar pago<i class="fa fa-fw fa-plus-circle pl-1"></i></a>
                                @else
                                <a href="{{route('anticipos.facturaunica')}}" class="btn btn-block btn-success btn-sm disabled">
                                    Asignar pago<i class="fa fa-fw fa-plus-circle pl-1"></i></a>
                                @endcan
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-hover" id="tabla-data">
                            <thead class='thead-dark'>
                                <tr>
                                    <th>Proveedor</th>
                                    <th>Número de Factura</th>
                                    <th>Serie</th>
                                    <th>Fecha</th>
                                    <th>Monto</th>
                                    <th>Tipo de Compra</th>
                                    <th class="width70">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($datas as $data)
                                <tr>
                                    <td>{{$data->Persona->per_nombre}}</td>
                                    <td>{{$data->com_numDoc}}</td>
                                    <td>{{$data->com_serie}}</td>
                                    <td>{{$data->com_fecha}}</td>
                                    <td class="text-right">{{Str::money($data->com_monto,'Q ')}}</td>
                                    <td>{{$data->TipoCompra->tipc_descripcion}}</td>
                                    <td>
                                        @can('ver cxp/facturas')
                                        <a href="{{route('facturas.mostrar',['id'=> $data->com_id])}}"
                                            class="btn-accion-tabla mr-4" data-toggle="tooltip"
                                            title="Mostrar este registro">
                                            <i class="far fa-eye text-dark"></i></a>
                                        @else
                                        <a href="{{route('facturas.mostrar',['id'=> $data->com_id])}}"
                                            class="btn-accion-tabla mr-4 disabled" data-toggle="tooltip"
                                            title="Mostrar este registro">
                                            <i class="far fa-eye text-dark"></i></a>
                                        @endcan
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

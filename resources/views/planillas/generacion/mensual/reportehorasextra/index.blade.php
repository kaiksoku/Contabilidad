@extends("layout.layout")
@section("titulo")
    Reporte de Horas Extras
@endsection

@section('breadcrumbs')
    {{ Breadcrumbs::render('reporte-horae') }}
@endsection
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
    <script src="{{asset("assets/pages/scripts/planillas/generacion/mensual/reportehorae/table.js")}}"
            type="text/javascript"></script>
@endsection


@section('contenido')
    @inject('empleado','App\Models\Planilla\Empleado')
    <input type="hidden" id="routepath" value="{{url('planillas/generacion/mensual')}}">
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    @include('includes.mensaje')
                    @include('includes.form-error')
                    <div class="card card-outline card-info">
                        <div class="card-header">
                            <span class="card-title"><small>Reporte de Horas Extras</small></span>
                            <div class="row d-flex justify-content-lg-end justify-content-sm-center">
                                <div class="bd-highlight p-2">
                                    <a class="btn btn-block btn-success btn-sm my-0 mx-2  @can('crear planillas/generacion/mensual') @else disabled @endcan" href="{{route('reporte-horae.crear')}}"
                                       id="crear">
                                        Crear Reporte <i class="fa fa-fw fa-plus-circle pl-1"></i></a>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover" id="tabla-data" cellspacing="0"
                                           width="100%">
                                        <thead class='thead-dark'>
                                        <tr>
                                            <th>#</th>
                                            <th>Empleado</th>
                                            <th>Fecha</th>
                                            <th>Horas</th>
                                            <th>Tipo</th>
                                            <th>Descripcion</th>
                                            <th>Empresa</th>
                                            <th>Terminal</th>
                                            <th>Acciones</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($datas as $data)
                                            <tr>
                                                <td>{{$data->ree_id}}</td>
                                                <td>{{strtoupper($empleado->getNombreCompleto($data->Salario->sal_empleado))}}</td>
                                                <td>{{$data->ree_fecha}}</td>
                                                <td>{{$data->ree_horas}}</td>
                                                <td>{{$data->ree_tipo=='E'?"EXTRAS":"ORDINARIAS"}}</td>
                                                <td>{{$data->ree_descripcion}}</td>
                                                <td>{{$data->Salario->Empresa->emp_siglas}}</td>
                                                <td>{{$data->Salario->Terminal->ter_abreviatura}}</td>
                                                <td>
                                                    <a href="{{route('reporte-horae.editar',['id'=> $data->ree_id])}}"
                                                       class="btn-accion-tabla  @can('actualizar planillas/empleados') @else disabled @endcan"
                                                       data-toggle="tooltip"
                                                       title="Editar este registro">
                                                        <i class="far fa-edit"></i></a>

                                                    <a href="{{route('reporte-horae.eliminar',['id'=> $data->ree_id])}}"
                                                       class="btn-accion-tabla @can('actualizar planillas/empleados') @else disabled @endcan eliminar-registro"
                                                       data-toggle="tooltip"
                                                       title="Eliminar este registro">
                                                        <i class="text-danger far fa-trash-alt"></i></a>


                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>

                                </div>

                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
@endsection


@extends("layout.layout")
@section("titulo")
    Salarios de Empleado
@endsection
@inject('empleado','App\Models\Planilla\Empleado')

@section('breadcrumbs')
    {{ Breadcrumbs::render('empleados-salario',$id,$nombre) }}
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
    <script src="{{asset("assets/pages/scripts/planillas/empleado/salarios/table.js")}}" type="text/javascript"></script>
@endsection


@section('contenido')

    <input type="hidden" id="routepath" value="{{url('planillas/empleados')}}">

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    @include('includes.mensaje')
                    @include('includes.form-error')
                    <div class="card card-outline card-info">
                        <div class="card-header">
                            <h3 class="card-title"> <small>Salario de Empleado {{$nombre}}</small></h3>
                            <div class="card-tools">
                                    <a href="{{route('empleados-salario.crear',$id)}}"
                                       class="btn btn-block btn-success btn-sm @can('crear planillas/empleados') @else disabled @endcan"
                                       id="crear">
                                        Nuevo registro<i class="fa fa-fw fa-plus-circle pl-1"></i></a>

                            </div>
                        </div>
                        <div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover" id="tabla-data" cellspacing="0"
                                           width="100%">
                                        <thead class='thead-dark'>
                                        <tr>
                                            <th>Empresa</th>
                                            <th>Terminal</th>
                                            <th>Puesto</th>
                                            <th>Salario</th>
                                            <th>Tipo</th>
                                            <th>Inicio</th>
                                            <th>Fin</th>
                                            <th >Acciones</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($salarios as $data)

                                            <tr>
                                                <td>{{$data->Empresa->emp_siglas}}</td>
                                                <td>{{$data->Terminal->ter_abreviatura}}</td>
                                                <td>{{$data->Puesto->pues_desc_ct}}</td>
                                                <td>{{ Str::money($data->sal_salario,'Q.') }}</td>
                                                <td>{{$data->sal_tipo}}</td>
                                                <td>{{$data->sal_inicio}}</td>
                                                <td>{{$data->sal_fin}}</td>
                                                <td>
                                                    <a href="{{route('empleados-salario.editar',['id'=> $data->sal_id])}}"
                                                       class="btn-accion-tabla  @can('actualizar planillas/empleados') @else disabled @endcan"
                                                       data-toggle="tooltip"
                                                       title="Editar este registro">
                                                        <i class="far fa-edit"></i></a>


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

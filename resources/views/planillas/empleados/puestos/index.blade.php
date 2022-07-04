@extends("layout.layout")
@section("titulo")
    Puestos
@endsection

@section('breadcrumbs')
    {{ Breadcrumbs::render('puestos') }}
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
    <script src="{{asset("assets/pages/scripts/planillas/empleado/table.js")}}" type="text/javascript"></script>
    <script src="{{asset("assets/pages/scripts/planillas/empleado/nuevo.js")}}" type="text/javascript"></script>
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
                            <h3 class="card-title">Puestos <small></small></h3>
                            <div class="card-tools">
                                @can('crear planillas/empleados')
                                    <a href="{{route('puestos.crear')}}"
                                       class="btn btn-block btn-success btn-sm"
                                       id="crear">
                                        Nuevo registro<i class="fa fa-fw fa-plus-circle pl-1"></i></a>
                                @else
                                    <a href="{{route('puestos.crear','#')}}"
                                       class="btn btn-block btn-success btn-sm disabled">
                                        Nuevo registro<i class="fa fa-fw fa-plus-circle pl-1"></i></a>
                                @endcan
                            </div>
                        </div>
                        <div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover" id="tabla-data" cellspacing="0"
                                           width="100%">
                                        <thead class='thead-dark'>
                                        <tr>
                                            <th class="col-lg">Descripcion</th>
                                            <th class="col-lg">Descripcion Corta</th>
                                            <th class="col-lg">Acciones</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($datas as $data)

                                            <tr>
                                                <td>{{$data->pues_desc_lg}}</td>
                                                <td>{{$data->pues_desc_ct}}</td>
                                                <td>

                                                    <a href="{{route('puestos.editar',['id'=> $data->pues_id])}}"
                                                       class="btn-accion-tabla  @can('actualizar planillas/empleados') @else disabled @endcan"
                                                       data-toggle="tooltip"
                                                       title="Editar este registro">
                                                        <i class="far fa-edit"></i></a>

                                                    <a href="{{route('puestos.eliminar',['id'=> $data->pues_id])}}"
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

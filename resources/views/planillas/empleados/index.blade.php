@extends("layout.layout")
@section("titulo")
    Empleados
@endsection

@section('breadcrumbs')
    {{ Breadcrumbs::render('empleados') }}
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
    @inject('empleado','App\Models\Planilla\Empleado')

    <input type="hidden" id="routepath" value="{{url('planillas/empleados')}}">

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    @include('includes.mensaje')
                    @include('includes.form-error')
                    <div class="card card-outline card-info">
                        <div class="card-header">
                            <h3 class="card-title">Empleados <small></small></h3>
                            <div class="row d-flex justify-content-lg-end justify-content-sm-center">
                                <div class="bd-highlight p-2">

                                    <a href="{{route('empleados.crear')}}"
                                       class="btn btn-block btn-success btn-sm @can('crear planillas/empleados') @else disabled  @endcan"
                                       id="crear">
                                        Nuevo registro<i class="fa fa-fw fa-plus-circle pl-1"></i></a>
                                </div>
                                <div class="bd-highlight p-2">
                                <a href="{{route('puestos')}}"
                                   class="btn btn-block btn-success btn-sm @can('crear planillas/empleados') @else disabled  @endcan"
                                   id="crear">
                                    Puestos<i class="fa fa-fw fa-plus-circle pl-1"></i></a>
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
                                            <th class="col-lg">Nombre</th>
                                            <th class="col-lg">Doc.ID</th>
                                            <th class="col-lg">Inicio&nbsp;Retiro</th>
                                            <th class="col-lg">Jornada</th>
                                            {{--                                            <th class="col-lg">Tipo&nbsp;Salario</th>--}}
                                            {{--                                            <th class="col-lg">Salario</th>--}}
                                            {{--                                            <th class="col-lg">Empresa</th>--}}
                                            {{--                                            <th class="col-lg">Terminal</th>--}}
                                            <th class="col-lg">Acciones</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($datas as $data)


                                            <tr>
                                                <td>{{strtoupper($empleado->getNombreCompleto($data->empl_id))}}</td>
                                                <td style="min-width: 200px">{{$data->empl_tipoDocID==1 ? 'DPI' : ($data->empl_tipoDocID==2 ? 'PARTIDA DE NACIMIENTO' : 'PASAPORTE')}}
                                                    :&nbsp;{{$data->empl_docID}}</td>
                                                <td style="min-width: 200px">{{Carbon\Carbon::parse($data->empl_inicio)->format('Y/m/d')}}
                                                    &nbsp;@if($data->empl_retiro){{Carbon\Carbon::parse($data->empl_retiro)->format('Y/m/d')}} @else
                                                        N/A @endif</td>
                                                <td style="min-width: 200px">{{$data->empl_jornada==1 ? 'Diurna' : ($data->empl_jornada==2 ? 'Mixta' : ($data->empl_jornada==3 ? 'Nocturna' : 'No esta sujeto a jornada'))}}</td>
                                                {{--                                                <td>{{$data->empl_tipoSalario=='T'?'Por Turnos':'Mensual'}}</td>--}}
                                                {{--                                                <td>{{Str::money( $data->empl_salario,'Q.')}}</td>--}}
                                                {{--                                                <td>{{$data->Empresa->emp_siglas}}</td>--}}
                                                {{--                                                <td>{{$data->Terminal->ter_abreviatura}}</td>--}}
                                                <td>

                                                    <a href="{{route('empleados-salario',['id'=> $data->empl_id])}}"
                                                       class="btn-accion-tabla  @can('crear planillas/empleados') @else disabled @endcan"
                                                       data-toggle="tooltip"
                                                       title="Ver salarios">
                                                        <i class="fas fa-money-check"></i></a>
                                                    <a href="{{route('empleados.editar',['id'=> $data->empl_id])}}"
                                                       class="btn-accion-tabla  @can('actualizar planillas/empleados') @else disabled @endcan"
                                                       data-toggle="tooltip"
                                                       title="Editar este registro">
                                                        <i class="far fa-edit"></i></a>

                                                    <a href="{{route('empleados.eliminar',['id'=> $data->empl_id])}}"
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

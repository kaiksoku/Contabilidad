@extends("layout.layout")
@section("titulo")
    Salario de Empleado
@endsection

@section('styles')
    <link rel="stylesheet" href="{{asset("assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css")}}">
    <link rel="stylesheet" href="{{asset("assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css")}}">
    <link href="{{asset("assets/plugins/select2/css/select2.min.css")}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset("assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css")}}" rel="stylesheet"
          type="text/css"/>
    <link rel="stylesheet" href="{{asset("assets/plugins/daterangepicker/daterangepicker.css")}}">
    <style>
        select[readonly] ~ .select2.select2-container .selection [role="combobox"] {
            background: repeating-linear-gradient(135deg, #dadada, #dadada 10px, rgba(255, 255, 255, 0.66) 10px, rgba(255, 255, 255, 0.66) 20px) !important;
            box-shadow: inset 0 0 0px 1px #77859133;
        }
    </style>
@endsection

@section('breadcrumbs')
    {{ Breadcrumbs::render('empleados-salario.crear',$id,$nombre) }}
@endsection

@section('scripts')
    <script src="{{asset("assets/pages/scripts/planillas/empleado/salarios/crear.js")}}"
            type="text/javascript"></script>
    <script src="{{asset("assets/plugins/select2/js/select2.full.min.js")}}"></script>
    <script src="{{asset("assets/plugins/select2/js/i18n/es.js")}}"></script>
    <script src="{{asset("assets/plugins/moment/moment.min.js")}}"></script>
    <script src="{{asset("assets/plugins/daterangepicker/daterangepicker.js")}}"></script>
    <script src="{{asset("assets/plugins/bs-custom-file-input/bs-custom-file-input.min.js")}}"></script>
    <script src="{{asset("assets/plugins/inputmask/jquery.inputmask.bundle.js")}}"></script>
@endsection

@section("scriptPlugins")
    <script src="{{asset("assets/plugins/datatables/jquery.dataTables.min.js")}}"></script>
    <script src="{{asset("assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js")}}"></script>
    <script src="{{asset("assets/plugins/datatables-responsive/js/dataTables.responsive.min.js")}}"></script>
    <script src="{{asset("assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js")}}"></script>
@endsection

@section('contenido')
    <input type="hidden" id="routepath" value="{{url('planillas/empleados')}}">
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    @include('includes.form-error')
                    @include('includes.mensaje')
                    <div class="card card-outline card-success">
                        <div class="card-header">
                            <h3 class="card-title">Salario de Empleado {{$nombre}}<small></small></h3>
                            <div class="card-tools">
                                <a href="{{route('empleados-salario',$salario->sal_empleado)}}" class="btn btn-block btn-info btn-sm">Volver a
                                    Listado<i class="fas fa-arrow-circle-left pl-1"></i></a>
                            </div>
                        </div>
                        <form action="{{route('empleados-salario.actualizar',['id'=>$salario->sal_id])}}" id="form-general"
                              class="form-horizontal" method="POST" autocomplete="off">
                            @csrf
                            @method('PUT')

                            <div class="card-body">
                                @include('planillas.empleados.salarios.form')
                            </div>
                            <!-- /.card-body -->
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

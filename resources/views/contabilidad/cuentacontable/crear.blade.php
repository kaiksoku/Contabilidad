@extends("layout.layout")
@section("titulo")
Cuentas Contables
@endsection

@section('styles')
<link href="{{asset("assets/plugins/select2/css/select2.min.css")}}" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css")}}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="{{asset("assets/plugins/daterangepicker/daterangepicker.css")}}">
@endsection

@section('scripts')
<script src="{{asset("assets/plugins/select2/js/select2.full.min.js")}}"></script>
<script src="{{asset("assets/plugins/select2/js/i18n/es.js")}}"></script>
<script src="{{asset("assets/plugins/inputmask/jquery.inputmask.bundle.js")}}"></script>
<script src="{{asset("assets/plugins/moment/moment.min.js")}}"></script>
<script src="{{asset("assets/plugins/daterangepicker/daterangepicker.js")}}"></script>

<script src="{{asset("assets/pages/scripts/contabilidad/cuentacontable/crear.js")}}" type="text/javascript"></script>
<script src="{{asset("assets/pages/scripts/contabilidad/cuentacontable/nuevo.js")}}" type="text/javascript"></script>
@endsection


@section('breadcrumbs')
{{ Breadcrumbs::render('cuentacontable.crear') }}
@endsection


@section('contenido')
@inject('pro', 'App\Models\cxp\Proveedor')
@inject('tcom', 'App\Models\Admin\TipoCompra')
@inject('comb','App\Models\Admin\TipoCombustible')
<input type="hidden" id="routepath" value="{{url('contabilidad/cuentascontables')}}">
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                @include('includes.form-error')
                @include('includes.mensaje')
                <div class="card card-outline card-success">

                    <form action="{{route('cuentacontable.guardar')}}" id="form-general" class="form-horizontal" method="POST" autocomplete="off">
                        <div class="card-body">
                            @csrf
                            @include('contabilidad.cuentacontable.form')
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

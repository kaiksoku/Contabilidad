@extends("layout.layout")
@section("titulo")
Pólizas de Importación
@endsection

@section('styles')
<link href="{{asset("assets/plugins/select2/css/select2.min.css")}}" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css")}}" rel="stylesheet"
    type="text/css" />
<link rel="stylesheet" href="{{asset("assets/plugins/daterangepicker/daterangepicker.css")}}">
@endsection

@section('breadcrumbs')
{{ Breadcrumbs::render('importacion.crear') }}
@endsection

@section('scripts')
<script src="{{asset("assets/pages/scripts/cxp/importacion/crear.js")}}" type="text/javascript"></script>
<script src="{{asset("assets/plugins/select2/js/select2.full.min.js")}}"></script>
<script src="{{asset("assets/plugins/select2/js/i18n/es.js")}}"></script>
<script src="{{asset("assets/plugins/moment/moment.min.js")}}"></script>
<script src="{{asset("assets/plugins/daterangepicker/daterangepicker.js")}}"></script>
@endsection

@section('contenido')
@inject('mon','App\Models\Admin\Moneda')
<input type="hidden" id="routepath" value="{{url('cxp/importacion')}}">
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                @include('includes.form-error')
                @include('includes.mensaje')
                <div class="card card-outline card-success">
                    <div class="card-header">
                        <h3 class="card-title">Crear Póliza de Importación<small></small></h3>
                        <div class="card-tools">
                            <a href="{{route('importacion')}}" class="btn btn-block btn-info btn-sm">
                                Volver a Listado<i class="fas fa-arrow-circle-left pl-1"></i></a>
                        </div>
                    </div>
                    <form action="{{route('importacion.guardar')}}" id="form-general" class="form-horizontal" method="POST"
                        autocomplete="off">
                        <div class="card-body">
                            @csrf
                            @include('cxp.importacion.form')
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-lg-4"></div>
                                <div class="col-lg-2 text-center">
                                    <button type="submit" id="submit"
                                        class="btn btn-lg btn-outline-success float-right disabled">Guardar</button>
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

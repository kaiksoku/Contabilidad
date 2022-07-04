@extends("layout.layout")
@section("titulo")
Poliza
@endsection

@section('breadcrumbs')
{{ Breadcrumbs::render('poliza.mostrar',$data) }}
@endsection

@section('contenido')
<input type="hidden" id="routepath" value="{{url('contabilidad/poliza')}}">
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-outline card-dark">
                    <div class="card-header">
                        <h3 class="card-title">Mostrar Poliza: {{$data->pol_id}}<small></small></h3>
                        <div class="card-tools">
                            <a href="{{route('poliza')}}" class="btn btn-block btn-info btn-sm">
                                Volver a Listado<i class="fas fa-arrow-circle-left pl-1"></i></a>
                                <a href="{{route('poliza.pdf',['id'=>$data->pol_id])}}" class="btn btn-outline-danger btn-sm">
                                    Exportar a PDF<i class="far fa-file-pdf pl-1"></i></a>

                        </div>
                    </div>
                    <div class="card-body row">
                        <div class="col-lg-8">

                            <table class="table" style="width:100%">
                                <table style="width:100%">
                                    <tr>
                                        <th>No</th>
                                        <th>{{ $data->pol_numero}}</th>

                                    </tr>
                                    <tr>
                                        <th>Fecha </th>
                                        <th>{{ \Carbon\Carbon::parse($data->pol_fecha)->format('d/m/Y') }}</td></th>

                                    </tr>

                                    <tr>
                                        <th>Descripción</th>
                                        <th>{{$data->pol_descripcion}}</th>

                                    </tr>
                                    <tr>
                                        <th>Correlativo</th>
                                        <th>{{$data->pol_correlativo}}</th>
                                    </tr>

                                </table>


                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

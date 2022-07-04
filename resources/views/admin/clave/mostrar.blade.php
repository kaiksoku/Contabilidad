@extends("layout.layout")
@section("titulo")
Claves
@endsection

@section('breadcrumbs')
{{ Breadcrumbs::render('clave.mostrar',$data) }}
@endsection

@section('contenido')
<input type="hidden" id="routepath" value="{{url('admin/clave')}}">
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-outline card-dark">
                    <div class="card-header">
                        <h3 class="card-title">Mostrar Clave: {{$data->cla_empresa}}<small></small></h3>
                        <div class="card-tools">
                            <a href="{{route('clave')}}" class="btn btn-block btn-info btn-sm">
                                Volver a Listado<i class="fas fa-arrow-circle-left pl-1"></i></a>
                        </div>
                    </div>
                    <div class="card-body row">
                        <div class="col-lg-8">

                            <table class="table" style="width:100%">
                                <table style="width:100%">
                                    <tr>
                                        <th>Usuario Firma </th>
                                        <td>{{$data->cla_UsuarioFirma}}</td>
                                    </tr>
                                    <tr>
                                        <th>Llave </th>
                                        <td> {{$data->cla_LlaveFirma}}</td>
                                    </tr>

                                    <tr>
                                        <th>Usuario</th>
                                        <td>{{$data->cla_UsuarioApi}}</td>
                                    </tr>
                                    <tr>
                                        <th>Llave Api</th>
                                        <td>{{$data->cla_LlaveApi}}</td>
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

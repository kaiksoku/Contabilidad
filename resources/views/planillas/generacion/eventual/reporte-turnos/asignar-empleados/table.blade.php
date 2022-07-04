<fieldset class="border p-2 col-sm-12 col-lg-12 my-3">
    <div class="table-responsive">
        <table class="table table-striped table-hover" id="tabla-data" cellspacing="0"
               width="100%">
            <thead class='thead-dark'>
            <tr>
                <th>Empleado</th>
                <th>Turnos</th>
                <th>Extras</th>
                <th>Ordinales</th>
                <th>Descripcion</th>
                <th>Eliminar</th>

            </tr>
            </thead>
            <tbody>
            @foreach (session('dataEmpleadosSeleccionados')??[]  as $key=> $data)
                <tr>
                    <td>{{strtoupper($empleado->getNombreCompleto($empleado->getIdBySal($data['dett_salario'])))}}</td>
                    <td>{{$data['dett_turnos']}}</td>
                    <td>{{$data['dett_extras']}}</td>
                    <td>{{$data['dett_ordinales']}}</td>
                    <td>{{$data['dett_descripcion']}}</td>
                    <td>
                    <a href="{{route('asignar-reporte.eliminar',['key'=> $key])}}"
                       class="btn-accion-tabla eliminar-registro "
                       data-toggle="tooltip"
                       title="Eliminar este registro">
                        <i class="text-danger far fa-trash-alt"></i></a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

</fieldset>

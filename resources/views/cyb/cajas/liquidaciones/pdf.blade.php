<!DOCTYPE html>
<html lang="es">
<style>
    html{
        margin-top: 80px;
        margin-bottom: 30px;
        padding: 0;
    }
    header{
        margin: 0;
        padding: 0;
        font-size: 0.7rem;

    }
    main{
        margin: 0;
        padding: 10px;

    }
    .header-pdf {
        font-size: 0.5rem;
        border: solid 1px black;
        text-align: center;
        background: #d2d4d5;

    }    .header-pdf-q {
             font-size: 0.5rem;
             border: solid 1px black;
             text-align: center;
             background: #d2d4d5;
            width: 80px;
         }

    .titulo1 {
        font-weight: 0;
    }
    .firma {
        font-weight: 0;
        font-size: 0.7rem;

    }
    .titulos{
        margin: 0;
        padding: 0;

    }
    .table {
        width: 100%;
        border-collapse: collapse;
        border: none;
    }
    .tr_item{
        border: solid 1px black;
    }
    td {
        font-size: 75%;
        border: none;
    }

    th {
        color: black;
    }




    .header {
        border: solid 1px black;

    }

    .tr-final{
        border: solid 1px black;

    }
    .name-item{
        width: 180px;
    }

    .item{
        font-size: 0.6rem;
        text-align: right;
    }

</style>
<body>
@inject('empleado','App\Models\Planilla\Empleado')

<header>
    <h4 style="text-align: center" class="titulos">{{$empresa->emp_nombre}}</h4>
    <h4 style="text-align: center" class="titulos">LIQUIDACION DE CAJA CHICA: {{$caja->cch_nombre}}</h4>
</header>
<main>
    <table class="table">
        <thead class='header'>
        <tr class="header">
            <th scope="col" style="width: 20px" class="header-pdf">#</th>
            <th scope="col"  style="width: 70px"class="header-pdf">Factura</th>
            <th scope="col"  style="width: 80px"class="header-pdf">Fecha</th>
            <th scope="col" style="width: 350px" class="header-pdf">Proveedor</th>
            <th scope="col" class="header-pdf-q">Monto</th>
            <th scope="col" class="header-pdf">Descripcion</th>
            <th scope="col" style="width: 50px" class="header-pdf">Estado</th>
        </tr>
        </thead>
        <tbody>

        @foreach($detalles as $detalle)
            <tr>
                <td class="tr_item item">{{$numeral=$numeral+1}}</td>
                <td class="tr_item item">{{$detalle['dlcc_numerodoc']}}</td>
                <td class="tr_item item">{{$detalle['dlcc_fecha']}}</td>
                <td class="tr_item item" style="text-align: left;">{{$detalle->ProveedorDetalle->Persona->per_nombre}}</td>
                <td class="tr_item item"><div style="width: 80px"><div style="text-align: left; display: inline-block; width: 30px"><span>Q</span> </div><div style="text-align: right; display: inline-block; width: 50px"><span>{{Str::money($detalle['dlcc_monto'], '')}}</span></div></div></td>
                <td class="tr_item item" style="text-align: left;">{{$detalle['dlcc_descripcion']}}</td>
                <td class="tr_item item">@if($detalle['dlcc_status']=='P')
                Pendiente
                @elseif($detalle['dlcc_status']=='L')
                Liquidado
                    @else
                Rechazado
                @endif</td>
            </tr>
        @endforeach
        <tr>
            <td class="tr_item item" style="text-align: center;">- -</td>
            <td class="tr_item item" style="text-align: center;">- - - -</td>
            <td class="tr_item item" style="text-align: center;">- - - - -</td>
            <td style="text-align: center;">-----------------------------ÚLTIMA LÍNEA-----------------------------</td>
            <td class="tr_item item" style="text-align: center;">- - - - -</td>
            <td class="tr_item item" style="text-align: center;">- - - - - - -</td>
            <td class="tr_item item" style="text-align: center;">- - - - - - -</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td class="item tr-final">SUMA DE TODOS LOS DETALLES</td>
            <td class="tr_item item"><div style="width: 80px"><div style="text-align: left; display: inline-block; width: 30px"><span>Q</span> </div><div style="text-align: right; display: inline-block; width: 50px"><span>{{Str::money($anterior,'')}}</span></div></div></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td class="item tr-final">TOTAL A REINTEGRAR</td>
            <td class="tr_item item"><div style="width: 80px"><div style="text-align: left; display: inline-block; width: 30px"><span>Q</span> </div><div style="text-align: right; display: inline-block; width: 50px"><span>{{Str::money($total, '')}}</span></div></div></td>
            <td></td>
            <td></td>
        </tr>
        </tbody>
    </table>
    <br>
    <div>
        <h4 class="firma"> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<span>________________________________</span></h4>
        <h4 class="firma"> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<span>{{$empleado->getNombreCompleto( $caja->Responsable->empl_id) }} </span></h4>

    </div>
</main>

<script type="text/php">
        if (isset($pdf)) {
                $x = 675;
                $y = 570;
                $text = "Página {PAGE_NUM} de {PAGE_COUNT}";
                $font = null;
                $size = 10;
                $color = array(0,0,0);
                $word_space = 0.0;  //  default
                $char_space = 0.0;  //  default
                $angle = 0.0;   //  default
                $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
            }


</script>
</body>

</html>

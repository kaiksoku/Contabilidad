<!DOCTYPE html>
<html lang="es">

<head>
    <style>
        .table {
            width: 100%;
            border-collapse: collapse;
            border: none;
        }

        td {
            font-size: 75%;
            border: none;
            text-align: center;
        }

        th {
            background-color: #292428;
            color: white;
            border: none;
            font-size: 0.9rem;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .text-right{
            text-align: right;
        }
    </style>
</head>

<body>
<header>
    <h3><center>Detalles de Conciliación de la Cuenta Bancaria: {{$cuentadebanco->CuentadeBanco->ctab_numero}}</center></h3>
</header>
<main>
    <table class="table">
        <thead class='thead-dark'>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Fecha</th>
            <th scope="col">Documento</th>
            <th scope="col">Tipo</th>
            <th scope="col">Descripción</th>
            <th scope="col">Monto</th>
            <th scope="col">Estado de Conciliación</th>
        </tr>
        </thead>
        <tbody>
        @foreach($transacciones as $transaccion)
            <tr>
                <td>{{$numeral=$numeral+1}}</td>
                <td>{{$transaccion->trab_fecha}}</td>
                <td>{{$transaccion->trab_documento}}</td>
                <td align="left">@if($transaccion->trab_tipo=='MD')
                        Movimiento de Débito
                    @elseif($transaccion->trab_tipo=='MC')
                        Movimiento de Credito
                    @elseif($transaccion->trab_tipo=='CH')
                        Cheque
                    @elseif($transaccion->trab_tipo=='TR')
                        Transferencia a Relacionados
                    @elseif($transaccion->trab_tipo=='TI')
                        Transferencia Interna
                    @elseif($transaccion->trab_tipo=='TA')
                        Transferencia a Terceros
                    @elseif($transaccion->trab_tipo=='CA')
                        Cheque a Terceros
                    @elseif($transaccion->trab_tipo=='TD')
                        Transferencia de Terceros
                    @elseif($transaccion->trab_tipo=='DE')
                        Depósitos de Terceros
                    @elseif($transaccion->trab_tipo=='DR')
                        Transferencia De Relacionados
                    @endif
                </td>
                <td>{{$transaccion->trab_descripcion}}</td>
                <td>{{str_replace('-', '',Str::money($transaccion->trab_monto, "Q "))}}</td>
                <td>@if($transaccion->trab_conciliado==0)
                        No conciliado
                    @else
                        Conciliado
                    @endif</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</main>
<script type="text/php">
        if (isset($pdf)) {
                $x = 700;
                $y = 595;
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

<!DOCTYPE html>
<html lang="es">

<head>
    <style>
        .imagen1 {
            text-align: center;
            font-family: "arial"
        }


        #heading {
            color: white;
            font-family: "arial"
        }


        .tama {

            color: #fff;
            background-color: rgb(15, 121, 241);
            font-size: x-small;
            font-family: "arial"

        }

        .cuerpo {
            border: black 1px solid;


        }

        .linea {
            font-size: x-small;
            font-family: "arial"

        }

        .horizontal {
            border-bottom: rgb(15, 121, 241) 1px solid;
            font-size: x-small;
            font-family: "arial"

        }

        .verticalLine {
            border-left: rgb(15, 121, 241) 1px solid;
            font-size: x-small;
            font-family: "arial"
        }

        .letter {

            border: 1px solid white;

            font-size: x-small;
            font-family: "arial"
        }

        .letter2 {

            border: 1px solid white;
            font-size: x-small;
            font-family: "arial"
        }

        .letter1 {

            font-size: x-small;
            font-family: "arial"
        }

        .total {
            width: 25%;
            text-align: right;
            vertical-align: top;
            border: 1px solid white;
            font-family: "arial";
            font-size: x-small;
        }

        .centrar {
            text-align: left;
        }

        .borde {

            text-align: right;
            vertical-align: top;
            border: solid #000;
            font-family: "arial";
            font-size: x-small;
        }

        .styled-table {
            border-collapse: collapse;
            margin: 25px 0;
            font-size: 0.9em;
            font-family: sans-serif;
            min-width: 400px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
        }

        .styled-table thead tr {
            background-color: #009879;
            color: #ffffff;
            text-align: left;
        }

        .styled-table th,
        .styled-table td {
            padding: 12px 15px;
        }

        .styled-table tbody tr {
            border-bottom: 1px solid #dddddd;
        }

        .styled-table tbody tr:nth-of-type(even) {
            background-color: #f3f3f3;
        }

        .styled-table tbody tr:last-of-type {
            border-bottom: 2px solid #009879;
        }



    </style>
</head>

<body>

    <main>
        <table class="letter2 ">
            <tr class="letter2 ">
                <th height="15">
                <td>

                    @if (file_exists(public_path() . '/assets/img/logos/' . $data->Empresa->emp_id . '.jpg'))
                        <img src="{{ public_path() . '/assets/img/logos/' . $data->Empresa->emp_id . '.jpg' }}" alt=""
                            class="mx-auto d-block" height="100px">
                    @elseif (file_exists(public_path()."/assets/img/logos/".$data->Empresa->emp_id.".png"))
                        <img src="{{ public_path() . '/assets/img/logos/' . $data->Empresa->emp_id . '.png' }}" alt=""
                            class="mx-auto d-block" height="100px">
                    @else
                        <p>{{ $data->Empresa->emp_siglas }}</p>
                    @endif
                </td>
                </th>
        </table>



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

            @foreach ($data->DetallePoliza as $linea)


            <tr>
                <td style="text-align: left" width="180%">
                    @if ($linea->dpol_ctaContable)

                    @endif
                </td>

            </tr>




        @endforeach



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
    </main>




</body>







</html>

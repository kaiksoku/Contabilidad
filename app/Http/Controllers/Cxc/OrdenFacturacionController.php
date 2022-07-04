<?php

namespace App\Http\Controllers\Cxc;

use Exception;
use Carbon\Carbon;
use App\Models\Cxc\Clave;

use App\Models\Cxc\Serie;
use App\Models\Admin\Moneda;
use App\Models\Admin\CorrelativoInterno;
use Illuminate\Http\Request;
use App\Models\Admin\Persona;
use App\Models\Cxc\Productos;
use App\Models\Cxc\Facturacion;
use App\Models\Cxc\DetalleOrdenF;
use App\Models\Cxc\DetalleVentas;
use App\Models\Parametros\Empresa;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Cxc\OrdenFacturacion;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;
use App\Http\Requests\Cxc\ValidacionFacturacion;
use App\Http\Requests\Cxc\ValidacionOrdenFacturacion;

class OrdenFacturacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->hasRole('Super Administrador')) {
            $datas = OrdenFacturacion::orderBy('ordf_id')->get();
        } else {
            $emp = auth()->user()->Empresas->pluck('emp_id');
            $ter = auth()->user()->Terminales->pluck('ter_id');
            $datas = OrdenFacturacion::whereIn('ordf_empresa', $emp)->whereIn('ordf_terminal', $ter)->orderBy('ordf_id')->get();
        }

        return view('cxc.ventas.ordenfacturacion.index', compact('datas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('cxc.ventas.ordenfacturacion.crear');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ValidacionOrdenFacturacion $request)
    {
        //dd($request->all());
        try {
            DB::transaction(function () use ($request) {
                $fecha = Carbon::createFromFormat('d/m/Y', $request->ordf_eta);
                $request['ordf_eta'] = Carbon::parse($fecha)->format('Y-m-d H:i:s');
                $Corr = getCorrelativo($fecha, $request->ordf_empresa, $request->ordf_terminal, "F");
                $request->merge(['ordf_correlativoInt' => $Corr->corr_id]);
                $request->merge(['correlativoTexto' => $Corr->corr_correlativo]);
                $orden = OrdenFacturacion::create($request->all());
               /* if (is_null($request->ordf_buque))
                    $orden->ordf_viaje = null;
                else
                    $orden->ordf_eta = null;
                $orden->save();*/
                foreach ($request->dof_producto as $i => $item) {
                    $detalle = new DetalleOrdenF();
                    $detalle->dof_ordenF = $orden->ordf_id;
                    $detalle->dof_producto = $item;
                    $detalle->dof_tarifa = $request->dof_tarifa[$i];
                    $detalle->dof_cantidad = $request->dof_cantidad[$i];
                    $detalle->save();
                }
            });
        } catch (Exception $e) {
            return redirect('cxc/ventas/ordenfacturacion')->withErrors(['catch2', $e->errorInfo]);
        }
        return redirect('cxc/ventas/ordenfacturacion')->with('mensajeHTML', "Orden de facturación creada con el correlativo")->with('correlativo', $request->correlativoTexto);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function show(Request $request, $id)
    {
        $data = OrdenFacturacion::findOrFail($id);
        $emp=$data->ordf_empresa;

        $datas = Empresa::join('certificador','certificador.cer_id', '=', 'empresa.emp_fel')->
        where('emp_id', '=', $emp)->get()->pluck('cer_nombre');


        return view('cxc.ventas.ordenfacturacion.editar', compact('data','datas'));
    }




    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {

        //dd($request->all());

        $data = OrdenFacturacion::findOrFail($id);
        return view('cxc.ventas.ordenfacturacion.editar', compact('data'));
    }

    public function edit1($id)
    {
        //dd($request->all());

        $data = OrdenFacturacion::findOrFail($id);
        $data1 = DetalleOrdenF::findOrFail($id);
        return view('cxc.ventas.ordenfacturacion.editar1', compact('data', 'data1'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


     public function CrearFacura (Request $request, $id){
 
            $emp=$request->ven_empresa;

            $data = Empresa::join('certificador','certificador.cer_id', '=', 'empresa.emp_fel')->
            where('emp_id', '=', $emp)->get()->pluck('cer_nombre');



            switch($data[0]){
                case'PAPEL':
                    try {

                        // dd($request->all());
                            DB::transaction(function () use ($request) {
                                $fecha = Carbon::createFromFormat('d/m/Y', $request->ven_fecha);
                                $request['ven_fecha'] = Carbon::parse($fecha)->format('Y-m-d H:i:s');
                                $request['ven_fechaCert'] = Carbon::parse($fecha)->format('Y-m-d H:i:s');
                                $Corr = getCorrelativo($fecha, $request->ven_empresa, $request->ven_terminal, "Q");

                                $request->merge(['ven_correlativoInt' => $Corr->corr_id]);
                                $request->merge(['correlativoTexto' => $Corr->corr_correlativo]);


                                $orden = Facturacion::create($request->all());
                                $orden->ven_iiud=$request->ordf_iiud;
                                 $orden->ven_serie=$request->ordf_serie;
                                 $orden->ven_numDoc=$request->ordf_numDoc;
                                 $orden->ven_enlacefactura=$request->ordf_enlacefactura;
                                $orden->ven_tipo = 'F';
                                $orden->save();
                                // if (is_null($request->ven_iiud))
                                // $orden->ven_fechaCert = null;

                                foreach ($request->detv_producto as $i => $item) {
                                    $detalle = new DetalleVentas();
                                    $detalle->detv_venta = $orden->ven_id;
                                    $detalle->detv_producto = $item;
                                    $detalle->detv_precioU = $request->detv_precioU[$i] * 1.12;
                                    $detalle->detv_cantidad = $request->detv_cantidad[$i];
                                    if (is_null($detalle->detv_descuento))
                                        $detalle->detv_descuento = '0';
                                    $detalle->save();
                                }

                                $factura =  OrdenFacturacion::where('ordf_id', $request->ven_id)->get();
                                $factura = $factura[0];
                                $factura->ordf_factura = $request->ordf_numDoc;
                                $factura->save();
    


                            });
                        } catch (Exception $e) {
                            return redirect('cxc/ventas/facturacion')->withErrors(['catch2', $e->getMessage()]);
                        }
                        return redirect('cxc/ventas/facturacion')->with('mensajeHTML', "Factura creada con el correlativo")->with('correlativo', $request->correlativoTexto);
                    break;
                case'SAT':

                    try {

                    // dd($request->all());
                        DB::transaction(function () use ($request) {


                            $fecha = Carbon::createFromFormat('d/m/Y', $request->ven_fecha);
                            $request['ven_fecha'] = Carbon::parse($fecha)->format('Y-m-d H:i:s');
                            $request['ven_fechaCert'] = Carbon::parse($fecha)->format('Y-m-d H:i:s');
                            $Corr = getCorrelativo($fecha, $request->ven_empresa, $request->ven_terminal, "Q");

                            $request->merge(['ven_correlativoInt' => $Corr->corr_id]);
                            $request->merge(['correlativoTexto' => $Corr->corr_correlativo]);


                            $orden = Facturacion::create($request->all());
                            $orden->ven_iiud=$request->ordf_iiud;
                            $orden->ven_serie=$request->ordf_serie;
                            $orden->ven_numDoc=$request->ordf_numDoc;
                            $orden->ven_enlacefactura=$request->ordf_enlacefactura;
                            $orden->ven_tipo = 'F';
                            $orden->save();
                            // if (is_null($request->ven_iiud))
                            // $orden->ven_fechaCert = null;

                            foreach ($request->detv_producto as $i => $item) {
                                $detalle = new DetalleVentas();
                                $detalle->detv_venta = $orden->ven_id;
                                $detalle->detv_producto = $item;
                                $detalle->detv_precioU = $request->detv_precioU[$i] * 1.12;
                                $detalle->detv_cantidad = $request->detv_cantidad[$i];
                                if (is_null($detalle->detv_descuento))
                                    $detalle->detv_descuento = '0';
                                $detalle->save();
                            }

                            $factura =  OrdenFacturacion::where('ordf_id', $request->ven_id)->get();
                            $factura = $factura[0];
                            $factura->ordf_factura = $request->ordf_numDoc;
                            $factura->save();



                        });
                    } catch (Exception $e) {
                        return redirect('cxc/ventas/facturacion')->withErrors(['catch2', $e->getMessage()]);
                    }
                    return redirect('cxc/ventas/facturacion')->with('mensajeHTML', "Factura creada con el correlativo")->with('correlativo', $request->correlativoTexto);
                    break;

                    case'INFILE':

                        try {
                            DB::transaction(function () use ($request) {

                                $fecha = Carbon::createFromFormat('d/m/Y', $request->ven_fecha);
                                $Corr = getCorrelativo($fecha, $request->ven_empresa, $request->ven_terminal, "Q1");
                                $request->merge(['ven_correlativoInt' => $Corr->corr_id]);
                                $request->merge(['correlativoTexto' => $Corr->corr_correlativo]);
                                $xml = Self::crearXML($request);
                                $url = 'https://certificador.feel.com.gt/fel/procesounificado/transaccion/v2/xml';
                                $request['ven_fecha'] = Carbon::parse($fecha)->format('Y-m-d H:i:s');
                                $request['ven_fechaCert'] = Carbon::parse($fecha)->format('Y-m-d H:i:s');
                                $Clave=Clave::where('cla_empresa', '=', $request->ven_empresa)->get();


                                //Undefined offset: 0

                                $UsuarioFirma=$Clave[0]->cla_UsuarioFirma;
                                $LlaveFirma=$Clave[0]->cla_LlaveFirma;
                                $UsuarioApi=$Clave[0]->cla_UsuarioApi;
                                $LlaveApi=$Clave[0]->cla_LlaveApi;

                                $headers = [
                                    'UsuarioFirma' => $UsuarioFirma,
                                    'LlaveFirma' => $LlaveFirma,
                                    'UsuarioApi' => $UsuarioApi,
                                    'LlaveApi' => $LlaveApi,
                                    'identificador' => $Corr->corr_correlativo,
                                    ];
                                $response = Http::withHeaders($headers)->send('POST', $url, ['body' => $xml]);     

                            
                                 if ($response->json()['resultado'] == 0) {
                                $error = $response->json()['descripcion_errores'];
                                throw new Exception($error[0]['mensaje_error']);
                                    }

                                $orden = Facturacion::create($request->all());

                                $orden->ven_fechaCert = $response->json()['fecha'];
                                $orden->ven_iiud = $response->json()['uuid'];
                                $orden->ven_serie = $response->json()['serie'];
                                $orden->ven_numDoc = $response->json()['numero'];

                                $orden->ven_enlacefactura = "https://report.feel.com.gt/ingfacereport/ingfacereport_documento?uuid=" . $response->json()['uuid'];
                                $orden->save();
                                $orden->ven_tipo = 'F';
                                $orden->save();
                                // if (is_null($request->ven_iiud))
                                // $orden->ven_fechaCert = null;
                                $orden->save();
                                foreach ($request->detv_producto as $i => $item) {
                                    $detalle = new DetalleVentas();
                                    $detalle->detv_venta = $orden->ven_id;
                                    $detalle->detv_producto = $item;
                                    $detalle->detv_precioU = $request->detv_precioU[$i] * 1.12;
                                    $detalle->detv_cantidad = $request->detv_cantidad[$i];
                                    if (is_null($detalle->detv_descuento))
                                        $detalle->detv_descuento = '0';
                                    $detalle->save();
                                }

                                $factura =  OrdenFacturacion::where('ordf_id', $request->ven_id)->get();
                                $factura = $factura[0];
                                $factura->ordf_factura = $orden->ven_numDoc;
                                $factura->save();


                                $urlf = $orden->ven_enlacefactura = "https://report.feel.com.gt/ingfacereport/ingfacereport_documento?uuid=" . $response->json()['uuid'];
                                $request->merge(['urlf' => $urlf]);
                            });
                        } catch (Exception $e) {
                            if($e ='Undefined offset: 0'){
                                return redirect('cxc/ventas/facturacion')->withErrors(['catch2', 'La empresa seleccionada no cuenta con credenciales de FEL']); 
                            }else{
                            return redirect('cxc/ventas/facturacion')->withErrors(['catch2', $e->getMessage()]);}
                        }
                        return redirect('cxc/ventas/facturacion/vista')->with('mensajeHTML', "Factura creada con el correlativo")->with('correlativo', $request->correlativoTexto)->with('urlf',$request->urlf);
                        break;

            }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }




    public function FacturaPDF($id)
    {


        $data = OrdenFacturacion::findOrFail($id);
        $pdf = app('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadView('cxc.ventas.ordenfacturacion.vistaordenf', compact('data'))->setPaper('letter');
        return $pdf->download('OrdenFacturacion.pdf');
    }

    public function Anular(Request $request, $id)
    {
        
        $factura =  OrdenFacturacion::where('ordf_anulada')->first();
        $factura=OrdenFacturacion::find($id)->update(['ordf_anulada' => 0]);
    

        return redirect('cxc/ventas/ordenfacturacion')->with('mensaje', 'Orden de Facturación anulada correctamente.');
    }

     

    public function update1(ValidacionOrdenFacturacion $request, $id)
    {


        try {
            DB::transaction(function () use ($request) {

                $orden = OrdenFacturacion::create($request->all());
                if (is_null($request->ordf_buque))
                    $orden->ordf_viaje = null;
                else
                    $orden->ordf_eta = null;
                $orden->save();

                foreach ($request->dof_producto as $i => $item) {
                    $detalle = new DetalleOrdenF();
                    $detalle->dof_ordenF = $orden->ordf_id;
                    $detalle->dof_producto = $item;
                    $detalle->dof_tarifa = $request->dof_tarifa[$i];
                    $detalle->dof_cantidad = $request->dof_cantidad[$i];
                    $detalle->save();
                }
            });
        } catch (Exception $e) {
            return redirect('cxc/ventas/ordenfacturacion')->withErrors(['catch2', $e->errorInfo]);
        }
        return redirect('cxc/ventas/ordenfacturacion')->with('mensaje', 'Orden de Facturación actualizada correctamente.');
    }

    static function crearXML(request $request)
    {

        $empresa = new Empresa();
        $cliente = new Persona();
        $moneda = new Moneda();
        $producto = new Productos();
        $correlativ =new CorrelativoInterno();
        $nit = $empresa->getNit($request->ven_empresa);
        $nComercial = $empresa->getNComercial($request->ven_empresa);
        $nombreEmp = $empresa->getNombre($request->ven_empresa);
        $direccionEmp = $empresa->getDireccion($request->ven_empresa);
        $municipioEmp = $empresa->getMunicipio($request->ven_empresa);
        $siglaMon = $moneda->getSigla($request->ven_moneda);
        $nitCli = $cliente->getNit($request->ven_persona);
        $emailCli = $cliente->getEmail($request->ven_persona);
        $nombreCli = $cliente->getNombreCli($request->ven_persona);
        $nombreCli = str_replace("&","&#38;",$nombreCli);
        $direccionCli = $cliente->getDireccionCli($request->ven_persona);
        $datetime = Carbon::createFromFormat('d/m/Y', $request->ven_fechaCert);
        $Atom = $datetime->toAtomString();
        $tipocambio = (($request->ven_tipoCambio));
        $tipocambio1 =  number_format($tipocambio, 5, '.', '');
        $Correlat = $correlativ->getCorr($request->ven_correlativoInt);
        $ETA =Carbon::parse($request->ordf_eta)->format('d/m/Y');
        $diseno="ESPECIAL";

        if($request->tipofactura ==1)$diseno="";

        $xml =  <<<XML
            <dte:GTDocumento xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:dte="http://www.sat.gob.gt/dte/fel/0.2.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" Version="0.1" xsi:schemaLocation="http://www.sat.gob.gt/dte/fel/0.2.0">
            <dte:SAT ClaseDocumento="dte">
            <dte:DTE ID="DatosCertificados">
            <dte:DatosEmision ID="DatosEmision">
            <dte:DatosGenerales CodigoMoneda="$siglaMon" FechaHoraEmision="$Atom" Tipo="FACT"></dte:DatosGenerales>
            <dte:Emisor AfiliacionIVA="GEN" CodigoEstablecimiento="1" NITEmisor="11400055K" NombreComercial="$nComercial" NombreEmisor="$nombreEmp">
              <dte:DireccionEmisor>
                <dte:Direccion>$direccionEmp</dte:Direccion>
                <dte:CodigoPostal>01001</dte:CodigoPostal>
                <dte:Municipio>GUATEMALA</dte:Municipio>
                <dte:Departamento>GUATEMALA</dte:Departamento>
                <dte:Pais>GT</dte:Pais>
              </dte:DireccionEmisor>
            </dte:Emisor>
            <dte:Receptor CorreoReceptor="$emailCli" IDReceptor="$nitCli" NombreReceptor="$nombreCli">
              <dte:DireccionReceptor>
                <dte:Direccion>$direccionCli</dte:Direccion>
                <dte:CodigoPostal>01001</dte:CodigoPostal>
                <dte:Municipio>GUATEMALA</dte:Municipio>
                <dte:Departamento>GUATEMALA</dte:Departamento>
                <dte:Pais>GT</dte:Pais>
              </dte:DireccionReceptor>
            </dte:Receptor>
            <dte:Frases>
              <dte:Frase CodigoEscenario="1" TipoFrase="1"></dte:Frase>
            </dte:Frases>
            <dte:Items>
            XML;

        $total = 0;
        $totiva = 0;
        foreach ($request->detv_producto as $i => $item) {

            $pU = round($request->detv_precioU[$i], 5);
            $pU2 = round($pU * 0.12, 5);
            $pU3 = $pU + $pU2;
            $pU4 = round($pU3 * $tipocambio, 5);

            $cantidad = round($request->detv_cantidad[$i], 5);
            $totalq = round($pU4 * $cantidad, 5);
            $subtotal=round($pU * $cantidad, 5);
            $ivasubtotal=round($subtotal *0.12, 5);
            $totalUSD=round($subtotal+ $ivasubtotal, 5);
            $iva = round($totalq / 1.12, 5);
            $iva2 = $totalq - $iva;
            $totaldolar=(($pU*$cantidad)*0.12);
            $total += $totalq;
            $totiva += $iva2;
            

            $prod = $producto->getProducto($request->detv_producto[$i]);

            if($request->tipofactura ==0){
            $xml .= <<<XML
            <dte:Item BienOServicio="S" NumeroLinea="1">
            <dte:Cantidad>$cantidad</dte:Cantidad>
            <dte:UnidadMedida>UND</dte:UnidadMedida>
            <dte:Descripcion>$prod|$pU|$subtotal|$ivasubtotal|$totalUSD</dte:Descripcion>
            <dte:PrecioUnitario>$pU4</dte:PrecioUnitario>
            <dte:Precio>$totalq</dte:Precio>
            <dte:Descuento>0.00</dte:Descuento>
            <dte:Impuestos>
              <dte:Impuesto>
                <dte:NombreCorto>IVA</dte:NombreCorto>
                <dte:CodigoUnidadGravable>1</dte:CodigoUnidadGravable>
                <dte:MontoGravable>$iva</dte:MontoGravable>
                <dte:MontoImpuesto>$iva2</dte:MontoImpuesto>
              </dte:Impuesto>
            </dte:Impuestos>
            <dte:Total>$totalq</dte:Total>
          </dte:Item>
          XML;

            }else{

                $xml .= <<<XML
                <dte:Item BienOServicio="S" NumeroLinea="1">
                <dte:Cantidad>$cantidad</dte:Cantidad>
                <dte:UnidadMedida>UND</dte:UnidadMedida>
                <dte:Descripcion>$prod||||</dte:Descripcion>
                <dte:PrecioUnitario>$pU4</dte:PrecioUnitario>
                <dte:Precio>$totalq</dte:Precio>
                <dte:Descuento>0.00</dte:Descuento>
                <dte:Impuestos>
                  <dte:Impuesto>
                    <dte:NombreCorto>IVA</dte:NombreCorto>
                    <dte:CodigoUnidadGravable>1</dte:CodigoUnidadGravable>
                    <dte:MontoGravable>$iva</dte:MontoGravable>
                    <dte:MontoImpuesto>$iva2</dte:MontoImpuesto>
                  </dte:Impuesto>
                </dte:Impuestos>
                <dte:Total>$totalq</dte:Total>
              </dte:Item>
              XML;


            }
        }

        $xml .= <<<XML



        </dte:Items>
        <dte:Totales>
          <dte:TotalImpuestos>
            <dte:TotalImpuesto NombreCorto="IVA" TotalMontoImpuesto="$totiva"></dte:TotalImpuesto>
          </dte:TotalImpuestos>
          <dte:GranTotal>$total</dte:GranTotal>
        </dte:Totales>
      </dte:DatosEmision>
    </dte:DTE>
    <dte:Adenda>
      <Codigo_cliente>C01</Codigo_cliente>
      <Observaciones>Prueba</Observaciones>
      <Tasa-Cambio>$tipocambio</Tasa-Cambio>

      <Fact-Comen1>$request->ven_descripcion
        Buque: $request->ordf_buque
        Viaje: $request->ordf_viaje
        Eta:   $ETA
        $request->ven_referencia
        </Fact-Comen1>


       <Fact-Comen>
      $request->ven_contenedores</Fact-Comen>

    <Referencia-Interna>$Correlat</Referencia-Interna>
    <Diseno> $diseno</Diseno>

    </dte:Adenda>
     </dte:SAT>
    </dte:GTDocumento>

    XML;
        return $xml;
    }
}

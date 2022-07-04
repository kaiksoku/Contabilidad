<?php

namespace App\Http\Controllers\Cxc;

use Exception;
use Carbon\Carbon;
use App\Models\Cxc\Clave;
use App\Models\Cxc\Abonos;
use App\Models\Admin\Moneda;
use App\Models\Cxc\Ncredito;
use Illuminate\Http\Request;
use App\Models\Admin\Persona;
use App\Models\Cxc\Facturacion;
use App\Models\Cxc\DetalleVentas;
use App\Models\Parametros\Empresa;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;
use App\Http\Requests\Cxc\ValidacionFacturacion;


class NcreditoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

      if (auth()->user()->hasRole('Super Administrador')) {
        $emp =Empresa::pluck('emp_id');
        $ter = auth()->user()->Terminales->pluck('ter_id');
        $datas = Facturacion::join('empresa','empresa.emp_id', '=', 'ventas.ven_empresa')->join('certificador','certificador.cer_id', '=', 'empresa.emp_fel')->where('ven_tipo', 'NCRE')->whereIn('ven_empresa', $emp)->whereIn('ven_terminal', $ter)->orderBy('ven_id')->get();

      } else {
        $emp = auth()->user()->Empresas->pluck('emp_id');
        $ter = auth()->user()->Terminales->pluck('ter_id');

        //DB::enableQueryLog();
        $datas = Facturacion::join('empresa','empresa.emp_id', '=', 'ventas.ven_empresa')->join('certificador','certificador.cer_id', '=', 'empresa.emp_fel')->where('ven_tipo', 'NCRE')->whereIn('ven_empresa', $emp)->whereIn('ven_terminal', $ter)->orderBy('ven_id')->get();
        //dd(DB::getQueryLog());
    }
        return view('cxc.ventas.documentos.ncredito.index', compact('datas'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('cxc.ventas.documentos.ncredito.crear');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeVerificar(ValidacionFacturacion $request)
    {
        $emp=$request->ven_empresa;
        $data = Empresa::join('certificador','certificador.cer_id', '=', 'empresa.emp_fel')->
        where('emp_id', '=', $emp)->get()->pluck('cer_nombre');
        //dd($data);


        switch($data[0]){
            case'PAPEL':
                try {

                     DB::transaction(function () use ($request) {
                        $fecha = Carbon::createFromFormat('d/m/Y', $request->ven_fecha);
                        $request['ven_fecha'] = Carbon::parse($fecha)->format('Y-m-d H:i:s');
                        $request['ven_fechaCert'] = Carbon::parse($fecha)->format('Y-m-d H:i:s');
                        $Corr = getCorrelativo($fecha, $request->ven_empresa, $request-> ven_terminal, "Q");

                                $request->merge(['ven_correlativoInt' => $Corr->corr_id]);
                        $request->merge(['correlativoTexto' => $Corr->corr_correlativo]);


                        $orden = Facturacion::create($request->all());
                        $orden->ven_tipo = 'NCRE';
                        $orden->save();

                          $detalle = new abonos();
                          $detalle->abf_venta = $orden->ven_id;
                          $detalle->abf_monto =$orden->ven_total;
                          $detalle->abf_tipoAbono='1';
                          $detalle->abf_fecha = $orden->ven_fecha;
                          $detalle->save();

                    });


                } catch (Exception $e) {
                    return redirect('cxc/ventas/documentos/ncredito')->withErrors(['catch2', $e->getMessage()]);
                }
                return redirect('cxc/ventas/documentos/ncredito')->with('mensajeHTML', "Nota de Crédito creada con el correlativo")->with('correlativo', $request->correlativoTexto);
             break;

            case'SAT':

                try {

                  DB::transaction(function () use ($request) {

                      $fecha = Carbon::createFromFormat('d/m/Y', $request->ven_fecha);
                      $request['ven_fecha'] = Carbon::parse($fecha)->format('Y-m-d H:i:s');
                      $request['ven_fechaCert'] = Carbon::parse($fecha)->format('Y-m-d H:i:s');
                      $Corr = getCorrelativo($fecha, $request->ven_empresa, $request-> ven_terminal, "Q");


                      $request->merge(['ven_correlativoInt' => $Corr->corr_id]);
                      $request->merge(['correlativoTexto' => $Corr->corr_correlativo]);


                      $orden = Facturacion::create($request->all());
                      $orden->ven_tipo = 'NCRE';
                      $orden->save();

                        $detalle = new abonos();
                        $detalle->abf_venta = $orden->ven_id;
                        $detalle->abf_monto =$orden->ven_total;
                        $detalle->abf_tipoAbono='1';
                        $detalle->abf_fecha = $orden->ven_fecha;
                        $detalle->save();

                  });


              } catch (Exception $e) {
                  return redirect('cxc/ventas/documentos/ncredito')->withErrors(['catch2', $e->getMessage()]);
              }
              return redirect('cxc/ventas/documentos/ncredito')->with('mensajeHTML', "Nota de Crédito creada con el correlativo")->with('correlativo', $request->correlativoTexto);
                break;

                case'INFILE':
                  try {


                    DB::transaction(function () use ($request) {
                      //dd($request->all());
                        $xml = Self::crearXML($request);
                        $url = 'https://certificador.feel.com.gt/fel/procesounificado/transaccion/v2/xml';
                        $fecha = Carbon::createFromFormat('d/m/Y', $request->ven_fecha);
                        $request['ven_fecha'] = Carbon::parse($fecha)->format('Y-m-d H:i:s');
                        $request['ven_fechaCert'] = Carbon::parse($fecha)->format('Y-m-d H:i:s');
                        $Corr = getCorrelativo($fecha, $request->ven_empresa, $request-> ven_terminal, "Q");
                        $Clave=Clave::where('cla_empresa', '=', $request->ven_empresa)->get();
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
                        $response = Http::withHeaders($headers)->send('POST',$url,['body'=>$xml]);


                        //dd($response->json());

                        if ($response->json()['resultado'] == 0) {
                          $error = $response->json()['descripcion_errores'];
                         //dd($error[0]['mensaje_error']);
                          throw new Exception($error[0]['mensaje_error']);
                      }

                        $request->merge(['ven_correlativoInt' => $Corr->corr_id]);
                        $request->merge(['correlativoTexto' => $Corr->corr_correlativo]);


                        $orden = Facturacion::create($request->all());
                        $orden->ven_fechaCert=$response->json()['fecha'];
                        $orden->ven_iiud=$response->json()['uuid'];
                        $orden->ven_serie= $response->json()['serie'];
                        $orden->ven_numDoc=$response->json()['numero'];
                        $orden->ven_enlacefactura="https://report.feel.com.gt/ingfacereport/ingfacereport_documento?uuid=".$response->json()['uuid'];
                        $orden->ven_tipo = 'NCRE';
                        $orden->save();

                          $detalle = new abonos();
                          $detalle->abf_venta = $orden->ven_id;
                          $detalle->abf_monto =$orden->ven_total;
                          $detalle->abf_tipoAbono='1';
                          $detalle->abf_fecha = $orden->ven_fecha;
                          $detalle->save();

                        $urlf = $orden->ven_enlacefactura = "https://report.feel.com.gt/ingfacereport/ingfacereport_documento?uuid=" . $response->json()['uuid'];
                        $request->merge(['urlf' => $urlf]);




                    });


                } catch (Exception $e) {
                    return redirect('cxc/ventas/documentos/ncredito')->withErrors(['catch2', $e->getMessage()]);
                }
                return redirect('cxc/ventas/documentos/ncredito/vista')->with('mensajeHTML', "Nota de Crédito creada con el correlativo")->with('correlativo', $request->correlativoTexto)->with('urlf',$request->urlf);

               break;

        }
      }



    public function store(Request $request)
    {

         try {


            DB::transaction(function () use ($request) {
              //dd($request->all());
                $xml = Self::crearXML($request);
                $url = 'https://certificador.feel.com.gt/fel/procesounificado/transaccion/v2/xml';
                $fecha = Carbon::createFromFormat('d/m/Y', $request->ven_fecha);
                $request['ven_fecha'] = Carbon::parse($fecha)->format('Y-m-d H:i:s');
                $request['ven_fechaCert'] = Carbon::parse($fecha)->format('Y-m-d H:i:s');
                $Corr = getCorrelativo($fecha, $request->ven_empresa, $request-> ven_terminal, "Q");
                $headers = [
                    'UsuarioFirma' => Config::get('credenciales.1.UsuarioFirma'),
                    'LlaveFirma' => Config::get('credenciales.1.LlaveFirma'),
                    'UsuarioApi' => Config::get('credenciales.1.UsuarioApi'),
                    'LlaveApi' => Config::get('credenciales.1.LlaveApi'),
                    'identificador' => $Corr->corr_correlativo,
                ];
                $response = Http::withHeaders($headers)->send('POST',$url,['body'=>$xml]);


                //dd($response->json());

                if ($response->json()['resultado'] == 0) {
                  $error = $response->json()['descripcion_errores'];
                 //dd($error[0]['mensaje_error']);
                  throw new Exception($error[0]['mensaje_error']);
              }

                $request->merge(['ven_correlativoInt' => $Corr->corr_id]);
                $request->merge(['correlativoTexto' => $Corr->corr_correlativo]);


                $orden = Facturacion::create($request->all());
                $orden->ven_fechaCert=$response->json()['fecha'];
                $orden->ven_iiud=$response->json()['uuid'];
                $orden->ven_serie= $response->json()['serie'];
                $orden->ven_numDoc=$response->json()['numero'];
                $orden->ven_enlacefactura="https://report.feel.com.gt/ingfacereport/ingfacereport_documento?uuid=".$response->json()['uuid'];
                $orden->ven_tipo = 'NCRE';
                $orden->save();

                  $detalle = new abonos();
                  $detalle->abf_venta = $orden->ven_id;
                  $detalle->abf_monto =$orden->ven_total;
                  $detalle->abf_tipoAbono='1';
                  $detalle->abf_fecha = $orden->ven_fecha;
                  $detalle->save();

                $urlf = $orden->ven_enlacefactura = "https://report.feel.com.gt/ingfacereport/ingfacereport_documento?uuid=" . $response->json()['uuid'];
                $request->merge(['urlf' => $urlf]);




            });


        } catch (Exception $e) {
            return redirect('cxc/ventas/documentos/ncredito')->withErrors(['catch2', $e->getMessage()]);
        }
        return redirect('cxc/ventas/documentos/ncredito/vista')->with('mensajeHTML', "Nota de Crédito creada con el correlativo")->with('correlativo', $request->correlativoTexto)->with('urlf',$request->urlf);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Facturacion::findOrFail($id);
        return view('cxc.ventas.documentos.ncredito.mostrar', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Ncredito::findOrFail($id);
        return view('cxc.ventas.documentos.ncredito.editar',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function Vista()
    {
        return view('cxc.ventas.documentos.ncredito.vista');
    }


    static function crearXML(request $request)
    {

        $empresa = new Empresa();
        $cliente = new Persona();
        $moneda = new Moneda();
        $nit = $empresa->getNit($request->ven_empresa);
        $siglaEmp = $empresa->getSigla($request->ven_empresa);
        $nombreEmp = $empresa->getNombre($request->ven_empresa);
        $direccionEmp = $empresa->getDireccion($request->ven_empresa);
        $municipioEmp = $empresa->getMunicipio($request->ven_empresa);
        $siglaMon = $moneda->getSigla($request->ven_moneda);
        $nitCli = $cliente->getNit($request->ven_persona);
        $emailCli = $cliente->getEmail($request->ven_persona);
        $nombreCli = $cliente->getNombreCli($request->ven_persona);
        $direccionCli = $cliente->getDireccionCli($request->ven_persona);
        $datetime = Carbon::createFromFormat('d/m/Y', $request->ven_fecha);
        $Atom = $datetime->toAtomString();



        $xml =  <<<XML
        <dte:GTDocumento xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:dte="http://www.sat.gob.gt/dte/fel/0.2.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" Version="0.1" xsi:schemaLocation="http://www.sat.gob.gt/dte/fel/0.2.0">
        <dte:SAT ClaseDocumento="dte">
        <dte:DTE ID="DatosCertificados">
        <dte:DatosEmision ID="DatosEmision">
        <dte:DatosGenerales CodigoMoneda="$siglaMon" FechaHoraEmision="$Atom" Tipo="NCRE"></dte:DatosGenerales>
        <dte:Emisor AfiliacionIVA="GEN" CodigoEstablecimiento="1" NITEmisor="11400055K" NombreComercial="$siglaEmp" NombreEmisor="$nombreEmp">
          <dte:DireccionEmisor>
            <dte:Direccion>CIUDAD</dte:Direccion>
            <dte:CodigoPostal>01001</dte:CodigoPostal>
            <dte:Municipio>GUATEMALA</dte:Municipio>
            <dte:Departamento>GUATEMALA</dte:Departamento>
            <dte:Pais>GT</dte:Pais>
          </dte:DireccionEmisor>
        </dte:Emisor>
        <dte:Receptor CorreoReceptor="$emailCli" IDReceptor="$nitCli" NombreReceptor="$nombreCli">
          <dte:DireccionReceptor>
            <dte:Direccion>CIUDAD</dte:Direccion>
            <dte:CodigoPostal>01001</dte:CodigoPostal>
            <dte:Municipio>GUATEMALA</dte:Municipio>
            <dte:Departamento>GUATEMALA</dte:Departamento>
            <dte:Pais>GT</dte:Pais>
          </dte:DireccionReceptor>
        </dte:Receptor>
        XML;



        $total1 = round($request->ven_total,5);//100


        $iva1 =  round(+$request->ven_total / 1.12,5);


        $siniva1 = round(((+$request->ven_total)-(+$iva1)),5) ;




        $xml .= <<<XML
         <dte:Items>
          <dte:Item BienOServicio="B" NumeroLinea="1">
            <dte:Cantidad>1.00</dte:Cantidad>
            <dte:UnidadMedida>UNI</dte:UnidadMedida>
            <dte:Descripcion>$request->ven_descripcion</dte:Descripcion>
            <dte:PrecioUnitario>$total1</dte:PrecioUnitario>
            <dte:Precio>$total1</dte:Precio>
            <dte:Descuento>0.00</dte:Descuento>
            <dte:Impuestos>
              <dte:Impuesto>
                <dte:NombreCorto>IVA</dte:NombreCorto>
                <dte:CodigoUnidadGravable>1</dte:CodigoUnidadGravable>
                <dte:MontoGravable>$iva1</dte:MontoGravable>
                <dte:MontoImpuesto>$siniva1</dte:MontoImpuesto>
              </dte:Impuesto>
            </dte:Impuestos>
            <dte:Total>$total1</dte:Total>
          </dte:Item>
        </dte:Items>
        <dte:Totales>
          <dte:TotalImpuestos>
            <dte:TotalImpuesto NombreCorto="IVA" TotalMontoImpuesto="$siniva1"></dte:TotalImpuesto>
          </dte:TotalImpuestos>
          <dte:GranTotal>$total1</dte:GranTotal>
        </dte:Totales>
        <dte:Complementos>
          <dte:Complemento IDComplemento="TEXT" NombreComplemento="TEXT" URIComplemento="TEXT">
            <cno:ReferenciasNota xmlns:cno="http://www.sat.gob.gt/face2/ComplementoReferenciaNota/0.1.0" FechaEmisionDocumentoOrigen="$request->fechacert" MotivoAjuste="DOCUMENTO DE MUESTRA" NumeroAutorizacionDocumentoOrigen="$request->uuid" NumeroDocumentoOrigen="$request->numdoc" SerieDocumentoOrigen="$request->serie" Version="0.0" xsi:schemaLocation="http://www.sat.gob.gt/face2/ComplementoReferenciaNota/0.1.0 C:\Users\User\Desktop\FEL\Esquemas\GT_Complemento_Referencia_Nota-0.1.0.xsd"></cno:ReferenciasNota>
          </dte:Complemento>
        </dte:Complementos>
            </dte:DTE>
            </dte:SAT>
            </dte:GTDocumento>

        XML;
            return $xml;
}

      public function Certificador(Request $request, $emp)
        {
          $data = Empresa::join('certificador','certificador.cer_id', '=', 'empresa.emp_fel')->
          where('emp_id', '=', $emp)->get()->pluck('cer_nombre');

        return ($data[0]);

        }

}


<?php

namespace App\Http\Controllers\cyb;

use App\Http\Controllers\Controller;
use App\Models\Admin\CorrelativoInterno;
use App\Models\cyb\Anticipos;
use App\Models\cyb\CajaChica;
use App\Models\cyb\Cheque;
use App\Models\cyb\Conciliaciones;
use App\Models\cyb\CuentasBancarias;
use App\Models\cyb\DetalleConciliacion;
use App\Models\cyb\DetalleLiquidacionCC;
use App\Models\cyb\LiquidacionCC;
use App\Models\cyb\Transacciones;
use App\Models\Parametros\Empresa;
use App\Models\Planilla\Empleado;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use NumberFormatter;

class PDFController extends Controller
{
    public function PDFCB($dato=null){
        if ($dato) {
            $empresa=Empresa::findOrFail($dato)->emp_siglas;
            $cuentasbancariass = CuentasBancarias::where('ctab_empresa', $dato)->get();
        } else {
            $empresa= 'Todas las Empresas';
            $cuentasbancariass = CuentasBancarias::orderby('ctab_empresa');
            if (auth()->user()->hasRole('Super Administrador')) {
                $cuentasbancariass = $cuentasbancariass->get();
            } else {
                $emp = auth()->user()->Empresas->pluck('emp_id');
                $cuentasbancariass = $cuentasbancariass->whereIn('ctab_empresa', $emp)->get();
            }
        };
        $numeral=0;
        $pdf = \PDF::loadview('cyb.bancos.cuentasbancarias.prueba', compact('cuentasbancariass', 'empresa', 'numeral'));
        return $pdf->download('Cuentas Bancarias.pdf');
    }

    public function PDFCC(){
        $cajachicas = CajaChica::all();
        $empleado = Empleado::all();
        $pdf = \PDF::loadview('cyb.cajas.responsables.pdf', compact('cajachicas', 'empleado'));
        return $pdf->download('Cajas Chicas.pdf');
    }



    public function chequePDF($tipo,$id){
        $formatterES = new NumberFormatter("es", NumberFormatter::SPELLOUT);
        $cheque = Cheque::find($id);
        $fecha = Carbon::parse($cheque->che_fecha);
        $cuentaid = $cheque->che_cuentabancaria;
        $cuentabancaria = CuentasBancarias::find($cuentaid);
        $decimales = explode(".", number_format($cheque->che_monto, 2, ".", ""));
        if($cuentabancaria->ctab_moneda == 1){
        $total_letras = ucfirst($formatterES->format($decimales[0])) . ' quetzales con ' . $decimales[1] . '/100';
        }else{
            $total_letras = ucfirst($formatterES->format($decimales[0])) . ' dólares con ' . $decimales[1] . '/100';
        }
        $data = ['lugar' => 'Guatemala,', 'beneficiario' => $cheque->che_beneficiario,
            'fecha' => $fecha->format('d') ." de ".strtolower(Str::nombreMes(intval($fecha->format('m')) )) .' del '. $fecha->format('Y'),
            'totalNumeros' => $cheque->che_monto
            , 'totalLetras' => $total_letras,
            'negociable' => $cheque->che_negociable,

        ];
        $pdf = app('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $urlView = 'cyb.bancos.cheque.';
        $url = $urlView . ($tipo == 1 ? 'industrial' : ($tipo == 2 ? 'banrural' : 'interbanco'));
        $pdf->loadView($url, ['data' => $data])->setPaper('letter', 'portrait');
        $name = 'Cheque ' . $tipo . '.pdf';
        return $pdf->stream($name);
    }

    public function ChequeBanrural($id){
        $cheque = Cheque::find($id);
        $pdf = \PDF::loadview('cyb.bancos.transferencias.terceros.banruralpdf', compact('cheque'));
        return $pdf->stream('Cheque.pdf');
    }

    public function IndustrialBanrural($id){
        $cheque = Cheque::find($id);
        $pdf = \PDF::loadview('cyb.bancos.transferencias.terceros.industrialpdf', compact('cheque'));
        return $pdf->download('Cheque.pdf');
    }

    public function InterBanrural($id){
        $cheque = Cheque::find($id);
        $pdf = \PDF::loadview('cyb.bancos.transferencias.terceros.interpdf', compact('cheque'));
        return $pdf->download('Cheque.pdf');
    }

    public function chequeLiquidacionPDF(Request $request, $id){
        $search = $request->get('search');
        $documento = $request->get('documento');
        $formatterES = new NumberFormatter("es", NumberFormatter::SPELLOUT);
        $liquidacion = LiquidacionCC::find($id);
        $correlativo = getCorrelativo($liquidacion->lcc_fecha, $search, 99, 'D' )->corr_id;
        $cajas = CajaChica::find( $liquidacion->lcc_cajachica);
        $detalles = DetalleLiquidacionCC::where('dlcc_idcc', $liquidacion->lcc_id)->where('dlcc_status', '=', 'L')->sum('dlcc_monto');
        $fecha = Carbon::parse($liquidacion->lcc_fecha);
        $cuentabancaria = CuentasBancarias::find($search);
        $tipo = $cuentabancaria->ctab_banco;
        $empleado = Empleado::find($cajas->cch_responsable);
        $decimales = explode(".", number_format($detalles, 2, ".", ""));
        $transacciones = Transacciones::where('trab_documento', $documento)->first();
        if(!($transacciones)){
            $dato = Transacciones::create([
                'trab_cuentabancaria'=>$search,
                'trab_fecha'=>$liquidacion->lcc_fecha,
                'trab_documento'=>$documento,
                'trab_tipo'=>'CH',
                'trab_descripcion'=>$liquidacion->lcc_descripcion,
                'trab_monto'=>'-'.$detalles,
                'trab_conciliado'=>0,
                'trab_correlativoInt'=>$correlativo
            ]);
        }else{
            return redirect()->back()->withErrors(['Hubo un problema', 'El <strong>Documento de Referencia</strong> ya esta registrado']);
        }
        if($dato){
            $liquidacion = LiquidacionCC::find($id);
            $liquidacion->update(['lcc_transaccion' => $dato['trab_id']]);
        }
        if($cuentabancaria->ctab_moneda == 1){
            $total_letras = ucfirst($formatterES->format($decimales[0])) . ' quetzales con ' . $decimales[1] . '/100';
        }else{
            $total_letras = ucfirst($formatterES->format($decimales[0])) . ' dólares con ' . $decimales[1] . '/100';
        }
        $data = ['lugar' => 'Guatemala,', 'beneficiario' => $empleado->getNombreCompleto($empleado->empl_id),
            'fecha' => $fecha->format('d') ." de ".strtolower(Str::nombreMes(intval($fecha->format('m')) )) .' del '. $fecha->format('Y'),
            'totalNumeros' => $detalles
            , 'totalLetras' => $total_letras,
            'negociable' => 1,
        ];
        $pdf = app('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $urlView = 'cyb.bancos.cheque.';
        $url = $urlView . ($tipo == 1 ? 'industrial' : ($tipo == 2 ? 'banrural' : 'interbanco'));
        $pdf->loadView($url, ['data' => $data])->setPaper('letter', 'portrait');
        $name = 'Cheque ' . $tipo . '.pdf';
        return $pdf->stream($name);
    }

    public function chequeLiquidacionPDFeditar(Request $request, $id){
        $search = $request->get('search');
        $documento = $request->get('documento');
        $formatterES = new NumberFormatter("es", NumberFormatter::SPELLOUT);
        $liquidacion = LiquidacionCC::find($id);
        $cajas = CajaChica::find( $liquidacion->lcc_cajachica);
        $detalles = DetalleLiquidacionCC::where('dlcc_idcc', $liquidacion->lcc_id)->where('dlcc_status', '=', 'L')->sum('dlcc_monto');
        $fecha = Carbon::parse($liquidacion->lcc_fecha);
        $cuentabancaria = CuentasBancarias::find($search);
        $tipo = $cuentabancaria->ctab_banco;
        $empleado = Empleado::find($cajas->cch_responsable);
        $decimales = explode(".", number_format($detalles, 2, ".", ""));
        $transaccionbancaria= Transacciones::find($liquidacion->lcc_transaccion);
        $transacciones = Transacciones::where('trab_documento', $documento)->first();
        //$prueba = $transacciones;
        if(!($transacciones)){
            $transaccionbancaria->update([
                'trab_cuentabancaria'=>$search,
                'trab_documento'=>$documento
            ]);
        }else{
            if($transaccionbancaria->trab_id==$transacciones->trab_id){
                $transaccionbancaria->update([
                    'trab_cuentabancaria'=>$search,
                    'trab_documento'=>$documento
                ]);
            }else{
                return redirect()->back()->withErrors(['Hubo un problema', 'El <strong>Documento de Referencia</strong> ya esta registrado']);
            }
        }
        if($cuentabancaria->ctab_moneda == 1){
            $total_letras = ucfirst($formatterES->format($decimales[0])) . ' quetzales con ' . $decimales[1] . '/100';
        }else{
            $total_letras = ucfirst($formatterES->format($decimales[0])) . ' dólares con ' . $decimales[1] . '/100';
        }
        $data = ['lugar' => 'Guatemala,', 'beneficiario' => $empleado->getNombreCompleto($empleado->empl_id),
            'fecha' => $fecha->format('d') ." de ".strtolower(Str::nombreMes(intval($fecha->format('m')) )) .' del '. $fecha->format('Y'),
            'totalNumeros' => $detalles
            , 'totalLetras' => $total_letras,
            'negociable' => 1,
        ];
        $pdf = app('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $urlView = 'cyb.bancos.cheque.';
        $url = $urlView . ($tipo == 1 ? 'industrial' : ($tipo == 2 ? 'banrural' : 'interbanco'));
        $pdf->loadView($url, ['data' => $data])->setPaper('letter', 'portrait');
        $name = 'Cheque ' . $tipo . '.pdf';
        return $pdf->stream($name);
    }


    public function liquidCajaChica(Request $request){

    $liquid = $request->get('search');
    $liquid = Carbon::parse(Carbon::createFromFormat('d/m/Y', $liquid))->format('Y-m-d');

        if (auth()->user()->hasRole('Super Administrador')) {
            $liquidaciones = LiquidacionCC::where('lcc_fecha', '>=', $liquid)->where('lcc_pendiente', 0)->get();
        } else {
            $emp = auth()->user()->Empresas->pluck('emp_id');
            $cajaschicas = CajaChica::orderBy('cch_id')->whereIn('cch_empresa', $emp)->get()->pluck('cch_id');
            $liquidaciones = LiquidacionCC::where('lcc_fecha', '>=', $liquid)
                ->where('lcc_pendiente', 0)->whereIn('lcc_cajachica', $cajaschicas)->get();
        }
        $numeral = 0;
    if($liquidaciones->count()==0){
        return redirect()->route('autorizar')->withErrors(['No hay liquidaciones pendientes', 'O no se encontraron Registros para esta fecha.']);
    }else{
        $pdf = \PDF::loadview('cyb.cajas.autorizacion.ccpdf', compact('liquidaciones', 'numeral'));
        return $pdf->download('Cajas Chicas Autorizadas.pdf');
    }
    }

    public function liquidAnticipo(Request $request){
        $liquid = $request->get('search');
        $liquid = Carbon::parse(Carbon::createFromFormat('d/m/Y', $liquid))->format('Y-m-d');
        if (auth()->user()->hasRole('Super Administrador'))
        {
            $anticipos = Anticipos::orderBy('ant_id')->where('ant_fecha', '>=', $liquid)->get();
        }else
        {
            $emp = auth()->user()->Empresas->pluck('emp_id');
            $terAuth = auth()->user()->Terminales->pluck('ter_id');
            $cuentas = CuentasBancarias::orderBy('ctab_id')->whereIn('ctab_empresa', $emp)->get()->pluck('ctab_id');
            $corr = CorrelativoInterno::whereIn('corr_terminal', $terAuth)->get()->pluck('corr_id');
            $cheque = Cheque::orderBy('che_id')->whereIn('che_cuentabancaria', $cuentas)->whereIn('che_correlativoInt', $corr)->get()->pluck('che_id');
            $anticipos = Anticipos::orderBy('ant_id')->whereIn('ant_cheque', $cheque)->where('ant_fecha', '>=', $liquid)->get();
        }
        $numeral = 0;
        if($anticipos->count()==0){
            return redirect()->route('liquidar')->withErrors(['No hay Anticipos Liquidados', 'No se encontraron Registros para esta fecha.']);
        }else{
            $pdf = \PDF::loadview('cyb.bancos.anticipos.liquidar.antpdf', compact('anticipos', 'numeral'));
            return $pdf->download('Anticipos Liquidados.pdf');
        }
    }
    public function conciliadospdf(Request $request){
        $liquid = $request->get('search');
        $liquid2 = $request->get('search2');
        if($liquid2 == '09'){
            $liquid2 = '9';
        }
        $transacciones = Conciliaciones::where('con_mes', $liquid2)->where('con_anio', $liquid)->get();

        if($transacciones->count()==0){
            return redirect()->route('conciliaciones')->withErrors(['No hay Transacciones Liquidadas', 'No se encontraron Registros para esta fecha.']);
        }else{
            $pdf = \PDF::loadview('cyb.bancos.conciliaciones.conciliacion.conciliadospdf', compact('transacciones'));
            return $pdf->download('Transacciones Conciliadas.pdf');
        }
    }
    public function detalleLiquidacion($id){
        $liquidacion = LiquidacionCC::where('lcc_id', $id)->first();
        $caja = CajaChica::where('cch_id', $liquidacion['lcc_cajachica'])->first();
        $detalles = DetalleLiquidacionCC::where('dlcc_idcc', $liquidacion['lcc_id'])->get();
        foreach($detalles as $detalle)
        {
            $detalle['dlcc_fecha'] = Carbon::parse($detalle['dlcc_fecha'])->format('d-m-Y');
        }
        $empresa = Empresa::where('emp_id', $caja['cch_empresa'])->first();
        $total = (new DetalleLiquidacionCC )->totalDetallesCajas($id);
        $anterior= (new DetalleLiquidacionCC )->DetallesCompletos($id);
        $numeral = 0;
        $pdf = app('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        if($detalles->count()==0){
            return redirect()->back()->withErrors(['Error', 'No se encontraron Registros para esta Liquidacion']);
        }else{
            $pdf->loadView('cyb.cajas.liquidaciones.pdf', ['empresa'=>$empresa, 'caja'=>$caja,'detalles' => $detalles, 'total' => $total, 'anterior' => $anterior,'numeral'=>$numeral])->setPaper('letter', 'landscape');
            return $pdf->stream('DetalleLiquidacionCC.pdf');
        }

    }

    public function detallesConciliacion($id){

        $detalles = DetalleConciliacion::where('dcon_conciliacion', $id)->get()->pluck('dcon_documento');
        $transacciones = Transacciones::whereIn('trab_id', $detalles)->get();
        $cuentadebanco =Transacciones::whereIn('trab_id', $detalles)->first();
        $numeral = 0;
        if($transacciones->count()==0){
            return redirect()->route('detallesdeconciliaciones')->withErrors(['No hay Detalles Relacionados', 'No se encontraron Registros para esta Conciliación.']);
        }else{
            $pdf = \PDF::loadview('cyb.bancos.conciliaciones.conciliacion.detallespdf', compact('transacciones', 'numeral', 'cuentadebanco'));
            return $pdf->download('Detalles de Conciliacion.pdf');
        }
    }


}

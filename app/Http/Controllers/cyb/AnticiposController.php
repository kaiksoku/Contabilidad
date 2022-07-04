<?php

namespace App\Http\Controllers\cyb;
use Carbon\Carbon;
use App\Models\cyb\Cheque;
use App\Models\cxp\Compras;
use Illuminate\Http\Request;
use App\Models\Admin\Persona;
use App\Models\cxp\Proveedor;
use App\Models\cyb\Anticipos;
use App\Models\cxp\Importacion;
use League\Flysystem\Exception;
use App\Models\cyb\Transacciones;
use App\Models\Parametros\Empresa;
use Illuminate\Support\Facades\DB;
use App\Models\cyb\DetalleAnticipo;
use App\Http\Controllers\Controller;
use App\Models\cyb\CuentasBancarias;
use App\Models\Admin\CorrelativoInterno;
use App\Models\Admin\MovimientoBancario;
use App\Http\Requests\cyb\ValidacionCheque;
use App\Http\Requests\cyb\ValidacionAnticipo;
use App\Http\Requests\cyb\ValidacionDetalleAnticipo;
use App\Models\cyb\DetalleAnticipoImportacion;

class AnticiposController extends Controller
{

    public function index(Request $request)
    {

        if (auth()->user()->hasRole('Super Administrador'))
        {
            $anticipos = Anticipos::orderBy('ant_id')->get();
        }else
        {
            $emp = auth()->user()->Empresas->pluck('emp_id');
            $terAuth = auth()->user()->Terminales->pluck('ter_id');
            $cuentas = CuentasBancarias::orderBy('ctab_id')->whereIn('ctab_empresa', $emp)->get()->pluck('ctab_id');
            $corr = CorrelativoInterno::whereIn('corr_terminal', $terAuth)->get()->pluck('corr_id');
            $cheque = Cheque::orderBy('che_id')->whereIn('che_cuentabancaria', $cuentas)->whereIn('che_correlativoInt', $corr)->get()->pluck('che_id');
            $anticipos = Anticipos::orderBy('ant_id')->whereIn('ant_cheque', $cheque)->get();
        }
        $buscar = $request->get('buscar');
        $numeral=0;
        return view('cyb.bancos.anticipos.anticipo.index', compact('anticipos', 'buscar', 'numeral'));
    }


    public function create()
    {
        if (auth()->user()->hasRole('Super Administrador')) {
            $cuentasbancariass = CuentasBancarias::all();
        } else {
            $emp = auth()->user()->Empresas->pluck('emp_id');
            $cuentasbancariass = CuentasBancarias::whereIn('ctab_empresa', $emp)->get();
        }
        $proveedores = Proveedor::all();
        return view('cyb.bancos.anticipos.anticipo.crear', compact('cuentasbancariass', 'proveedores'));
    }

    public function facturaunica()
    {
        if (auth()->user()->hasRole('Super Administrador')) {
            $cuentasbancariass = CuentasBancarias::all();
        } else {
            $emp = auth()->user()->Empresas->pluck('emp_id');
            $cuentasbancariass = CuentasBancarias::whereIn('ctab_empresa', $emp)->get();
        }
        $proveedores = Proveedor::all();

        $emp = auth()->user()->Empresas->pluck('emp_id');
        $emp=explode("[", $emp);
        $emp=explode("]", $emp[1]);
        $terAuth = auth()->user()->Terminales->pluck('ter_id');
        $terAuth=explode('[', $terAuth);
        $terAuth=explode(']', $terAuth[1]);
        $Temp="com_empresa in (".$emp[0].") and com_terminal in (".$terAuth[0].") and com_id in (SELECT [com_id] FROM [Contabilidad].[dbo].[compras] inner join detalleanticipo on detalleanticipo.dant_tipo=com_id group by com_id, com_monto having com_monto-sum(dant_monto)>0) or com_id not in (SELECT distinct(dant_tipo) FROM [Contabilidad].[dbo].[detalleanticipo])";
        $movimientos = Compras::selectRaw("com_id,CONCAT(com_serie, ' - ', com_numDoc, ' - ', personas.per_nombre, ' - ', com_monto) as Factura")->
        join("personas", "personas.per_id", "=", "com_persona")->whereRaw($Temp)->get();


        return view('cyb.bancos.anticipos.anticipo.facturaunica', compact('cuentasbancariass', 'proveedores', 'movimientos'));
    }

    public function polizaimportacion()
    {
        if (auth()->user()->hasRole('Super Administrador')) {
            $cuentasbancariass = CuentasBancarias::all();
        } else {
            $emp = auth()->user()->Empresas->pluck('emp_id');
            $cuentasbancariass = CuentasBancarias::whereIn('ctab_empresa', $emp)->get();
        }
        $proveedores = Proveedor::all();

        $emp = auth()->user()->Empresas->pluck('emp_id');
        $emp=explode("[", $emp);
        $emp=explode("]", $emp[1]);
        $terAuth = auth()->user()->Terminales->pluck('ter_id');
        $terAuth=explode('[', $terAuth);
        $terAuth=explode(']', $terAuth[1]);
        $Temp="poim_empresa in (".$emp[0].") and poim_terminal in (".$terAuth[0].")";
        //$Temp="poim_empresa in (".$emp[0].") and poim_terminal in (".$terAuth[0].") and com_id in (SELECT [com_id] FROM [Contabilidad].[dbo].[compras] inner join detalleanticipo on detalleanticipo.dant_tipo=com_id group by com_id, com_monto having com_monto-sum(dant_monto)>0) or com_id not in (SELECT distinct(dant_tipo) FROM [Contabilidad].[dbo].[detalleanticipo])";
        $movimientos = Importacion::selectRaw("[poim_id], concat(poim_orden, ' - ', poim_dua) as Polizas")->
        whereRaw($Temp)->get();
        $Temp="che_id not in  (SELECT distinct([ant_cheque]) FROM [Contabilidad].[dbo].[anticipo]) and che_tipo in ('CA', 'TA', 'TR') and ctab_empresa in (".$emp[0].")";
        $cheques = Cheque::selectRaw("che_id, CONCAT(cuentabancaria.ctab_numero, '-', che_numero, '-', che_monto) as Cheques")->
        join('cuentabancaria', 'cuentabancaria.ctab_id', '=', 'che_cuentabancaria')->whereRaw($Temp)->get();

        return view('cyb.bancos.anticipos.anticipo.polizaimportacion', compact('cuentasbancariass', 'proveedores', 'cheques', 'movimientos'));
    }


    public function store(ValidacionCheque $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $data = $request->validated();
                $data['che_fecha'] = Carbon::parse(Carbon::createFromFormat('d/m/Y', $data['che_fecha']))->format('Y-m-d H:i:s');
                $cuentabancaria = CuentasBancarias::findOrFail($data['che_cuentabancaria']);
                $Corr =getCorrelativo($data['che_fecha'], $cuentabancaria->ctab_empresa, $data['che_terminal'], 'D' );
                $request->merge(['che_correlativoInt' => $Corr->corr_id]);
                $request->merge(['correlativoTexto' => $Corr->corr_correlativo]);
                $data['che_correlativoInt'] = $Corr->corr_id;
                $beneficiarioanticipo = $data['che_beneficiario'];
                if(!($request->che_conciliado)){
                    $data['che_conciliado']= 0;
                }
                if(!($request->che_tipo)){
                    $data['che_tipo']= 'CH';
                }
                if(!($request->che_negociable)){
                    $data['che_negociable']= 0;
                }
                if(!($request->che_tc)){
                    $data['che_tc']= 1;
                }
                if(($request->che_beneficiario)==0){
                    $data['che_beneficiario']= $data['beneficiario'];
                }else{
                    $proveedor = Proveedor::find($data['che_beneficiario']);
                    $beneficiario = Persona::find($proveedor['pro_persona']);
                    $data['che_beneficiario']= $beneficiario['per_nombre'];
                }
                $cheque = Cheque::create($data);
                if($cheque['che_beneficiario']== $data['beneficiario']){
                    $cheque['che_beneficiario']= null;
                }else{
                    $cheque['che_beneficiario']= $beneficiarioanticipo;
                }
                Anticipos::create([
                    'ant_numero' => $cuentabancaria['ctab_numero'] .'-'. $data['che_numero'],
                    'ant_fecha' => $cheque['che_fecha'],
                    'ant_liquidado' => 0,
                    'ant_cheque' => $cheque['che_id'],
                    'ant_proveedor' => $cheque['che_beneficiario'],
                ]);
                Transacciones::create([
                    'trab_cuentabancaria'=> $cheque['che_cuentabancaria'],
                    'trab_fecha'=> $cheque['che_fecha'],
                    'trab_documento'=> $cheque['che_numero'],
                    'trab_tipo'=> $cheque['che_tipo'],
                    'trab_descripcion'=> $cheque['che_descripcion'],
                    'trab_monto'=> '-'.$cheque['che_monto'],
                    'trab_conciliado'=> 0,
                    'trab_correlativoInt'=> $cheque['che_correlativoInt']
                ]);
            });
            return redirect()->route('anticipos')->with('mensajeHTML', 'Cheque Generado con el correlativo: ')->with('correlativo', $request->correlativoTexto);
        } catch (Exception $e) {
            return redirect()->route('anticipos')->withErrors(['mensaje', 'Error generando el Registro.']);
        }
    }

    public function storeFacturaUnica(Request $request)
    {
        $data = $request;
        $cuentabancaria = CuentasBancarias::findOrFail($data['che_cuentabancaria']);
        try {
            DB::transaction(function () use ($request) {
                $data = $request;
                $data['che_fecha'] = Carbon::parse(Carbon::createFromFormat('d/m/Y', $data['che_fecha']))->format('Y-m-d H:i:s');
                $cuentabancaria = CuentasBancarias::findOrFail($data['che_cuentabancaria']);
                $Corr =getCorrelativo($data['che_fecha'], $cuentabancaria->ctab_empresa, $data['che_terminal'], 'D' );
                $request->merge(['che_correlativoInt' => $Corr->corr_id]);
                $request->merge(['correlativoTexto' => $Corr->corr_correlativo]);
                $data['che_correlativoInt'] = $Corr->corr_id;
                $beneficiarioanticipo = $data['che_beneficiario'];
                if(!($request->che_conciliado)){
                    $data['che_conciliado']= 0;
                }
                if(!($request->che_tipo)){
                    $data['che_tipo']= 'CH';
                }
                if(!($request->che_negociable)){
                    $data['che_negociable']= 0;
                }
                if(!($request->che_tc)){
                    $data['che_tc']= 1;
                }
                if(($request->che_beneficiario)==0){
                    $data['che_beneficiario']= $data['beneficiario'];
                }else{
                    $proveedor = Proveedor::find($data['che_beneficiario']);
                    $beneficiario = Persona::find($proveedor['pro_persona']);
                    $data['che_beneficiario']= $beneficiario['per_nombre'];
                }

                $cheque = Cheque::create($data->request->all());
                if($cheque['che_beneficiario']== $data['beneficiario']){
                    $cheque['che_beneficiario']= null;
                }else{
                    $cheque['che_beneficiario']= $beneficiarioanticipo;
                }

                Anticipos::create([
                    'ant_numero' => $cuentabancaria['ctab_numero'] .'-'. $data['che_numero'],
                    'ant_fecha' => $cheque['che_fecha'],
                    'ant_liquidado' => 1,
                    'ant_cheque' => $cheque['che_id'],
                    'ant_proveedor' => $cheque['che_beneficiario'],
                ]);
                Transacciones::create([
                    'trab_cuentabancaria'=> $cheque['che_cuentabancaria'],
                    'trab_fecha'=> $cheque['che_fecha'],
                    'trab_documento'=> $cheque['che_numero'],
                    'trab_tipo'=> $cheque['che_tipo'],
                    'trab_descripcion'=> $cheque['che_descripcion'],
                    'trab_monto'=> '-'.$cheque['che_monto'],
                    'trab_conciliado'=> 0,
                    'trab_correlativoInt'=> $cheque['che_correlativoInt']
                ]);
            });
                    $anticipo = Anticipos::where('ant_numero', $cuentabancaria['ctab_numero'].'-'.$request->che_numero)->get()->pluck('ant_id');
                    $chequeid = $request->che_numero;
                    $monto = $request->che_monto;
                    $suma = DetalleAnticipo::where('dant_anticipo', $anticipo)->sum('dant_monto');
                    $sumaF = DetalleAnticipo::where('dant_tipo', $request->dant_tipo)->sum('dant_monto')+$monto;
                    $MontoF= Compras::where('com_id', $request->dant_tipo)->get()->pluck('com_monto');
                    $DiferenciaActiciposFactura=$MontoF[0]-$sumaF;
                    if($suma+$request->dant_monto<=$monto) {
                        try {
                            if($DiferenciaActiciposFactura>=0)
                            {
                                DB::transaction(function () use ($request, $anticipo) {
                                    if (!($request->dant_estado)) {
                                        $data['dant_estado'] = 'P';
                                    }
                                    DetalleAnticipo::create([
                                        "dant_anticipo" => $anticipo[0],
                                        "dant_tipo" => $request->dant_tipo,
                                        "dant_documento" => $request->che_numero,
                                        "dant_monto" => $request->che_monto,
                                        "dant_estado" => "P"
                                    ]);
                                });
                                return redirect()->route('anticipos')->with('mensaje', 'Registro generado con éxito.');
                            }
                            else{
                                return redirect()->route('anticipos')->withErrors(['catch2', 'El anticipo supera el monto de la Factura.']);
                            }

                        } catch (Exception $e) {
                            return redirect()->route('anticipos')->withErrors(['catch2', $e->errorInfo]);
                        }
                    }else{
                        return redirect()->back()->withErrors(['Error con el monto del detalle', 'No se puede superar el limite establecido']);
                    }
                } catch (Exception $e) {
                    return redirect()->route('anticipos')->withErrors(['mensaje', 'Error generando el Registro.']);
                }
    }

    public function storePolizaImportacion(Request $request)
    {
        //dd($request);
        $cheque=Cheque::find($request->che_numero);
        $poliza=Importacion::find($request->dant_tipo);
        /*
        Cheque
        "che_id" => "1"
        "che_cuentabancaria" => "1"
        "che_numero" => "123"
        "che_fecha" => "2021-11-09"
        "che_monto" => "123.00000"
        "che_beneficiario" => "123"
        "che_descripcion" => "11111111111111111111111111111111111111"
        "che_negociable" => "0"
        "che_tipo" => "CA"
        "che_tc" => "1.00000"
        "che_correlativoInt" => "2"
        "created_at" => "2021-11-09 11:41:27.813"
        "updated_at" => "2021-11-09 11:41:27.813"
        */

        /*
        Poliza
        "poim_id" => "1"
        "poim_fecha" => "2021-11-09"
        "poim_proveedor" => "Locura transitoria"
        "poim_descripcion" => "Prueba de perfil de poliza"
        "poim_orden" => "1"
        "poim_dua" => "1"
        "poim_moneda" => "1"
        "poim_tipoCambio" => "1.00000"
        "poim_FOB" => "1.00000"
        "poim_flete" => "1.00000"
        "poim_seguro" => "1.00000"
        "poim_IVA" => "1.00000"
        "poim_empresa" => "1"
        "poim_terminal" => "3"
        "poim_correlativoInt" => "4"
        "created_at" => "2021-11-09 13:47:15.627"
        "updated_at" => "2021-11-09 13:47:15.627"
        */

        $TotalPoliza=(($poliza->poim_FOB+$poliza->poim_flete+$poliza->poim_seguro)*$poliza->poim_tipoCambio)+$poliza->poim_IVA;
        //$TotalPagosPoliza=DetalleAnticipoImportacion::where('dant_tipo', '=', $request->dant_tipo)->get()->sum('dant_monto');

        try{
            $AnticipoCheck=Anticipos::create([
                'ant_numero' =>$cheque->CuentasBancarias->ctab_numero.'-'.$cheque->che_numero,
                'ant_fecha' => date("Y-m-d H:i:s"),
                'ant_liquidado' => 1,
                'ant_cheque' => $cheque->che_id,
                'ant_proveedor' => $request->che_beneficiario
            ]);
            if($AnticipoCheck)
            {
                $anticipo = Anticipos::where('ant_numero', $cheque->CuentasBancarias->ctab_numero.'-'.$cheque->che_numero)->get()->pluck('ant_id');

                $DetalleCheck=DetalleAnticipoImportacion::create([
                    "dant_anticipo" => $anticipo[0],
                    "dant_tipo" => $request->dant_tipo,
                    "dant_documento" => $cheque->che_id,
                    "dant_monto" => $cheque->che_monto
                ]);

                if($DetalleCheck)
                {
                    return redirect()->route('anticipos')->with('mensaje', 'Registro generado con éxito.');
                }
                else
                {
                    return redirect()->back()->withErrors(['Error en el proceso.', 'No se guardo el detalle del anticipo a poliza de importacion.']);
                }
            }
            else
            {
                return redirect()->back()->withErrors(['Error en el proceso.', 'No se guardo el anticipo a poliza de importacion.']);
            }
        }
        catch(Exception $e){
            return redirect()->back()->withErrors(['Error en el proceso.', 'Comprobar los datos proporcionados.']);
        }

    }


    public function show(Request $request)
    {
        if (auth()->user()->hasRole('Super Administrador'))
        {
            $anticipos = Anticipos::orderBy('ant_id')->get();
        }else
        {
            $emp = auth()->user()->Empresas->pluck('emp_id');
            $terAuth = auth()->user()->Terminales->pluck('ter_id');
            $cuentas = CuentasBancarias::orderBy('ctab_id')->whereIn('ctab_empresa', $emp)->get()->pluck('ctab_id');
            $corr = CorrelativoInterno::whereIn('corr_terminal', $terAuth)->get()->pluck('corr_id');
            $cheque = Cheque::orderBy('che_id')->whereIn('che_cuentabancaria', $cuentas)->whereIn('che_correlativoInt', $corr)->get()->pluck('che_id');
            $anticipos = Anticipos::orderBy('ant_id')->whereIn('ant_cheque', $cheque)->get();
        }
        $buscar = $request->get('buscar');
        $numeral=0;
        return view('cyb.bancos.anticipos.liquidar.index', compact('anticipos', 'buscar', 'numeral'));
    }

    public function masterdetalles($id)
    {
        $detalles = DetalleAnticipo::where('dant_anticipo', $id)->get();
        $nombre = Anticipos::findOrFail($id);
        $numeral=0;
        return view('cyb.bancos.anticipos.liquidar.masterdetalles', compact('detalles', 'nombre', 'numeral'));
    }


    public function antdetalle($id)
    {
        $emp = auth()->user()->Empresas->pluck('emp_id');
        $emp=explode("[", $emp);
        $emp=explode("]", $emp[1]);
        $terAuth = auth()->user()->Terminales->pluck('ter_id');
        $terAuth=explode('[', $terAuth);
        $terAuth=explode(']', $terAuth[1]);
        $Temp="com_empresa in (".$emp[0].") and com_terminal in (".$terAuth[0].") and com_id in (SELECT [com_id] FROM [Contabilidad].[dbo].[compras] inner join detalleanticipo on detalleanticipo.dant_tipo=com_id group by com_id, com_monto having com_monto-sum(dant_monto)>0) or com_id not in (SELECT distinct(dant_tipo) FROM [Contabilidad].[dbo].[detalleanticipo])";
        $anticipos = Anticipos::find($id);
        $movimientos = Compras::selectRaw("com_id,CONCAT(com_serie, ' - ', com_numDoc, ' - ', personas.per_nombre, ' - ', com_monto) as Factura")->
        join("personas", "personas.per_id", "=", "com_persona")->whereRaw($Temp)->get();
        return view('cyb.bancos.anticipos.anticipo.creardetalle', compact('anticipos', 'movimientos'));
    }

    public function storedetalle(ValidacionDetalleAnticipo $request)
    {
        $anticipo = Anticipos::find($request->dant_anticipo);
        $cheque = Cheque::find($anticipo->ant_cheque);
        $monto = $cheque->che_monto;
        $suma = DetalleAnticipo::where('dant_anticipo', $request->dant_anticipo)->sum('dant_monto');
        $sumaF = DetalleAnticipo::where('dant_tipo', $request->dant_tipo)->sum('dant_monto')+$request->dant_monto;
        $MontoF= Compras::where('com_id', $request->dant_tipo)->get()->pluck('com_monto');
        $DiferenciaActiciposFactura=$MontoF[0]-$sumaF;
        if($suma+$request->dant_monto<=$monto) {
            try {
                if($DiferenciaActiciposFactura>=0)
                {
                    DB::transaction(function () use ($request) {
                        $data = $request->validated();
                        dd($data);
                        if (!($request->dant_estado)) {
                            $data['dant_estado'] = 'P';
                        }
                        DetalleAnticipo::create($data);
                    });
                    return redirect()->route('anticipos')->with('mensaje', 'Registro generado con éxito.');
                }
                else{
                    return redirect()->route('anticipos')->withErrors(['catch2', 'El anticipo supera el monto de la Factura.']);
                }

            } catch (Exception $e) {
                return redirect()->route('anticipos')->withErrors(['catch2', $e->errorInfo]);
            }
        }else{
            return redirect()->back()->withErrors(['Error con el monto del detalle', 'No se puede superar el limite establecido']);
        }
    }

    public function listadetalle($id)
    {
        $nombre = Anticipos::findOrFail($id);
        $numeral=0;
        $detalles = DetalleAnticipo::where('dant_anticipo', $id)->get();
        if($detalles->isEmpty())
        {
            $detalles = DetalleAnticipoImportacion::where('dant_anticipo', $id)->get();
            return view('cyb.bancos.anticipos.anticipo.listadetalleimport', compact('detalles', 'nombre', 'numeral'));

        }
        else{
            return view('cyb.bancos.anticipos.anticipo.listadetalle', compact('detalles', 'nombre', 'numeral'));
        }
    }

    public function liquiddetalles(Request $request, $detalles, $cambiar)
    {
        if ($request->ajax()) {
            $detalles = DetalleAnticipo::findorfail($detalles);
            if ($cambiar == 'liquidado') {
                $detalles->update(['lcc_pendiente' => 1]);
            } else {
                $detalles->update(['lcc_pendiente' => 0 ]);
            }
        } else {
            abort(404);
        }
    }

    public function detalleEstado(Request $request, $detalle, $cambiar)
    {
        if ($request->ajax()) {
            $detalle = DetalleAnticipo::findorfail($detalle);
            if ($cambiar == 'liquidado') {
                $detalle->update(['dant_estado' => 'L']);
            } else {
                $detalle->update(['dant_estado' => 'R']);
            }
        } else {
            abort(404);
        }
    }

    public function destroy($id){
        try {
            $detalle = DetalleAnticipo::find($id);
            $detalle->delete();
            return redirect()->back()->with('mensaje', 'Registro eliminado con Exito.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors(['No se pudo eliminar', 'Hubo un error']);
        }

    }

    public function antLiquidar(Request $request, $liquidacion, $cambiar)
    {
        if ($request->ajax()) {
            $anticipo = Anticipos::findorfail($liquidacion);

            if ($cambiar == 'liquidado') {
                $anticipo->update(['ant_liquidado' => 1]);
                $detalles = DetalleAnticipo::where('dant_anticipo', $liquidacion)->get();
                foreach ($detalles as $detalle) {
                    if ($detalle->dant_estado == 'P'){
                        $detalle->update(['dant_estado' =>  'R']);
                    }
                }
            } else {
                $anticipo->update(['ant_liquidado' => 0 ]);
            }
        } else {
            abort(404);
        }
    }
}

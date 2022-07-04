<?php

namespace App\Http\Controllers\Contabilidad;

use Exception;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Models\Contabilidad\CuentaContable;
use App\Models\cyb\CajaChica;
use App\Models\Parametros\Empresa;
use App\Models\Parametros\Centrocostos;
use DeviceDetector\Parser\Device\Console;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Seguridad\Usuario;

class CuentaContableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->hasRole('Super Administrador')) {
            $datas = CuentaContable::join('empresa', 'empresa.emp_id', '=', 'cuentacontable.cta_empresa')->where('cta_codigo', '>', 0)->orderBy('cta_empresa')->orderBy('cta_codigo')->get();
        } else {
            $emp = auth()->user()->Empresas->pluck('emp_id');
            $ter = auth()->user()->Terminales->pluck('ter_id');
            $datas = CuentaContable::join('empresa', 'empresa.id', '=', 'cuentacontable.cta_empresa')->where('cta_codigo', '>', 0)->whereIn('cta_empresa', $emp)->orderBy('cta_empresa')->orderBy('cta_codigo')->get();
        }
        return view('contabilidad/cuentacontable/index', compact('datas'));
    }
    public function ideaslokas()
    {
        return view('contabilidad/cuentacontable/ideaslokas');
        //Prueba Git
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('contabilidad.cuentacontable.crear');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            //Apartamos la empresa de la cuenta contable.
            $CodigoEmpresa=Empresa::where('emp_NIT', $request->empresa)->get()->pluck('emp_id');
            $CodigoNomenclatura=0;
            $Temp=0;
            $CuentaDetalle=0;
            $Saldo='D';
            $Excento=1;
            if($request->saldo==0)$Saldo='H';
            if($request->excento!=1)$Excento=0;

            //Nomenclatura
            //Evaluar si es de centrales para ir descendente.
            if($request->com_terminal==99)
            {
                //Un caso para Nivel 4
                if($request->nivel4=='0'){
                $Temp=$request->nivel3;
                $CodigoNivel3=$Temp.'%';
                $codNv4 = DB::table('cuentacontable')->
                selectRaw("isnull(min(cta_codigo)-1, CONCAT('".$Temp."', 99)) as codN")->
                whereRaw("cta_codigo like ? and LEN(cta_codigo)=6 and cta_empresa=? and SUBSTRING(cta_codigo, 7, 1)=9", [$CodigoNivel3, $CodigoEmpresa])->get();
                $CodigoNomenclatura=$codNv4[0]->codN;
                }
                //Otro para Nivel 5
                else{
                $Temp=$request->nivel4;
                $CodigoNivel4=$Temp.'%';
                $codNv5 = DB::table('cuentacontable')->
                selectRaw("isnull(min(cta_codigo)-1, CONCAT('".$Temp."', 99)) as codN")->
                whereRaw("cta_codigo like ? and LEN(cta_codigo)=8 and cta_empresa=? and SUBSTRING(cta_codigo, 7, 1)=9", [$CodigoNivel4, $CodigoEmpresa])->get();
                $CodigoNomenclatura=$codNv5[0]->codN;
                $CuentaDetalle=1;
                }
            }
            //En caso contrario en las terminales se hace ascendente
            else{
                //Un caso para Nivel 4
                if($request->nivel4=='0')
                {
                    $Temp=$request->nivel3;
                    $CodigoNivel3=$Temp.'%';
                    $CodigoNuevo=$Temp.'01';
                    $codNv4 = DB::table('cuentacontable')->
                    selectRaw("isnull(MAX(cta_codigo)+1, CONCAT('".$Temp."', 01)) as codN")->
                    whereRaw("cta_codigo like ? and LEN(cta_codigo)=6 and cta_empresa=? and SUBSTRING(cta_codigo, 5, 1)!=9", [$CodigoNuevo, $CodigoNivel3, $CodigoEmpresa])->get();
                    $CodigoNomenclatura=$codNv4[0]->codN;
                }
                //Otro para Nivel 5
                else{
                    $Temp=$request->nivel4;
                    $CodigoNivel4=$Temp.'%';
                    $CodigoNuevo=$Temp.'01';
                    $codNv5 = DB::table('cuentacontable')->
                    selectRaw("isnull(MAX(cta_codigo)+1, CONCAT('".$Temp."', 01)) as codN")->
                    whereRaw("cta_codigo like ? and LEN(cta_codigo)=8 and cta_empresa=? and SUBSTRING(cta_codigo, 7, 1)!=9", [$CodigoNivel4, $CodigoEmpresa])->get();
                    $CodigoNomenclatura=$codNv5[0]->codN;
                    $CuentaDetalle=1;
                }
            }

            //Agregar la cuenta contable.

            DB::table('cuentacontable')->insert([
                ['cta_codigo' => $CodigoNomenclatura, 'cta_descripcion' => $request->nuevaCuenta, 'cta_padre' => $Temp, 'cta_detalle' => $CuentaDetalle,
                'cta_centroCosto' => $request->centrocostos, 'cta_tipoSaldo' => $Saldo, 'cta_empresa' => $CodigoEmpresa[0], 'cta_excento' => $Excento,
                'cta_obs1' => $request->cta_obs1,'cta_obs2' => $request->cta_obs2,'cta_obs3' => $request->cta_obs3,'created_at' => Carbon::now()->format('Y-m-d H:i:s')]
            ]);
            return redirect('contabilidad/cuentacontable')->with('mensaje', 'Cuenta contable guardada correctamente.');

        } catch (Exception $e) {
            return redirect('contabilidad/cuentacontable')->withErrors(['catch', 'Error al guardar cuenta contable, revisar los datos proporcionados.']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {

        try {
            if($request->cta_tipoSaldo)
            {
                $request->cta_tipoSaldo='D';
            }
            else
            {
                $request->cta_tipoSaldo='H';
            }
            if($request->cta_excento)
            {
                $request->cta_excento=1;
            }
            else
            {
                $request->cta_excento=0;
            }
            CuentaContable::where('cta_id', $id)->update(['cta_tipoSaldo'=>$request->cta_tipoSaldo,'cta_excento'=>$request->cta_excento,'cta_centrocosto'=>$request->cta_centrocosto,
            'cta_obs1'=>$request->cta_obs1,'cta_obs2'=>$request->cta_obs2,'cta_obs3'=>$request->cta_obs3,'cta_descripcion'=>$request->cta_descripcion]);
            return redirect('contabilidad/cuentacontable')->with('mensaje', 'Moneda actualizada correctamente.');
        }
        catch (Exception $e) {
            return redirect('contabilidad/cuentacontable')->withErrors(['catch2', $e->errorInfo]);
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = CuentaContable::join('empresa', 'empresa.emp_id', '=', 'cuentacontable.cta_empresa')->
        where('cta_id', '=', $id)->get();
        $centrocosto=Centrocostos::where('cco_id', '=', $data[0]->cta_centroCosto)->get();
        $centrocostos=Centrocostos::where('cco_regimen', $data[0]->emp_regimen)->get();
        $data=$data[0];
        $centrocosto=$centrocosto[0];
        if($data->cta_tipoSaldo=='D')
        {
            $data->cta_tipoSaldo='1';
        }
        else
        {
            $data->cta_tipoSaldo='0';
        }
        return view('contabilidad.cuentacontable.editar',compact('data', 'centrocosto', 'centrocostos'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $Hijos=CuentaContable::selectRaw('count(cta_id) as Hijos')->where('cta_padre', '=', $id)->get();
            $Hijos=$Hijos[0]->Hijos;
            if($Hijos==0){
                CuentaContable::destroy($id);
                return redirect('contabilidad/cuentacontable')->with('mensaje', 'Cuenta contable eliminanda correctamente.');
            }
            else{
                return redirect('contabilidad/cuentacontable')->withErrors(['catch', 'La cuenta contable que desea eliminar tiene cuentas hijos.']);
            }
            //
        } catch (Exception $e) {
            return redirect('contabilidad/cuentacontable')->withErrors(['catch', $e->errorInfo]);
        }
        //
    }

    public function CentroCostos(request $request, $emp)
    {
        if ($request->ajax()) {
            $RegimenEmpresa=Empresa::where('emp_id', $emp)->get()->pluck('emp_regimen');
            $centrocostos=Centrocostos::where('cco_regimen', $RegimenEmpresa)->get();
            return response()->json($centrocostos);
        } else {
            abort(404);
        }
    }

    public function CuentaNivel1(request $request, $emp)
    {
        if ($request->ajax()) {
            $cta = CuentaContable::where('cta_codigo', '>', 0)->where('cta_empresa', '=', $emp)->whereRaw('len(cta_codigo)=?', [1])->orderBy('cta_empresa')->orderBy('cta_codigo')->get();
            return response()->json($cta);
        } else {
            abort(404);
        }
    }

    public function CuentaNivel2(request $request, $emp, $Nivel)
    {
        if ($request->ajax()) {
            $cta = CuentaContable::where('cta_codigo', '>', 0)->where('cta_empresa', '=', $emp)->whereRaw('SUBSTRING(cta_codigo, 1, 1)=?', $Nivel)->whereRaw('len(cta_codigo)=?', [2])->orderBy('cta_empresa')->orderBy('cta_codigo')->get();
            return response()->json($cta);
        } else {
            abort(404);
        }
    }

    public function CuentaNivel3(request $request, $emp, $Nivel)
    {
        if ($request->ajax()) {
            $cta = CuentaContable::where('cta_codigo', '>', 0)->where('cta_empresa', '=', $emp)->whereRaw('SUBSTRING(cta_codigo, 1, 2)=?', $Nivel)->whereRaw('len(cta_codigo)=?', [4])->orderBy('cta_empresa')->orderBy('cta_codigo')->get();
            return response()->json($cta);
        } else {
            abort(404);
        }
    }

    public function CuentaNivel4(request $request, $emp, $Nivel)
    {
        if ($request->ajax()) {
            $cta = CuentaContable::where('cta_codigo', '>', 0)->where('cta_empresa', '=', $emp)->whereRaw('SUBSTRING(cta_codigo, 1, 4)=?', $Nivel)->whereRaw('len(cta_codigo)=?', [6])->orderBy('cta_empresa')->orderBy('cta_codigo')->get();
            return response()->json($cta);
        } else {
            abort(404);
        }
    }

    public function CuentaActivoGasto(request $request, $emp, $ter, $nivel1, $detalle = 0)
    {
        $nivel2 = "[1-9]";
        $ter = str_pad($ter, 2, '0', STR_PAD_LEFT);
        if ($request->ajax()) {
            if ($nivel1 == "[1]") {
                $nivel2 = "[^123457]";
                if ($detalle == 1) {
                    $cta = CuentaContable::where('cta_empresa', $emp)->where('cta_codigo', 'LIKE', $nivel1 . $nivel2 . "%")->where('cta_detalle', 1)->where('cta_tipoSaldo', 'D')->get();
                } else {
                    $cta = CuentaContable::where('cta_empresa', $emp)->where('cta_codigo', 'LIKE', $nivel1 . $nivel2 . "%")->where('cta_tipoSaldo', 'D')->get();
                }
            } else {
                if ($detalle == 1) {
                    $cta = CuentaContable::where('cta_empresa', $emp)->where('cta_codigo', 'LIKE', $nivel1 . $nivel2 . $ter . "%")->where('cta_detalle', 1)->get();
                } else {
                    $cta = CuentaContable::where('cta_empresa', $emp)->where('cta_codigo', 'LIKE', $nivel1 . $nivel2 . $ter . "%")->get();
                }
            }
            return response()->json($cta);
        } else {
            abort(404);
        }
    }

    public function CuentasExcentas(request $request, $emp, $ter)
    {
        $ter = str_pad($ter, 2, '0', STR_PAD_LEFT);
        if ($request->ajax()) {
            $cta = CuentaContable::where('cta_empresa', $emp)->where('cta_excento', 1)->where('cta_codigo', 'LIKE', '[57]_' . $ter . '%')->get();
            return response()->json($cta);
        } else {
            abort(404);
        }
    }

    public function CuentaDepreciacion(request $request, $emp, $ter, $nivel1, $detalle = 0)
    {
        $nivel2 = "1";
        $nivel4 = "07";
        if ($ter == 99) {
            $nivel1 = "7";
            $nivel4 = "03";
        }
        $ter = str_pad($ter, 2, '0', STR_PAD_LEFT);
        if ($request->ajax()) {
            if ($detalle == 1) {
                $cta = CuentaContable::where('cta_empresa', $emp)->where('cta_codigo', 'LIKE', $nivel1 . $nivel2 . $ter . $nivel4 . "%")->where('cta_detalle', 1)->get();
            } else {
                $cta = CuentaContable::where('cta_empresa', $emp)->where('cta_codigo', 'LIKE', $nivel1 . $nivel2 . $ter . $nivel4 . "%")->get();
            }
            return response()->json($cta);
        } else {
            abort(404);
        }
    }

    public function CuentaAmortizacion(request $request, $emp, $ter, $detalle = 0)
    {
        $nivel1 = "7";
        $nivel2 = "1";
        $ter = str_pad($ter, 2, '0', STR_PAD_LEFT);
        if ($request->ajax()) {
            if ($detalle == 1) {
                $cta = CuentaContable::where('cta_empresa', $emp)->where('cta_codigo', 'LIKE', $nivel1 . $nivel2 . "%")->where('cta_detalle', 1)->where('cta_descripcion', 'LIKE', 'AMORTIZACION%')->get();
            } else {
                $cta = CuentaContable::where('cta_empresa', $emp)->where('cta_codigo', 'LIKE', $nivel1 . $nivel2 . "%")->where('cta_descripcion', 'LIKE', 'AMORTIZACION%')->get();
            }
            return response()->json($cta);
        } else {
            abort(404);
        }
    }

    public function CuentaDepAcum(request $request, $emp, $detalle = 0)
    {
        if ($request->ajax()) {
            if ($detalle == 1) {
                $cta = CuentaContable::where('cta_empresa', $emp)->where('cta_codigo', 'LIKE', "17%")->where('cta_detalle', 1)->get();
            } else {
                $cta = CuentaContable::where('cta_empresa', $emp)->where('cta_codigo', 'LIKE', "17%")->get();
            }
            return response()->json($cta);
        } else {
            abort(404);
        }
    }

    public function CuentaAmortAcum(request $request, $emp, $detalle = 0)
    {
        if ($request->ajax()) {
            if ($detalle == 1) {
                $cta = CuentaContable::where('cta_empresa', $emp)->where('cta_codigo', 'LIKE', "180102%")->where('cta_detalle', 1)->where('cta_tipoSaldo', 'H')->get();
            } else {
                $cta = CuentaContable::where('cta_empresa', $emp)->where('cta_codigo', 'LIKE', "180102%")->get();
            }
            return response()->json($cta);
        } else {
            abort(404);
        }
    }

    public function CuentaPorNivel(Request $request, $emp, $nivel = 'planillas', $detalle = 0)
    {

        if ($request->ajax()) {
            if ($nivel == 'planillas') {
                $nivel1 = '[75]';
                if ($detalle == 1) {
                    $cta = CuentaContable::where('cta_empresa', $emp)->where('cta_codigo', 'LIKE', $nivel1 . "%")->where('cta_detalle', 1)->get();
                } else {
                    $cta = CuentaContable::where('cta_empresa', $emp)->where('cta_codigo', 'LIKE', $nivel1 . "%")->get();
                }
            } elseif ($nivel == 'caja') {
                [$empresa,$terminal]= explode('-',$emp);
                $nivel1 = '[57][0-9]'.str_pad($terminal, 2, "0", STR_PAD_LEFT).'%';
                if ($detalle == 1) {
                    $cta = CuentaContable::where('cta_empresa', $empresa)->where('cta_codigo', 'LIKE', $nivel1 . "%")->where('cta_detalle', 1)->get();
                } else {
                    $cta = CuentaContable::where('cta_empresa', $emp)->where('cta_codigo', 'LIKE', $nivel1)->get();
                }
            } else {
                [$empresa,$tipo]= explode('-',$emp);
                $nivel1 = '1101'.$tipo;
                if ($detalle == 1) {
                    $cta = CuentaContable::where('cta_empresa', $empresa)->where('cta_codigo', 'LIKE', $nivel1 . "%")->where('cta_detalle', 1)->get();
                } else {
                    $cta = CuentaContable::where('cta_empresa', $emp)->where('cta_codigo', 'LIKE', $nivel1)->get();
                }
            }
            return response()->json($cta);
        } else {
            abort(404);
        }
    }

    public function CuentaCajaChica(Request $request, $emp)
    {
        $nivel1 = '11';
        if ($request->ajax()) {
            $cajas = CajaChica::where('cch_id', '>', 0)->get()->pluck('cch_cuentacontable');
                $cta = CuentaContable::where('cta_empresa', $emp)
                    ->where('cta_codigo', 'LIKE', $nivel1."%")
                    ->where('cta_descripcion', 'LIKE', 'CAJA'."%")
                    ->where('cta_detalle', true)
                    ->whereNotIn('cta_id',$cajas)
                    ->get();
            return response()->json($cta);
        } else {
            abort(404);
        }
    }

    public function Poliza(Request $request, $emp)
    {
        if ($request->ajax()) {
            $act = CuentaContable::where('cta_empresa', '=', $emp)->where('cta_detalle', 'LIKE', '1%')->get();
            return response()->json($act);
        } else {
            abort(404);
        }
    }

    public function PolizaNit(Request $request, $emp)
    {
        $id=Empresa::where('emp_NIT', $emp)->get()->pluck('emp_id');
        if ($request->ajax()) {
            $act = CuentaContable::where('cta_empresa', $id)->where('cta_detalle', 'LIKE', '1%')->get();
            return response()->json($act);
        } else {
            abort(404);
        }
    }


}

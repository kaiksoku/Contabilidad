<?php

namespace  App\Http\Controllers\Contabilidad;

use Exception;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Contabilidad\Poliza;
use App\Http\Controllers\Controller;
use App\Models\Contabilidad\DetPoliza;
use App\Http\Requests\Contabilidad\ValidacionPoliza;

class PolizaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->hasRole('Super Administrador')) {
            $datas = Poliza::orderBy('pol_id')->get();
        } else {
            $emp = auth()->user()->Empresas->pluck('emp_id');

            $datas = Poliza::whereIn('pol_empresa', $emp)->orderBy('pol_id')->get();
        }
        return view('contabilidad.poliza.index1', compact('datas'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

         return view('contabilidad.poliza.crear');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        $fecha = Carbon::createFromFormat('d/m/Y', $request->pol_fecha);
        $FechaFinal = Carbon::parse($fecha)->format('Y-m-d H:i:s');
        $TempMes = Carbon::parse($fecha)->format('m');
        $TempAno = Carbon::parse($fecha)->format('Y');
        $Cantidadpartidas=count($request->dpol_monto);

        $Temp="isnull(max(pol_numero)+1,CONCAT(".$TempMes.",'000001')) as numero";
        $TempNumero=Poliza::selectRaw($Temp)->whereRaw('MONTH(pol_fecha)=? and YEAR(pol_fecha)=? and pol_empresa=?', [$TempMes, $TempAno, $request->com_empresa])->get()->pluck('numero');
        try {
            DB::table('polizas')->insert([
                ['pol_numero'=> $TempNumero[0], 'pol_fecha'=> $FechaFinal,'pol_descripcion'=> $request->pol_descripcion, 'pol_empresa'=> $request->com_empresa]
            ]);

            for($i=0;$i<$Cantidadpartidas;$i++)
            {
                DB::table('detpolizas')->insert([
                    ['dpol_idpoliza'=> $TempNumero[0], 'dpol_ctaContable'=> $request->dpol_ctaContable[$i],'dpol_monto'=> $request->dpol_monto[$i], 'dpol_posicion'=> $request->flexRadioDefault[$i]]
                ]);
            }
            return redirect('contabilidad/poliza')->with('mensajeHTML', "Poliza creada exitosamente");
        } catch (Exception $e) {
            return redirect('contabilidad/poliza')->withErrors(['catch2', $e->errorInfo]);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Poliza::findOrFail($id);
        return view('contabilidad.poliza.mostrar', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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


    public function FacturaPDF($id)
    {


        $data = Poliza::findOrFail($id);
        $pdf = app('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadView('contabilidad.poliza.pdf', compact('data'))->setPaper('letter');
        return $pdf->download('PolizaManual.pdf');
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
}

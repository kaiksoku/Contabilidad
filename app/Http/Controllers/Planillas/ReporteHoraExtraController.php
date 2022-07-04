<?php

namespace App\Http\Controllers\Planillas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Planillas\ValidacionReporteHorasExtras;
use App\Models\Planilla\ReporteHoraExtra;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Http\Request;

class ReporteHoraExtraController extends Controller
{

    public function index()
    {
        $query  =   ReporteHoraExtra::with('Salario')->join('salarios as s','s.sal_id','=','reportehorae.ree_salario')->orderBy('ree_id');
        if (auth()->user()->hasRole('Super Administrador')) {
            $datas =  $query->get();
        } else {
            $emp = auth()->user()->Empresas->pluck('emp_id');
            $ter = auth()->user()->Terminales->pluck('ter_id');
            $datas = $query->whereIn('sal_empresa', $emp)->whereIn('sal_terminal', $ter)->orderBy('sal_id')->get();
        }
        return  view('planillas.generacion.mensual.reportehorasextra.index',compact('datas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function create()
    {
        return view('planillas.generacion.mensual.reportehorasextra.crear');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ValidacionReporteHorasExtras $request)
    {
        $data = $request->validated();
        $data['ree_fecha'] = Carbon::createFromFormat('d/m/Y', $data['ree_fecha'])->format('Y-m-d H:i:s');
        ReporteHoraExtra::create($data);
        return redirect()->route('reporte-horae')->with('mensaje', 'Reporte ingresado con exito.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        $reporte = ReporteHoraExtra::find($id);
        $reporte->ree_empresa = $reporte->Salario->Empresa->emp_id;
        $reporte->ree_terminal = $reporte->Salario->Terminal->ter_id;
        $reporte->ree_fecha =  Carbon::parse($reporte->ree_fecha)->format('d/m/Y');
        return view('planillas.generacion.mensual.reportehorasextra.editar',compact('id','reporte'));

    }


    public function update(ValidacionReporteHorasExtras $request, $id)
    {
        $data = $request->validated();
        $data['ree_fecha'] = Carbon::createFromFormat('d/m/Y', $data['ree_fecha'])->format('Y-m-d H:i:s');
        $reporte = ReporteHoraExtra::find($id);
        $reporte->update($data);
        return redirect()->route('reporte-horae')->with('mensaje', 'Reporte actualizado con exito.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return DB::transaction(function () use ($id) {
            try {
                ReporteHoraExtra::find($id)->delete();
            } catch (Exception $e) {
                return redirect()->route('reporte-horae')->withErrors(['catch', $e->errorInfo]);
            }
            return redirect()->route('reporte-horae')->with('mensaje', 'Reporte eliminando correctamente.');
        });
    }
}

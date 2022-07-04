<?php

namespace App\Http\Controllers\Planillas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Planillas\ValidacionDetalleTurnos;
use App\Http\Requests\Planillas\ValidacionReportesTurnos;
use App\Http\Requests\Planillas\ValidacionValidarReporteTurnos;
use App\Models\Planilla\ControlSeguridad;
use App\Models\Planilla\DetallePlanilla;
use App\Models\Planilla\DetalleTurnos;
use App\Models\Planilla\Empleado;
use App\Models\Planilla\ReporteTurnos;
use App\Models\Planilla\Salarios;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteTurnosController extends Controller
{

    public function index(Request $request)
    {
        [$from, $to] = explode(' / ', $request->get('date') ?: ' / ');
        $from = Carbon::createFromFormat('d/m/Y', $from ?: now()->startOfMonth()->format('d/m/Y'));
        $to = Carbon::createFromFormat('d/m/Y', $to ?: now()->endOfMonth()->format('d/m/Y'));
        $query = ReporteTurnos::with('Planilla')->join('planilla as p', 'p.pla_id', 'rept_planilla')
            ->orderBy('rept_id')->whereBetween('rept_fecha', [$from->format('Y-m-d'), $to->format('Y-m-d')]);
        if (auth()->user()->hasRole('Super Administrador')) {
            $datas = $query->get();
        } else {
            $emp = auth()->user()->Empresas->pluck('emp_id');
            $ter = auth()->user()->Terminales->pluck('ter_id');
            $datas = $query->whereIn('p.pla_empresa', $emp)->whereIn('p.pla_terminal', $ter)->get();
        }
        $date = $from->format('d/m/Y') . " / " . $to->format('d/m/Y');
        return view('planillas.generacion.eventual.reporte-turnos.index', ['datas' => $datas, 'date' => $date]);
    }

    public function create(Request $request)
    {

        return view('planillas.generacion.eventual.reporte-turnos.crear');
    }

    public function createDetail(Request $request, $id)
    {
        $detalle = new DetalleTurnos();
        return view('planillas.generacion.eventual.reporte-turnos.asignar-empleados.detalle-crear', compact('detalle', 'id'));
    }

    public function store(Request $request)
    {
        if ($request->session()->has('dataRept')) {
            DB::transaction(function () use ($request) {
                $data = $request->session()->get('dataRept');
                $dataEmpleados = $request->session()->get('dataEmpleadosSeleccionados');
                $reporte = ReporteTurnos::create($data);
                foreach ($dataEmpleados as $item) {
                    $item['dett_reporte'] = $reporte->rept_id;
                    DetalleTurnos::create($item);
                }
            });
            return redirect()->route('reporte-turnos')->with('mensaje', 'Reporte creado exitosamente.');

        } else {
            return redirect()->route('reporte-turnos')->withErrors('A ocurrido un error.');

        }
    }

    public function storeDetail(ValidacionDetalleTurnos $request,$id)
    {
        $data = $request->validated();
        $data['dett_reporte'] = $id;
        DetalleTurnos::create($data);
        return redirect()->route('reporte-turnos.ver',$id)->with('mensaje', 'Reporte creado exitosamente.');


    }


    public function show($id)
    {
        $datas = DetalleTurnos::where('dett_reporte', $id)->orderBy('dett_id')->get();
        return view('planillas.generacion.eventual.reporte-turnos.show', compact('datas', 'id'));
    }


    public function edit($id)
    {
        $detalle = DetalleTurnos::find($id);
        return view('planillas.generacion.eventual.reporte-turnos.asignar-empleados.editar', compact('detalle', 'id'));

    }


    public function update(ValidacionDetalleTurnos $request, $id)
    {
        $data = $request->validated();
        $detalle = DetalleTurnos::find($id);
        $detalle->update($data);
        return redirect()->route('reporte-turnos.ver', $id)->with('mensaje', 'Reporte actualizado exitosamente.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function destroyDetail($id)
    {
        return DB::transaction(function () use ($id) {
            try {
                DetalleTurnos::find($id)->delete();
            } catch (Exception $e) {
                return redirect()->back()->withErrors(['catch', $e->errorInfo]);
            }
            return redirect()->back()->with('mensaje', 'Empleado eliminando correctamente.');
        });
    }

    public function asignar(ValidacionReportesTurnos $request)
    {
        $data = $request->validated();
        $data['rept_inicio'] = Carbon::parse(Carbon::createFromFormat('d/m/Y H:i', $data['rept_fecha'] . ' ' . $data['rept_inicio']))->format('Y-m-d H:i:s');
        $data['rept_fin'] = Carbon::parse(Carbon::createFromFormat('d/m/Y H:i', $data['rept_fecha'] . ' ' . $data['rept_fin']))->format('Y-m-d H:i:s');
        $data['rept_fecha'] = Carbon::parse(Carbon::createFromFormat('d/m/Y', $data['rept_fecha']))->format('Y-m-d');
        $empleados = Salarios::where('sal_terminal', $data['rept_terminal'])->where('sal_empresa', $data['rept_empresa'])->where('sal_tipo', '=', 'T')->get();

        $request->session()->put(['dataRept' => $data]);
        $request->session()->put(['dataEmpleados' => $empleados]);
        $request->session()->put(['dataEmpleadosSeleccionados' => []]);
        $detalle = new DetalleTurnos();

        return view('planillas.generacion.eventual.reporte-turnos.asignar-empleados.crear', compact('detalle'));
    }

    public function asignarEmpleados(ValidacionDetalleTurnos $request)
    {
        if ($request->session()->has('dataRept')) {
            $empleados = $request->validated();
            $dataEmpleados = $request->session()->get('dataEmpleadosSeleccionados');
            $detalle = new DetalleTurnos();

            $key = array_search(($empleados['dett_salario']), array_column($dataEmpleados, 'dett_salario'));
            if (false !== $key) {
                return view('planillas.generacion.eventual.reporte-turnos.asignar-empleados.crear', compact('detalle'))->withErrors(['El empleado ya fue asignado']);
            } else {
                $request->session()->push('dataEmpleadosSeleccionados', $empleados);

                return view('planillas.generacion.eventual.reporte-turnos.asignar-empleados.crear', compact('detalle'))->with('mensaje', 'Empleado asigando con exito.');
            }
        } else {
            return redirect()->route('reporte-turnos.crear')->withErrors('A ocurrido un error.');

        }
    }

    public function eliminar(Request $request, $key)
    {

        if ($request->session()->has('dataRept')) {
            $dataEmpleados = $request->session()->get('dataEmpleadosSeleccionados');
            unset($dataEmpleados[$key]);
            $request->session()->put('dataEmpleadosSeleccionados', $dataEmpleados);

            return view('planillas.generacion.eventual.reporte-turnos.asignar-empleados.crear')->with('mensaje', 'Empleado eliminado con exito.');
        } else {
            return redirect()->route('reporte-turnos.crear')->withErrors('A ocurrido un error.');

        }
    }
}

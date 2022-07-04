<?php

namespace App\Http\Controllers\Planillas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Planillas\ValidacionSalario;
use App\Models\Planilla\Empleado;
use App\Models\Planilla\Puesto;
use App\Models\Planilla\Salarios;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SalarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index($id)
    {
        $salarios = Salarios::where('sal_empleado', $id)->get();
        $nombre = (new Empleado)->getNombreCompleto($id);
        return view('planillas.empleados.salarios.index', compact('salarios', 'id', 'nombre'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function create($id)
    {

        $nombre = (new Empleado)->getNombreCompleto($id);
        return view('planillas.empleados.salarios.crear', compact('id', 'nombre'));


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ValidacionSalario $request): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validated();
        $salario = Salarios::where('sal_empresa', $data['sal_empresa'])->where('sal_empresa', $data['sal_terminal'])->where('sal_empleado', $data['sal_empleado'])->first();
        if ($salario) {
            throw ValidationException::withMessages(['Ya existe un salario registrado para esta empresa']);
        }
        $data['sal_inicio'] = Carbon::createFromFormat('d/m/Y', $data['sal_inicio'])->format('Y-m-d H:i:s');
        $data['sal_fin'] = $data['sal_fin'] ? Carbon::parse(Carbon::createFromFormat('d/m/Y', $data['sal_fin']))->format('Y-m-d H:i:s') : null;
        if (!isset($data['sal_igss'])) {
            $data['sal_igss'] = 0;
        }
        Salarios::create($data);
        return redirect()->route('empleados-salario', $data['sal_empleado'])->with('mensaje', 'Salario creado con exito.');

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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function edit($id)
    {
        $salario = Salarios::find($id);
        $nombre = (new Empleado)->getNombreCompleto($salario->sal_empleado);
        $id = $salario->sal_empleado;
        return view('planillas.empleados.salarios.editar', compact('id', 'nombre', 'salario'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ValidacionSalario $request, $id)
    {
        $data = $request->validated();
        $salario = Salarios::find($id);
        $data['sal_inicio'] = Carbon::createFromFormat('d/m/Y', $data['sal_inicio'])->format('Y-m-d H:i:s');
        $data['sal_fin'] = $data['sal_fin'] ? Carbon::parse(Carbon::createFromFormat('d/m/Y', $data['sal_fin']))->format('Y-m-d H:i:s') : null;
        if (!isset($data['sal_igss'])) {
            $data['sal_igss'] = 0;
        }
        $salario->update($data);

        return redirect()->route('empleados-salario', $data['sal_empleado'])->with('mensaje', 'Salario actualizado con exito.');
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

    public function getPuesto(Request $request, $id)
    {
        if ($request->ajax()) {
            $puestos = Puesto::where('pues_empresa', $id)->get();
            return response()->json($puestos);
        } else {
            abort(404);
        }
    }

}

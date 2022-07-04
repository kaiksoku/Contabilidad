<?php

namespace App\Http\Controllers\Planillas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Planillas\ValidacionPuesto;
use App\Models\Planilla\Puesto;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PuestosController extends Controller
{
    public function index()
    {
        if (auth()->user()->hasRole('Super Administrador')) {
            $datas = Puesto::orderBy('pues_id')->get();
        } else {
            $emp = auth()->user()->Empresas->pluck('emp_id');
            $ter = auth()->user()->Terminales->pluck('ter_id');
            $datas = Puesto::orderBy('pues_id')->whereIn('pues_empresa', $emp)->whereIn('pues_terminal', $ter)->get();
        }
        return view('planillas.empleados.puestos.index', compact('datas'));
    }

    public function create()
    {
        $puesto = new Puesto();
        return view('planillas.empleados.puestos.crear', compact('puesto'));
    }


    public function store(ValidacionPuesto $request)
    {

        $data = $request->validated();
        Puesto::create($data);
        return redirect()->route('puestos')->with('mensaje', 'Puesto creado con exito.');
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
        $puesto = Puesto::find($id);
        return view('planillas.empleados.puestos.editar', compact('puesto'));
    }


    public function update(ValidacionPuesto $request, $id)
    {
        try {
            DB::transaction(function () use ($request, $id) {
                $puesto = Puesto::find($id);
                $data = $request->validated();
                $puesto->fill($data);
                $puesto->save();
            });
        } catch (Exception $e) {
            return redirect()->route('puestos')->withErrors(['catch2', $e->errorInfo]);
        }
        return redirect()->route('puestos')->with('mensaje', 'Puesto actualizado con exito.');

    }


    public function destroy($id)
    {
        return DB::transaction(function () use ($id) {
            try {
                Puesto::destroy($id);
            } catch (Exception $e) {
                return redirect()->route('puestos')->withErrors(['catch', $e->errorInfo]);
            }
            return redirect()->route('puestos')->with('mensaje', 'Puesto eliminando correctamente.');
        });

    }

}

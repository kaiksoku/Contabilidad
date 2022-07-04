<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Cxc\Clave;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Http\Requests\Admin\ValidacionClave;



class ClaveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datas =Clave::where('cla_empresa','>',0)->orderBy('cla_empresa')->get();
        return view('admin.clave.index',compact('datas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.clave.crear');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ValidacionClave $request)
    {
        //dd($request->all());
        Clave::create($request->all());
        return redirect('admin/clave')->with('mensaje', 'Clave para facturación creada exitosamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Clave::findOrFail($id);
       return view('admin.clave.mostrar', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Clave::findOrFail($id);
        return view('admin.clave.editar',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ValidacionClave $request, $id)
    {

        Clave::findOrFail($id)->update($request->all());
        return redirect('admin/clave')->with('mensaje', 'Clave actualizada correctamente.');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     public function destroy($id)
    {
        try {
            Clave::destroy($id);
        } catch (Exception $e) {
            return redirect('admin/clave')->withErrors(['catch', $e->errorInfo]);
        }
        return redirect('admin/clave')->with('mensaje', 'Clave eliminanda correctamente.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Models\Devs;
use Illuminate\Http\Request;
use App\Http\Requests\ValidarDev;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class DevTechsController extends Controller
{   
    private function getDevsTechsPorStatus($status)
    {
        $devs = Devs::whereHas('techs', function($query) use ($status) {
            return $query->where('status', $status);
        })->get();

        return $devs;
    }   

    private function getDevsTechsPorNotStatus($status)
    {   
        DB::enableQueryLog();

        $devs = Devs::whereDoesntHave('techs', function($query) use ($status) {
            return $query->where('status', $status);
        })->get();

        Log::alert('select', DB::getQueryLog());

        return $devs;
    }  

    private function getComplexoDevTechsPorDev(Devs $dev, $status)
    {
        /* return $dev->belongsToMany(
            'App\Http\Models\Techs', 
            'devs_techs', 
            'id_dev', 
            'id_tech'
        )->wherePivot('status', $status)->withPivot('status')->get(); */

        /* OU */

        return $dev->techs()->wherePivot('status', $status)->withPivot('status')->get();
    }

    private function getDevsComTechs() 
    {
        $devs = Devs::with(['techs'])->get();

        return $devs;
    }

    private function getDevsComTechsFiltro($status) 
    {
        $devs = Devs::whereHas('techs', function ($query) use ($status) {
            return $query->where('status', $status); 
        })->with(['techs' => function($query) use ($status) {
            return $query->wherePivot('status', $status)->withPivot('status'); 
        }, 'posts'])->get();

        return $devs;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $status = request('status');
        $id     = request('id');

        $dev = Devs::find($id);

        //return $this->getDevsTechsPorStatus($status);
        //return $this->getDevsTechsPorNotStatus($status);
        //return $this->getComplexoDevTechsPorDev($dev, $status);
        //return $this->getDevsComTechs();
        return $this->getDevsComTechsFiltro($status);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ValidarDev $request)
    {
        $json = $request->only(['nome', 'github_username']);

        $json_techs = $request->input('techs');

        $dev = Devs::create($json);
        $dev->techs()->attach($json_techs);
        $dev->save();

        return $dev;
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
        $json = $request->input('techs');

        $dev = Devs::find($id);

        if (!$dev) {
            return response()->json(['erro' => 'Desenvolvedor não encontrado'], 404);
        }

        /* Insere se não existir */
        /* $dev->techs()->syncWithoutDetaching($json);*/

        foreach ($json as $key => $tech) {
            $dev->techs()->updateExistingPivot($key, $tech);
        }

        return $dev;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $json = request()->input('techs');

        $dev = Devs::find($id);

        if (!$dev) {
            return response()->json(['erro' => 'Desenvolvedor não encontrado'], 404);
        }

        $dev->techs()->detach($json);

        return response()->json(['sucesso' => 'Techs foram removidas']);
    }
}

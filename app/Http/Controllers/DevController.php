<?php

namespace App\Http\Controllers;

use App\Http\Models\Devs;
use App\Http\Requests\ValidarDev;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DevController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        //$devs = Devs::all();
        $devs = Devs::select()->with(['posts'])->get();

        if (sizeof($devs) == 0) {
            return response()->json(['erro' => 'Nenhum registro encontrado'], 404);
        }

        /* Ocultar campo na consulta */
        $devs->makeHidden(['nome']);

        return $devs;
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
        $json_post = $request->only(['post']);

        //DB::enableQueryLog();
        $devs = Devs::create($json);
        //$devs->posts()->create($json_post['post']);
        $devs->posts()->createMany($json_post['post']);
        $devs->save();
        //dd(DB::getQueryLog());
        return $devs;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        /* FIltro, se id > 0 busca somente o registro com este id */
        if ($id > 0) {
            $devs = Devs::where('id', $id)->get();

        }

        if (sizeof($devs) == 0) {
            return response()->json(['erro' => 'Nenhum registro encontrado'], 404);
        }

        /* Ocultar campo na consulta */
        $devs->makeHidden(['nome']);

        return $devs;
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
    public function update(ValidarDev $request, $id)
    {
        $json = $request->only(['nome', 'github_username']);
        $devs = Devs::find($id);

        $devs->posts()->createMany([
            ['titulo' => 'Teste 3', 'descricao' => 'Teste 3'],
            ['titulo' => 'Teste 4', 'descricao' => 'Teste 4'],
        ]);

        $devs->update($json);

        /* Obter registro alterado */
        $dev = Devs::where('id', $id)->get();
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
        
        try {

            $dev = Devs::find($id)->delete();
            
        } catch (\Illuminate\Database\QueryException $e) {
            
            Log::alert($e->getTraceAsString());
            return response()->json(['error' => 'Não foi possivel realizar a exclusão do registro'], 550);
        }

        if ($dev) {
            return response()->json(['sucess' => 'Registro apagado com sucesso']);
        } else {
            return response()->json(['erro' => 'Problemas para apagar o registro'], 400);
        }
    }
}

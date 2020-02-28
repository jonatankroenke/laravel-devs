<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Techs extends Model
{
    protected $table = 'techs';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function devs() 
    {
        return $this->belongsToMany(
            'App\Http\Models\Devs', /* Outro Model */
            'devs_techs', /* Tabela relacional */
            'id_tech', /* id da relação do model atual */
            'id_dev' /* id da relação do outro model */
        );
    }
}

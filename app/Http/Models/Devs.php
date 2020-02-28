<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Devs extends Model
{
    protected $table = 'devs';
    protected $primaryKey = 'id';
    protected $hidden = ['created_at', 'updated_at'];
    protected $guarded = [];

    public function posts()
    {
        return $this->hasMany('App\Http\Models\Posts', 'dev_id', 'id');
    }

    public function techs() 
    {
        return $this->belongsToMany(
            'App\Http\Models\Techs', /* Outro Model */
            'devs_techs', /* Tabela relacional */
            'id_dev', /* id da relação do model atual */
            'id_tech' /* id da relação do outro model */
        );
    }
}

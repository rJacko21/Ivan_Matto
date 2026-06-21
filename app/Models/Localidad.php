<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Localidad extends Model {
    protected $table = 'localidads';
    protected $fillable = ['nombre_localidad'];

    public function eventos() {
        return $this->hasMany(Evento::class, 'id_localidad'); // Relación exigida en indicador 3
    }
}
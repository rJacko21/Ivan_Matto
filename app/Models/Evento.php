<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evento extends Model {
    protected $fillable = ['nombre_evento', 'fecha', 'id_localidad'];

    public function localidad() {
        return $this->belongsTo(Localidad::class, 'id_localidad'); // Relación exigida en indicador 3
    }
}

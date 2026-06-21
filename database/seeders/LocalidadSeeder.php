<?php

namespace Database\Seeders;

use App\Models\Localidad;
use Illuminate\Database\Seeder;

class LocalidadSeeder extends Seeder {
    public function run(): void {
        Localidad::create(['nombre_localidad' => 'Villarrica']);
        Localidad::create(['nombre_localidad' => 'Asunción']);
        Localidad::create(['nombre_localidad' => 'Coronel Oviedo']);
    }
}

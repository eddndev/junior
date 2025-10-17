<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $areas = [
            [
                'name' => 'Dirección General',
                'slug' => 'direccion-general',
                'description' => 'Dirección ejecutiva de la empresa',
                'is_active' => true,
            ],
            [
                'name' => 'Recursos Humanos',
                'slug' => 'recursos-humanos',
                'description' => 'Gestión del talento humano y administración de personal',
                'is_active' => true,
            ],
            [
                'name' => 'Finanzas',
                'slug' => 'finanzas',
                'description' => 'Gestión financiera, presupuestos y cotizaciones',
                'is_active' => true,
            ],
            [
                'name' => 'Marketing',
                'slug' => 'marketing',
                'description' => 'Campañas de marketing, generación de leads y comunicación',
                'is_active' => true,
            ],
            [
                'name' => 'Producción',
                'slug' => 'produccion',
                'description' => 'Operaciones de producción y logística',
                'is_active' => true,
            ],
            [
                'name' => 'Tecnología',
                'slug' => 'tecnologia',
                'description' => 'Desarrollo de tecnología e infraestructura IT',
                'is_active' => true,
            ],
        ];

        foreach ($areas as $area) {
            Area::create($area);
        }

        $this->command->info('Áreas creadas exitosamente.');
    }
}

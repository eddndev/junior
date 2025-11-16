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
                'is_system' => true, // Área del sistema - no se puede desactivar
            ],
            [
                'name' => 'Recursos Humanos',
                'slug' => 'recursos-humanos',
                'description' => 'Gestión del talento humano y administración de personal',
                'is_active' => true,
                'is_system' => true, // Área del sistema - no se puede desactivar
            ],
            [
                'name' => 'Finanzas',
                'slug' => 'finanzas',
                'description' => 'Gestión financiera, presupuestos y cotizaciones',
                'is_active' => true,
                'is_system' => true, // Área del sistema - no se puede desactivar
            ],
            [
                'name' => 'Marketing',
                'slug' => 'marketing',
                'description' => 'Campañas de marketing, generación de leads y comunicación',
                'is_active' => true,
                'is_system' => true, // Área del sistema - no se puede desactivar
            ],
            [
                'name' => 'Producción',
                'slug' => 'produccion',
                'description' => 'Operaciones de producción y logística',
                'is_active' => true,
                'is_system' => true, // Área del sistema - no se puede desactivar
            ],
            [
                'name' => 'General',
                'slug' => 'general',
                'description' => 'Área común para comunicaciones y bitácora general de toda la empresa',
                'is_active' => true,
                'is_system' => true, // Área del sistema - no se puede desactivar
            ],
        ];

        foreach ($areas as $area) {
            Area::create($area);
        }

        $this->command->info('Áreas creadas exitosamente.');
    }
}

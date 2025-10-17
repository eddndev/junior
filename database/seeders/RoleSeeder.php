<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Dirección General',
                'slug' => 'direccion-general',
                'description' => 'Rol de supervisión con acceso total al sistema. Puede monitorear todas las áreas y delegar tareas.',
            ],
            [
                'name' => 'Director de Área',
                'slug' => 'director-area',
                'description' => 'Responsable de un área específica. Recibe tareas de Dirección General y las desglosa para su equipo.',
            ],
            [
                'name' => 'Administrador de RRHH',
                'slug' => 'admin-rrhh',
                'description' => 'Gestiona el ciclo de vida de los usuarios (altas, bajas, modificaciones) y envía notificaciones.',
            ],
            [
                'name' => 'Gestor Financiero',
                'slug' => 'gestor-financiero',
                'description' => 'Gestiona clientes, catálogo de costos, presupuestos y cotizaciones.',
            ],
            [
                'name' => 'Gestor de Marketing',
                'slug' => 'gestor-marketing',
                'description' => 'Crea y gestiona campañas de marketing, asigna tareas y registra leads.',
            ],
            [
                'name' => 'Miembro de Producción',
                'slug' => 'miembro-produccion',
                'description' => 'Visualiza tareas asignadas, gestiona calendario de disponibilidad y marca tareas completadas.',
            ],
            [
                'name' => 'Empleado General',
                'slug' => 'empleado-general',
                'description' => 'Usuario base con acceso a notificaciones, calendario y bitácoras de su área.',
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }

        $this->command->info('Roles creados exitosamente.');
    }
}

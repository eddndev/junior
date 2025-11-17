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
                'description' => 'Rol ejecutivo con acceso total al sistema. Puede monitorear todas las áreas y delegar tareas.',
            ],
            [
                'name' => 'Director de Área',
                'slug' => 'director-area',
                'description' => 'Responsable de un área específica. Asigna tareas, gestiona equipo y supervisa entregas.',
            ],
            [
                'name' => 'Gerente',
                'slug' => 'gerente',
                'description' => 'Apoyo al director de área. Mismos permisos que director para gestionar equipo y tareas.',
            ],
            [
                'name' => 'Administrador de RRHH',
                'slug' => 'admin-rrhh',
                'description' => 'Gestiona el ciclo de vida de los usuarios (altas, bajas, modificaciones) y configuración del sistema.',
            ],
            [
                'name' => 'Empleado',
                'slug' => 'empleado',
                'description' => 'Usuario operativo. Puede ver y entregar tareas, actualizar bitácora, ver calendario y recibir mensajes.',
            ],
            [
                'name' => 'Supervisor',
                'slug' => 'supervisor',
                'description' => 'Rol de solo lectura. Puede visualizar todas las actividades, usuarios y reportes sin modificar nada.',
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }

        $this->command->info('Roles creados exitosamente.');
    }
}

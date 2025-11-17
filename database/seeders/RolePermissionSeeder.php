<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener todos los roles
        $direccionGeneral = Role::where('slug', 'direccion-general')->first();
        $directorArea = Role::where('slug', 'director-area')->first();
        $gerente = Role::where('slug', 'gerente')->first();
        $adminRRHH = Role::where('slug', 'admin-rrhh')->first();
        $empleado = Role::where('slug', 'empleado')->first();
        $supervisor = Role::where('slug', 'supervisor')->first();

        // ===================================
        // DIRECCIÓN GENERAL - Acceso Total
        // ===================================
        $allPermissions = Permission::all()->pluck('id');
        $direccionGeneral->permissions()->sync($allPermissions);

        // ===================================
        // ADMINISTRADOR DE RRHH
        // ===================================
        $rrhhPermissions = Permission::whereIn('slug', [
            // Gestión de usuarios
            'gestionar-usuarios',
            'asignar-roles',
            'ver-usuarios',
            'gestionar-areas',
            'ver-areas',
            // Comunicación
            'enviar-notificaciones',
            'enviar-mensajes-generales',
            'ver-notificaciones',
            // Trazabilidad
            'ver-trazabilidad',
            'filtrar-trazabilidad',
            // Tareas básicas
            'ver-tareas',
            'ver-bitacora',
            'crear-bitacora',
            // Calendario
            'ver-calendario',
            'gestionar-disponibilidad',
        ])->pluck('id');
        $adminRRHH->permissions()->sync($rrhhPermissions);

        // ===================================
        // DIRECTOR DE ÁREA
        // ===================================
        $directorPermissions = Permission::whereIn('slug', [
            // Usuarios
            'ver-usuarios',
            'ver-areas',
            // Tareas completas
            'crear-tareas',
            'editar-tareas',
            'eliminar-tareas',
            'asignar-tareas',
            'ver-tareas',
            'completar-tareas',
            // Bitácora
            'crear-bitacora',
            'editar-bitacora',
            'eliminar-bitacora',
            'ver-bitacora',
            // Calendario de disponibilidad
            'gestionar-disponibilidad',
            'ver-disponibilidad-equipo',
            // Calendario General
            'ver-calendario',
            'crear-eventos-calendario',
            'editar-eventos-calendario',
            'eliminar-eventos-calendario',
            'crear-reuniones',
            'registrar-asistencia',
            // Comunicación
            'enviar-notificaciones',
            'ver-notificaciones',
            // Trazabilidad
            'ver-trazabilidad',
        ])->pluck('id');
        $directorArea->permissions()->sync($directorPermissions);

        // ===================================
        // GERENTE - Mismos permisos que Director de Área
        // ===================================
        $gerente->permissions()->sync($directorPermissions);

        // ===================================
        // EMPLEADO
        // ===================================
        $empleadoPermissions = Permission::whereIn('slug', [
            // Tareas
            'ver-tareas',
            'completar-tareas',
            // Bitácora
            'ver-bitacora',
            'crear-bitacora',
            'editar-bitacora',
            // Calendario de disponibilidad
            'gestionar-disponibilidad',
            'ver-disponibilidad-equipo',
            // Calendario General
            'ver-calendario',
            // Comunicación
            'ver-notificaciones',
            // Básico
            'ver-usuarios',
            'ver-areas',
        ])->pluck('id');
        $empleado->permissions()->sync($empleadoPermissions);

        // ===================================
        // SUPERVISOR - Solo permisos de lectura (ver)
        // ===================================
        $supervisorPermissions = Permission::where('slug', 'like', 'ver-%')->pluck('id');
        $supervisor->permissions()->sync($supervisorPermissions);

        $this->command->info('Permisos asignados a roles exitosamente.');
    }
}

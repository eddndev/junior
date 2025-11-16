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
        $adminRRHH = Role::where('slug', 'admin-rrhh')->first();
        $gestorFinanciero = Role::where('slug', 'gestor-financiero')->first();
        $gestorMarketing = Role::where('slug', 'gestor-marketing')->first();
        $miembroProduccion = Role::where('slug', 'miembro-produccion')->first();
        $empleadoGeneral = Role::where('slug', 'empleado-general')->first();

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
        // GESTOR FINANCIERO
        // ===================================
        $finanzasPermissions = Permission::whereIn('slug', [
            // Clientes
            'gestionar-clientes',
            'ver-clientes',
            // Catálogo de costos
            'gestionar-catalogo-costos',
            'ver-catalogo-costos',
            // Presupuestos
            'crear-presupuestos',
            'editar-presupuestos',
            'ver-presupuestos',
            'registrar-gastos',
            'ver-reportes-presupuestos',
            // Cotizaciones
            'crear-cotizaciones',
            'editar-cotizaciones',
            'ver-cotizaciones',
            'usar-calculadora-costos',
            // Básico
            'ver-usuarios',
            'ver-areas',
            'ver-notificaciones',
            'ver-tareas',
            'completar-tareas',
            'ver-bitacora',
            'crear-bitacora',
        ])->pluck('id');
        $gestorFinanciero->permissions()->sync($finanzasPermissions);

        // ===================================
        // GESTOR DE MARKETING
        // ===================================
        $marketingPermissions = Permission::whereIn('slug', [
            // Campañas
            'crear-campanas',
            'editar-campanas',
            'ver-campanas',
            // Tareas de marketing
            'crear-tareas-marketing',
            'asignar-tareas-marketing',
            'ver-tareas-marketing',
            // Leads
            'registrar-leads',
            'editar-leads',
            'ver-leads',
            'ver-reportes-marketing',
            // Básico
            'ver-usuarios',
            'ver-areas',
            'ver-notificaciones',
            'ver-tareas',
            'completar-tareas',
            'ver-bitacora',
            'crear-bitacora',
        ])->pluck('id');
        $gestorMarketing->permissions()->sync($marketingPermissions);

        // ===================================
        // MIEMBRO DE PRODUCCIÓN
        // ===================================
        $produccionPermissions = Permission::whereIn('slug', [
            // Tareas
            'ver-tareas',
            'completar-tareas',
            // Bitácora
            'crear-bitacora',
            'ver-bitacora',
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
        $miembroProduccion->permissions()->sync($produccionPermissions);

        // ===================================
        // EMPLEADO GENERAL
        // ===================================
        $empleadoPermissions = Permission::whereIn('slug', [
            // Tareas básicas
            'ver-tareas',
            'completar-tareas',
            // Bitácora
            'ver-bitacora',
            'crear-bitacora',
            // Calendario General
            'ver-calendario',
            // Comunicación
            'ver-notificaciones',
            // Básico
            'ver-usuarios',
            'ver-areas',
        ])->pluck('id');
        $empleadoGeneral->permissions()->sync($empleadoPermissions);

        $this->command->info('Permisos asignados a roles exitosamente.');
    }
}

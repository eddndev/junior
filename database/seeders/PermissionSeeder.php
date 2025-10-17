<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Módulo Núcleo - Gestión de Usuarios
            ['name' => 'Gestionar Usuarios', 'slug' => 'gestionar-usuarios', 'module' => 'nucleo', 'description' => 'Crear, editar y desactivar usuarios'],
            ['name' => 'Asignar Roles', 'slug' => 'asignar-roles', 'module' => 'nucleo', 'description' => 'Asignar y remover roles a usuarios'],
            ['name' => 'Ver Usuarios', 'slug' => 'ver-usuarios', 'module' => 'nucleo', 'description' => 'Ver lista de usuarios del sistema'],

            // Módulo Núcleo - Gestión de Áreas
            ['name' => 'Gestionar Áreas', 'slug' => 'gestionar-areas', 'module' => 'nucleo', 'description' => 'Crear y editar áreas/departamentos'],
            ['name' => 'Ver Áreas', 'slug' => 'ver-areas', 'module' => 'nucleo', 'description' => 'Ver todas las áreas del sistema'],

            // Módulo Tareas - Gestión de Tareas
            ['name' => 'Crear Tareas', 'slug' => 'crear-tareas', 'module' => 'tareas', 'description' => 'Crear nuevas tareas'],
            ['name' => 'Editar Tareas', 'slug' => 'editar-tareas', 'module' => 'tareas', 'description' => 'Editar tareas existentes'],
            ['name' => 'Eliminar Tareas', 'slug' => 'eliminar-tareas', 'module' => 'tareas', 'description' => 'Eliminar tareas'],
            ['name' => 'Asignar Tareas', 'slug' => 'asignar-tareas', 'module' => 'tareas', 'description' => 'Asignar tareas a usuarios'],
            ['name' => 'Ver Tareas', 'slug' => 'ver-tareas', 'module' => 'tareas', 'description' => 'Ver todas las tareas'],
            ['name' => 'Completar Tareas', 'slug' => 'completar-tareas', 'module' => 'tareas', 'description' => 'Marcar tareas como completadas'],

            // Módulo Tareas - Bitácora de Equipo
            ['name' => 'Crear Entradas de Bitácora', 'slug' => 'crear-bitacora', 'module' => 'tareas', 'description' => 'Crear entradas en la bitácora del equipo'],
            ['name' => 'Editar Bitácora', 'slug' => 'editar-bitacora', 'module' => 'tareas', 'description' => 'Editar entradas de bitácora'],
            ['name' => 'Eliminar Bitácora', 'slug' => 'eliminar-bitacora', 'module' => 'tareas', 'description' => 'Eliminar entradas de bitácora'],
            ['name' => 'Ver Bitácora', 'slug' => 'ver-bitacora', 'module' => 'tareas', 'description' => 'Ver bitácora del equipo'],

            // Módulo Tareas - Calendario
            ['name' => 'Gestionar Disponibilidad', 'slug' => 'gestionar-disponibilidad', 'module' => 'tareas', 'description' => 'Gestionar calendario de disponibilidad personal'],
            ['name' => 'Ver Disponibilidad de Equipo', 'slug' => 'ver-disponibilidad-equipo', 'module' => 'tareas', 'description' => 'Ver disponibilidad de todo el equipo'],

            // Módulo Comunicación
            ['name' => 'Enviar Notificaciones', 'slug' => 'enviar-notificaciones', 'module' => 'comunicacion', 'description' => 'Enviar notificaciones a usuarios o áreas'],
            ['name' => 'Enviar Mensajes Generales', 'slug' => 'enviar-mensajes-generales', 'module' => 'comunicacion', 'description' => 'Enviar mensajes generales a toda la empresa'],
            ['name' => 'Ver Notificaciones', 'slug' => 'ver-notificaciones', 'module' => 'comunicacion', 'description' => 'Ver notificaciones propias'],

            // Módulo Trazabilidad
            ['name' => 'Ver Panel de Trazabilidad', 'slug' => 'ver-trazabilidad', 'module' => 'comunicacion', 'description' => 'Acceder al panel de auditoría y trazabilidad'],
            ['name' => 'Filtrar Trazabilidad', 'slug' => 'filtrar-trazabilidad', 'module' => 'comunicacion', 'description' => 'Filtrar registros de auditoría por usuario/acción'],

            // Módulo Finanzas - Clientes
            ['name' => 'Gestionar Clientes', 'slug' => 'gestionar-clientes', 'module' => 'finanzas', 'description' => 'Crear, editar y desactivar clientes'],
            ['name' => 'Ver Clientes', 'slug' => 'ver-clientes', 'module' => 'finanzas', 'description' => 'Ver catálogo de clientes'],

            // Módulo Finanzas - Catálogo de Costos
            ['name' => 'Gestionar Catálogo de Costos', 'slug' => 'gestionar-catalogo-costos', 'module' => 'finanzas', 'description' => 'Crear y editar items del catálogo de costos'],
            ['name' => 'Ver Catálogo de Costos', 'slug' => 'ver-catalogo-costos', 'module' => 'finanzas', 'description' => 'Ver catálogo de costos'],

            // Módulo Finanzas - Presupuestos
            ['name' => 'Crear Presupuestos', 'slug' => 'crear-presupuestos', 'module' => 'finanzas', 'description' => 'Crear nuevos presupuestos'],
            ['name' => 'Editar Presupuestos', 'slug' => 'editar-presupuestos', 'module' => 'finanzas', 'description' => 'Editar presupuestos existentes'],
            ['name' => 'Ver Presupuestos', 'slug' => 'ver-presupuestos', 'module' => 'finanzas', 'description' => 'Ver presupuestos'],
            ['name' => 'Registrar Gastos', 'slug' => 'registrar-gastos', 'module' => 'finanzas', 'description' => 'Registrar gastos contra presupuestos'],
            ['name' => 'Ver Reportes de Presupuestos', 'slug' => 'ver-reportes-presupuestos', 'module' => 'finanzas', 'description' => 'Ver reportes de consumo de presupuestos'],

            // Módulo Finanzas - Cotizaciones
            ['name' => 'Crear Cotizaciones', 'slug' => 'crear-cotizaciones', 'module' => 'finanzas', 'description' => 'Crear nuevas cotizaciones para clientes'],
            ['name' => 'Editar Cotizaciones', 'slug' => 'editar-cotizaciones', 'module' => 'finanzas', 'description' => 'Editar cotizaciones existentes'],
            ['name' => 'Ver Cotizaciones', 'slug' => 'ver-cotizaciones', 'module' => 'finanzas', 'description' => 'Ver cotizaciones'],
            ['name' => 'Usar Calculadora de Costos', 'slug' => 'usar-calculadora-costos', 'module' => 'finanzas', 'description' => 'Usar la calculadora de costos para simulaciones'],

            // Módulo Marketing - Campañas
            ['name' => 'Crear Campañas', 'slug' => 'crear-campanas', 'module' => 'marketing', 'description' => 'Crear nuevas campañas de marketing'],
            ['name' => 'Editar Campañas', 'slug' => 'editar-campanas', 'module' => 'marketing', 'description' => 'Editar campañas existentes'],
            ['name' => 'Ver Campañas', 'slug' => 'ver-campanas', 'module' => 'marketing', 'description' => 'Ver campañas de marketing'],

            // Módulo Marketing - Tareas de Marketing
            ['name' => 'Crear Tareas de Marketing', 'slug' => 'crear-tareas-marketing', 'module' => 'marketing', 'description' => 'Crear tareas dentro de campañas'],
            ['name' => 'Asignar Tareas de Marketing', 'slug' => 'asignar-tareas-marketing', 'module' => 'marketing', 'description' => 'Asignar tareas de marketing a usuarios'],
            ['name' => 'Ver Tareas de Marketing', 'slug' => 'ver-tareas-marketing', 'module' => 'marketing', 'description' => 'Ver tareas de marketing'],

            // Módulo Marketing - Leads
            ['name' => 'Registrar Leads', 'slug' => 'registrar-leads', 'module' => 'marketing', 'description' => 'Registrar nuevos leads de campañas'],
            ['name' => 'Editar Leads', 'slug' => 'editar-leads', 'module' => 'marketing', 'description' => 'Editar información de leads'],
            ['name' => 'Ver Leads', 'slug' => 'ver-leads', 'module' => 'marketing', 'description' => 'Ver leads generados'],
            ['name' => 'Ver Reportes de Marketing', 'slug' => 'ver-reportes-marketing', 'module' => 'marketing', 'description' => 'Ver reportes de campañas y leads'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        $this->command->info('Permisos creados exitosamente.');
    }
}

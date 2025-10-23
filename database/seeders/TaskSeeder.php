<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Task;
use App\Models\User;
use App\Models\TaskAssignment;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get areas
        $produccion = Area::where('slug', 'produccion')->first();
        $marketing = Area::where('slug', 'marketing')->first();
        $finanzas = Area::where('slug', 'finanzas')->first();
        $desarrollo = Area::where('slug', 'desarrollo')->first();

        // Get users
        $director = User::where('email', 'director@junior.com')->first();
        $users = User::where('email', '!=', 'director@junior.com')->get();

        if (!$director || $users->isEmpty()) {
            $this->command->warn('⚠️  No hay usuarios. Ejecuta UserSeeder primero.');
            return;
        }

        // ========================================
        // TAREAS DE PRODUCCIÓN
        // ========================================

        $taskProduccion1 = Task::create([
            'title' => 'Planificar sesión de fotos de producto',
            'description' => 'Coordinar con el equipo de fotografía para la sesión de fotos del nuevo catálogo de productos. Incluye selección de locación, equipo necesario y modelos.',
            'area_id' => $produccion->id,
            'priority' => 'high',
            'status' => 'in_progress',
            'due_date' => now()->addDays(5),
        ]);

        // Asignar 2 usuarios a esta tarea (polimórfico)
        $usersProduccion = User::whereHas('areas', function ($q) use ($produccion) {
            $q->where('areas.id', $produccion->id);
        })->take(2)->get();

        foreach ($usersProduccion as $user) {
            TaskAssignment::create([
                'assignable_type' => Task::class,
                'assignable_id' => $taskProduccion1->id,
                'user_id' => $user->id,
                'assigned_at' => now(),
            ]);
        }

        // Tarea dependiente (parent_task_id)
        $taskProduccion2 = Task::create([
            'title' => 'Revisar calidad de edición de video promocional',
            'description' => 'El equipo de edición ha entregado el primer borrador del video. Revisar y dar feedback antes de la sesión de fotos.',
            'area_id' => $produccion->id,
            'parent_task_id' => $taskProduccion1->id, // Depende de la tarea anterior
            'priority' => 'medium',
            'status' => 'pending',
            'due_date' => now()->addDays(3),
        ]);

        // Asignar 1 usuario
        if ($usersProduccion->count() > 0) {
            TaskAssignment::create([
                'assignable_type' => Task::class,
                'assignable_id' => $taskProduccion2->id,
                'user_id' => $usersProduccion->first()->id,
                'assigned_at' => now(),
            ]);
        }

        Task::create([
            'title' => 'Preparar equipos para filmación externa',
            'description' => 'Verificar y empacar todo el equipo necesario para la filmación del próximo martes.',
            'area_id' => $produccion->id,
            'priority' => 'high',
            'status' => 'pending',
            'due_date' => now()->addDays(2),
        ]);

        // ========================================
        // TAREAS DE MARKETING
        // ========================================

        $taskMarketing1 = Task::create([
            'title' => 'Diseñar campaña de email marketing para Black Friday',
            'description' => 'Crear estrategia de email marketing para la campaña de Black Friday. Incluye diseño de plantillas, segmentación de audiencia y calendario de envíos.',
            'area_id' => $marketing->id,
            'priority' => 'critical',
            'status' => 'in_progress',
            'due_date' => now()->addDays(7),
        ]);

        $usersMarketing = User::whereHas('areas', function ($q) use ($marketing) {
            $q->where('areas.id', $marketing->id);
        })->take(2)->get();

        foreach ($usersMarketing as $user) {
            TaskAssignment::create([
                'assignable_type' => Task::class,
                'assignable_id' => $taskMarketing1->id,
                'user_id' => $user->id,
                'assigned_at' => now(),
            ]);
        }

        Task::create([
            'title' => 'Actualizar contenido de redes sociales',
            'description' => 'Publicar 10 posts en Instagram y Facebook sobre los nuevos productos de la temporada.',
            'area_id' => $marketing->id,
            'priority' => 'medium',
            'status' => 'pending',
            'due_date' => now()->addDays(4),
        ]);

        // Tarea atrasada
        $taskMarketingOverdue = Task::create([
            'title' => 'Entregar manual de identidad corporativa',
            'description' => 'Documento final del manual de marca con guidelines de uso de logo, colores y tipografías.',
            'area_id' => $marketing->id,
            'priority' => 'high',
            'status' => 'pending',
            'due_date' => now()->subDays(3), // Atrasada 3 días
        ]);

        if ($usersMarketing->count() > 0) {
            TaskAssignment::create([
                'assignable_type' => Task::class,
                'assignable_id' => $taskMarketingOverdue->id,
                'user_id' => $usersMarketing->first()->id,
                'assigned_at' => now()->subDays(10),
            ]);
        }

        // ========================================
        // TAREAS DE FINANZAS
        // ========================================

        $taskFinanzas1 = Task::create([
            'title' => 'Preparar reporte financiero Q4',
            'description' => 'Consolidar estados financieros del cuarto trimestre para presentación a dirección.',
            'area_id' => $finanzas->id,
            'priority' => 'high',
            'status' => 'in_progress',
            'due_date' => now()->addDays(10),
        ]);

        $usersFinanzas = User::whereHas('areas', function ($q) use ($finanzas) {
            $q->where('areas.id', $finanzas->id);
        })->first();

        if ($usersFinanzas) {
            TaskAssignment::create([
                'assignable_type' => Task::class,
                'assignable_id' => $taskFinanzas1->id,
                'user_id' => $usersFinanzas->id,
                'assigned_at' => now(),
            ]);
        }

        Task::create([
            'title' => 'Revisar presupuesto de campaña de marketing',
            'description' => 'Validar costos proyectados de la campaña Black Friday y aprobar desembolsos.',
            'area_id' => $finanzas->id,
            'priority' => 'high',
            'status' => 'pending',
            'due_date' => now()->addDays(5),
        ]);

        // Tarea completada
        Task::create([
            'title' => 'Aprobar presupuesto anual 2025',
            'description' => 'Revisión y aprobación del presupuesto operativo del próximo año fiscal.',
            'area_id' => $finanzas->id,
            'priority' => 'critical',
            'status' => 'completed',
            'due_date' => now()->subDays(15),
            'completed_at' => now()->subDays(12),
        ]);

        // ========================================
        // TAREAS DE DESARROLLO
        // ========================================

        if ($desarrollo) {
            $taskDesarrollo1 = Task::create([
                'title' => 'Implementar módulo de tareas y colaboración',
                'description' => 'Desarrollar sistema completo de gestión de tareas jerárquicas con asignación polimórfica.',
                'area_id' => $desarrollo->id,
                'priority' => 'critical',
                'status' => 'in_progress',
                'due_date' => now()->addDays(14),
            ]);

            // Tarea dependiente
            Task::create([
                'title' => 'Escribir tests para módulo de tareas',
                'description' => 'Crear tests unitarios y de integración para el módulo de tareas.',
                'area_id' => $desarrollo->id,
                'parent_task_id' => $taskDesarrollo1->id,
                'priority' => 'high',
                'status' => 'pending',
                'due_date' => now()->addDays(16),
            ]);

            Task::create([
                'title' => 'Optimizar queries de base de datos',
                'description' => 'Agregar índices y optimizar queries N+1 en módulos de usuarios y áreas.',
                'area_id' => $desarrollo->id,
                'priority' => 'medium',
                'status' => 'completed',
                'due_date' => now()->subDays(5),
                'completed_at' => now()->subDays(2),
            ]);
        }

        // ========================================
        // TAREAS SIN ASIGNAR (Sin área específica)
        // ========================================

        Task::create([
            'title' => 'Reunión general de equipo - Retrospectiva Q3',
            'description' => 'Reunión mensual para revisar logros del trimestre y planificar objetivos del siguiente periodo.',
            'area_id' => $produccion->id, // Por defecto en Producción
            'priority' => 'medium',
            'status' => 'pending',
            'due_date' => now()->addDays(8),
        ]);

        $this->command->info('✅ TaskSeeder completado: ' . Task::count() . ' tareas creadas.');
        $this->command->info('✅ Asignaciones polimórficas: ' . TaskAssignment::count() . ' asignaciones creadas.');
    }
}
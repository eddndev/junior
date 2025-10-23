<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\Subtask;
use App\Models\TaskAssignment;
use App\Models\User;
use Illuminate\Database\Seeder;

class SubtaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Verificar que existan tareas
        if (Task::count() === 0) {
            $this->command->warn('⚠️  No hay tareas. Ejecuta TaskSeeder primero.');
            return;
        }

        // ========================================
        // SUBTAREAS: "Planificar sesión de fotos de producto"
        // ========================================

        $taskFotos = Task::where('title', 'like', '%sesión de fotos%')->first();

        if ($taskFotos) {
            $subtask1 = Subtask::create([
                'task_id' => $taskFotos->id,
                'title' => 'Reservar estudio fotográfico',
                'description' => 'Contactar con Estudio XYZ y reservar para el viernes próximo',
                'status' => 'completed',
                'order' => 1,
                'completed_at' => now()->subHours(2),
            ]);

            $subtask2 = Subtask::create([
                'task_id' => $taskFotos->id,
                'title' => 'Preparar lista de productos a fotografiar',
                'description' => 'Listado de 50 productos prioritarios del nuevo catálogo',
                'status' => 'in_progress',
                'order' => 2,
            ]);

            // Asignar usuarios a subtareas (polimórfico)
            $userProduccion = User::whereHas('areas', function ($q) use ($taskFotos) {
                $q->where('areas.id', $taskFotos->area_id);
            })->first();

            if ($userProduccion) {
                TaskAssignment::create([
                    'assignable_type' => Subtask::class,
                    'assignable_id' => $subtask2->id,
                    'user_id' => $userProduccion->id,
                    'assigned_at' => now(),
                ]);
            }

            Subtask::create([
                'task_id' => $taskFotos->id,
                'title' => 'Contratar modelos profesionales',
                'description' => 'Buscar y contratar 2 modelos para la sesión',
                'status' => 'pending',
                'order' => 3,
            ]);
        }

        // ========================================
        // SUBTAREAS: "Diseñar campaña de email marketing para Black Friday"
        // ========================================

        $taskEmail = Task::where('title', 'like', '%email marketing%Black Friday%')->first();

        if ($taskEmail) {
            $subtask1 = Subtask::create([
                'task_id' => $taskEmail->id,
                'title' => 'Diseñar plantilla de email principal',
                'description' => 'Diseño responsivo con imágenes de productos destacados',
                'status' => 'in_progress',
                'order' => 1,
            ]);

            $subtask2 = Subtask::create([
                'task_id' => $taskEmail->id,
                'title' => 'Redactar copy para emails',
                'description' => 'Textos persuasivos y urgencia para Black Friday',
                'status' => 'pending',
                'order' => 2,
            ]);

            $subtask3 = Subtask::create([
                'task_id' => $taskEmail->id,
                'title' => 'Segmentar audiencia en 4 grupos',
                'description' => 'VIP, recurrentes, nuevos prospectos y carrito abandonado',
                'status' => 'completed',
                'order' => 3,
                'completed_at' => now()->subDays(1),
            ]);

            // Asignar usuarios a subtareas
            $usersMarketing = User::whereHas('areas', function ($q) use ($taskEmail) {
                $q->where('areas.id', $taskEmail->area_id);
            })->take(2)->get();

            if ($usersMarketing->count() > 0) {
                TaskAssignment::create([
                    'assignable_type' => Subtask::class,
                    'assignable_id' => $subtask1->id,
                    'user_id' => $usersMarketing->first()->id,
                    'assigned_at' => now(),
                ]);

                if ($usersMarketing->count() > 1) {
                    TaskAssignment::create([
                        'assignable_type' => Subtask::class,
                        'assignable_id' => $subtask2->id,
                        'user_id' => $usersMarketing->last()->id,
                        'assigned_at' => now(),
                    ]);
                }
            }
        }

        // ========================================
        // SUBTAREAS: "Preparar reporte financiero Q4"
        // ========================================

        $taskReporte = Task::where('title', 'like', '%reporte financiero Q4%')->first();

        if ($taskReporte) {
            Subtask::create([
                'task_id' => $taskReporte->id,
                'title' => 'Consolidar estados de septiembre',
                'description' => 'Recopilar y validar datos financieros de septiembre',
                'status' => 'completed',
                'order' => 1,
                'completed_at' => now()->subDays(5),
            ]);

            Subtask::create([
                'task_id' => $taskReporte->id,
                'title' => 'Consolidar estados de octubre',
                'description' => 'Recopilar y validar datos financieros de octubre',
                'status' => 'completed',
                'order' => 2,
                'completed_at' => now()->subDays(3),
            ]);

            Subtask::create([
                'task_id' => $taskReporte->id,
                'title' => 'Consolidar estados de noviembre',
                'description' => 'Recopilar y validar datos financieros de noviembre',
                'status' => 'in_progress',
                'order' => 3,
            ]);

            Subtask::create([
                'task_id' => $taskReporte->id,
                'title' => 'Preparar análisis de gastos operativos',
                'description' => 'Análisis detallado de gastos vs presupuesto',
                'status' => 'pending',
                'order' => 4,
            ]);

            Subtask::create([
                'task_id' => $taskReporte->id,
                'title' => 'Crear presentación ejecutiva',
                'description' => 'Slides para presentación a la junta directiva',
                'status' => 'pending',
                'order' => 5,
            ]);
        }

        // ========================================
        // SUBTAREAS: "Implementar módulo de tareas y colaboración"
        // ========================================

        $taskModulo = Task::where('title', 'like', '%módulo de tareas y colaboración%')->first();

        if ($taskModulo) {
            Subtask::create([
                'task_id' => $taskModulo->id,
                'title' => 'Crear migraciones de base de datos',
                'description' => 'Tablas tasks, subtasks y task_assignments',
                'status' => 'completed',
                'order' => 1,
                'completed_at' => now()->subDays(3),
            ]);

            Subtask::create([
                'task_id' => $taskModulo->id,
                'title' => 'Implementar modelos Eloquent',
                'description' => 'Task, Subtask y TaskAssignment con relaciones',
                'status' => 'completed',
                'order' => 2,
                'completed_at' => now()->subDays(2),
            ]);

            Subtask::create([
                'task_id' => $taskModulo->id,
                'title' => 'Crear seeders con datos de demostración',
                'description' => 'TaskSeeder y SubtaskSeeder con datos realistas',
                'status' => 'in_progress',
                'order' => 3,
            ]);

            Subtask::create([
                'task_id' => $taskModulo->id,
                'title' => 'Desarrollar controladores y rutas',
                'description' => 'TaskController, SubtaskController y rutas protegidas',
                'status' => 'pending',
                'order' => 4,
            ]);

            Subtask::create([
                'task_id' => $taskModulo->id,
                'title' => 'Crear vistas Blade con componentes',
                'description' => 'Index, create, edit, show usando componentes del Sprint 1',
                'status' => 'pending',
                'order' => 5,
            ]);
        }

        $this->command->info('✅ SubtaskSeeder completado: ' . Subtask::count() . ' subtareas creadas.');

        // Contar asignaciones de subtareas
        $subtaskAssignments = TaskAssignment::where('assignable_type', Subtask::class)->count();
        $this->command->info('✅ Asignaciones de subtareas: ' . $subtaskAssignments . ' asignaciones creadas.');
    }
}
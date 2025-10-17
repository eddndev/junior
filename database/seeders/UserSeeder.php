<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener roles y áreas
        $direccionGeneralRole = Role::where('slug', 'direccion-general')->first();
        $adminRRHHRole = Role::where('slug', 'admin-rrhh')->first();
        $gestorFinancieroRole = Role::where('slug', 'gestor-financiero')->first();
        $gestorMarketingRole = Role::where('slug', 'gestor-marketing')->first();
        $miembroProduccionRole = Role::where('slug', 'miembro-produccion')->first();
        $empleadoGeneralRole = Role::where('slug', 'empleado-general')->first();
        $directorAreaRole = Role::where('slug', 'director-area')->first();

        $direccionArea = Area::where('slug', 'direccion-general')->first();
        $rrhhArea = Area::where('slug', 'recursos-humanos')->first();
        $finanzasArea = Area::where('slug', 'finanzas')->first();
        $marketingArea = Area::where('slug', 'marketing')->first();
        $produccionArea = Area::where('slug', 'produccion')->first();
        $tecnologiaArea = Area::where('slug', 'tecnologia')->first();

        // Usuario 1: Director General
        $directorGeneral = User::create([
            'name' => 'Carlos Mendoza',
            'email' => 'director@junior.com',
            'password' => Hash::make('password'),
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $directorGeneral->roles()->attach($direccionGeneralRole->id, ['area_id' => $direccionArea->id]);
        $directorGeneral->areas()->attach($direccionArea->id);

        // Usuario 2: Administrador RRHH
        $adminRRHH = User::create([
            'name' => 'Ana García',
            'email' => 'rrhh@junior.com',
            'password' => Hash::make('password'),
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $adminRRHH->roles()->attach($adminRRHHRole->id, ['area_id' => $rrhhArea->id]);
        $adminRRHH->areas()->attach($rrhhArea->id);

        // Usuario 3: Gestor Financiero
        $gestorFinanzas = User::create([
            'name' => 'Roberto López',
            'email' => 'finanzas@junior.com',
            'password' => Hash::make('password'),
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $gestorFinanzas->roles()->attach($gestorFinancieroRole->id, ['area_id' => $finanzasArea->id]);
        $gestorFinanzas->areas()->attach($finanzasArea->id);

        // Usuario 4: Gestor de Marketing
        $gestorMarketing = User::create([
            'name' => 'Laura Martínez',
            'email' => 'marketing@junior.com',
            'password' => Hash::make('password'),
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $gestorMarketing->roles()->attach($gestorMarketingRole->id, ['area_id' => $marketingArea->id]);
        $gestorMarketing->areas()->attach($marketingArea->id);

        // Usuario 5: Director de Producción
        $directorProduccion = User::create([
            'name' => 'Miguel Torres',
            'email' => 'produccion@junior.com',
            'password' => Hash::make('password'),
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $directorProduccion->roles()->attach($directorAreaRole->id, ['area_id' => $produccionArea->id]);
        $directorProduccion->areas()->attach($produccionArea->id);

        // Usuario 6: Miembro de Producción 1
        $miembroProduccion1 = User::create([
            'name' => 'Pedro Ramírez',
            'email' => 'pedro.produccion@junior.com',
            'password' => Hash::make('password'),
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $miembroProduccion1->roles()->attach($miembroProduccionRole->id, ['area_id' => $produccionArea->id]);
        $miembroProduccion1->areas()->attach($produccionArea->id);

        // Usuario 7: Miembro de Producción 2
        $miembroProduccion2 = User::create([
            'name' => 'Sofía González',
            'email' => 'sofia.produccion@junior.com',
            'password' => Hash::make('password'),
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $miembroProduccion2->roles()->attach($miembroProduccionRole->id, ['area_id' => $produccionArea->id]);
        $miembroProduccion2->areas()->attach($produccionArea->id);

        // Usuario 8: Empleado General de Marketing
        $empleadoMarketing = User::create([
            'name' => 'Javier Hernández',
            'email' => 'javier.marketing@junior.com',
            'password' => Hash::make('password'),
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $empleadoMarketing->roles()->attach($empleadoGeneralRole->id, ['area_id' => $marketingArea->id]);
        $empleadoMarketing->areas()->attach($marketingArea->id);

        // Usuario 9: Empleado General de Tecnología (multi-área)
        $empleadoTecnologia = User::create([
            'name' => 'María Fernández',
            'email' => 'maria.tech@junior.com',
            'password' => Hash::make('password'),
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        // Este usuario pertenece a dos áreas: Tecnología y Producción
        $empleadoTecnologia->roles()->attach($empleadoGeneralRole->id, ['area_id' => $tecnologiaArea->id]);
        $empleadoTecnologia->roles()->attach($empleadoGeneralRole->id, ['area_id' => $produccionArea->id]);
        $empleadoTecnologia->areas()->attach([$tecnologiaArea->id, $produccionArea->id]);

        $this->command->info('Usuarios de ejemplo creados exitosamente.');
        $this->command->info('Credenciales por defecto: email / password');
    }
}

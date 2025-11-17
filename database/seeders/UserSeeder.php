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
        // Obtener roles
        $direccionGeneralRole = Role::where('slug', 'direccion-general')->first();
        $directorAreaRole = Role::where('slug', 'director-area')->first();
        $gerenteRole = Role::where('slug', 'gerente')->first();
        $adminRRHHRole = Role::where('slug', 'admin-rrhh')->first();
        $empleadoRole = Role::where('slug', 'empleado')->first();
        $supervisorRole = Role::where('slug', 'supervisor')->first();

        // Obtener áreas
        $generalArea = Area::where('slug', 'general')->first();
        $rrhhArea = Area::where('slug', 'recursos-humanos')->first();
        $finanzasArea = Area::where('slug', 'finanzas')->first();
        $marketingArea = Area::where('slug', 'marketing')->first();
        $produccionArea = Area::where('slug', 'produccion')->first();

        $password = Hash::make('Emprendedores2025');

        // ===================================
        // Usuario 1: Director General (Moises)
        // ===================================
        $moises = User::create([
            'name' => 'Moises Montes de Oca Martínez',
            'email' => 'moy.mdo.2@gmail.com',
            'password' => $password,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        // Rol de Dirección General (acceso total)
        $moises->roles()->attach($direccionGeneralRole->id, ['area_id' => $generalArea->id]);
        // Director de CADA área
        $moises->roles()->attach($directorAreaRole->id, ['area_id' => $generalArea->id]);
        $moises->roles()->attach($directorAreaRole->id, ['area_id' => $rrhhArea->id]);
        $moises->roles()->attach($directorAreaRole->id, ['area_id' => $finanzasArea->id]);
        $moises->roles()->attach($directorAreaRole->id, ['area_id' => $marketingArea->id]);
        $moises->roles()->attach($directorAreaRole->id, ['area_id' => $produccionArea->id]);
        // Pertenece a todas las áreas
        $moises->areas()->attach([
            $generalArea->id,
            $rrhhArea->id,
            $finanzasArea->id,
            $marketingArea->id,
            $produccionArea->id,
        ]);

        // ===================================
        // Usuario 2: Director de RRHH (Tania)
        // ===================================
        $tania = User::create([
            'name' => 'Tania Montserrat García Díaz',
            'email' => 'tgarciad1900@gmail.com',
            'password' => $password,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $tania->roles()->attach($directorAreaRole->id, ['area_id' => $rrhhArea->id]);
        $tania->roles()->attach($adminRRHHRole->id, ['area_id' => $rrhhArea->id]);
        $tania->roles()->attach($empleadoRole->id, ['area_id' => $generalArea->id]);
        $tania->areas()->attach([$rrhhArea->id, $generalArea->id]);

        // ===================================
        // Usuario 3: Gerente de RRHH (Avril)
        // ===================================
        $avril = User::create([
            'name' => 'Avril Paola Mejía Avianeda',
            'email' => 'kaydavi04@gmail.com',
            'password' => $password,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $avril->roles()->attach($gerenteRole->id, ['area_id' => $rrhhArea->id]);
        $avril->roles()->attach($adminRRHHRole->id, ['area_id' => $rrhhArea->id]);
        $avril->roles()->attach($empleadoRole->id, ['area_id' => $generalArea->id]);
        $avril->areas()->attach([$rrhhArea->id, $generalArea->id]);

        // ===================================
        // Usuario 4: Director de Finanzas (Alondra)
        // ===================================
        $alondra = User::create([
            'name' => 'Alondra Yuzeth Ayala Hernández',
            'email' => 'alondra.ayala.her@gmail.com',
            'password' => $password,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $alondra->roles()->attach($directorAreaRole->id, ['area_id' => $finanzasArea->id]);
        $alondra->roles()->attach($empleadoRole->id, ['area_id' => $generalArea->id]);
        $alondra->areas()->attach([$finanzasArea->id, $generalArea->id]);

        // ===================================
        // Usuario 5: Gerente de Finanzas (Andres)
        // ===================================
        $andres = User::create([
            'name' => 'Andres Fernando Ceniceros Romero',
            'email' => 'andresbkhm3@gmail.com',
            'password' => $password,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $andres->roles()->attach($gerenteRole->id, ['area_id' => $finanzasArea->id]);
        $andres->roles()->attach($empleadoRole->id, ['area_id' => $generalArea->id]);
        $andres->areas()->attach([$finanzasArea->id, $generalArea->id]);

        // ===================================
        // Usuario 6: Empleado de Finanzas (Kevin)
        // ===================================
        $kevin = User::create([
            'name' => 'Kevin Uriel Gómez Martínez',
            'email' => 'kevinurielgomezmartinez@gmail.com',
            'password' => $password,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $kevin->roles()->attach($empleadoRole->id, ['area_id' => $finanzasArea->id]);
        $kevin->roles()->attach($empleadoRole->id, ['area_id' => $generalArea->id]);
        $kevin->areas()->attach([$finanzasArea->id, $generalArea->id]);

        // ===================================
        // Usuario 7: Director de Producción (Jonathan)
        // ===================================
        $jonathan = User::create([
            'name' => 'Jonathan Magdiel García Martínez',
            'email' => 'jony.gar1916@gmail.com',
            'password' => $password,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $jonathan->roles()->attach($directorAreaRole->id, ['area_id' => $produccionArea->id]);
        $jonathan->roles()->attach($empleadoRole->id, ['area_id' => $generalArea->id]);
        $jonathan->areas()->attach([$produccionArea->id, $generalArea->id]);

        // ===================================
        // Usuario 8: Gerente de Producción (Lesly)
        // ===================================
        $lesly = User::create([
            'name' => 'Lesly Alexa Martinez Ramirez',
            'email' => 'lamr5765@gmail.com',
            'password' => $password,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $lesly->roles()->attach($gerenteRole->id, ['area_id' => $produccionArea->id]);
        $lesly->roles()->attach($empleadoRole->id, ['area_id' => $generalArea->id]);
        $lesly->areas()->attach([$produccionArea->id, $generalArea->id]);

        // ===================================
        // Usuario 9: Empleado de Producción (Eduardo)
        // ===================================
        $eduardo = User::create([
            'name' => 'Eduardo Alonso Sánchez',
            'email' => 'eddndev@gmail.com',
            'password' => $password,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $eduardo->roles()->attach($empleadoRole->id, ['area_id' => $produccionArea->id]);
        $eduardo->roles()->attach($empleadoRole->id, ['area_id' => $generalArea->id]);
        $eduardo->areas()->attach([$produccionArea->id, $generalArea->id]);

        // ===================================
        // Usuario 10: Empleado de Producción (Edén)
        // ===================================
        $eden = User::create([
            'name' => 'Edén Uribe Sánchez',
            'email' => 'edenesbueno@gmail.com',
            'password' => $password,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $eden->roles()->attach($empleadoRole->id, ['area_id' => $produccionArea->id]);
        $eden->roles()->attach($empleadoRole->id, ['area_id' => $generalArea->id]);
        $eden->areas()->attach([$produccionArea->id, $generalArea->id]);

        // ===================================
        // Usuario 11: Empleado de Producción (Francisco)
        // ===================================
        $francisco = User::create([
            'name' => 'Francisco Isaac Ordoñez Pedrero',
            'email' => 'ofranciscoisaac@yahoo.com',
            'password' => $password,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $francisco->roles()->attach($empleadoRole->id, ['area_id' => $produccionArea->id]);
        $francisco->roles()->attach($empleadoRole->id, ['area_id' => $generalArea->id]);
        $francisco->areas()->attach([$produccionArea->id, $generalArea->id]);

        // ===================================
        // Usuario 12: Director de Marketing (Yolatl)
        // ===================================
        $yolatl = User::create([
            'name' => 'Yolatl Hidalgo Salmeron',
            'email' => 'yolatito.hgo@gmail.com',
            'password' => $password,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $yolatl->roles()->attach($directorAreaRole->id, ['area_id' => $marketingArea->id]);
        $yolatl->roles()->attach($empleadoRole->id, ['area_id' => $generalArea->id]);
        $yolatl->areas()->attach([$marketingArea->id, $generalArea->id]);

        // ===================================
        // Usuario 13: Gerente de Marketing (Melanie)
        // ===================================
        $melanie = User::create([
            'name' => 'Melanie Jocelyn Duran Tovar',
            'email' => 'melaniejocelynd@gmail.com',
            'password' => $password,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $melanie->roles()->attach($gerenteRole->id, ['area_id' => $marketingArea->id]);
        $melanie->roles()->attach($empleadoRole->id, ['area_id' => $generalArea->id]);
        $melanie->areas()->attach([$marketingArea->id, $generalArea->id]);

        // ===================================
        // Usuario 14: Empleado de Marketing (Gelhy)
        // ===================================
        $gelhy = User::create([
            'name' => 'Gelhy Raquel Martinez Ramírez',
            'email' => 'mgelhyraquelraquel@gmail.com',
            'password' => $password,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $gelhy->roles()->attach($empleadoRole->id, ['area_id' => $marketingArea->id]);
        $gelhy->roles()->attach($empleadoRole->id, ['area_id' => $generalArea->id]);
        $gelhy->areas()->attach([$marketingArea->id, $generalArea->id]);

        // ===================================
        // Usuario 15: Empleado de Marketing (Samantha)
        // ===================================
        $samantha = User::create([
            'name' => 'Samantha Salazar Pérez',
            'email' => 'salazarsamantha49@gmail.com',
            'password' => $password,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $samantha->roles()->attach($empleadoRole->id, ['area_id' => $marketingArea->id]);
        $samantha->roles()->attach($empleadoRole->id, ['area_id' => $generalArea->id]);
        $samantha->areas()->attach([$marketingArea->id, $generalArea->id]);

        // ===================================
        // Usuario 16: Empleado de Marketing (Samuel)
        // ===================================
        $samuel = User::create([
            'name' => 'Samuel Imanol García Dueñas',
            'email' => 'imanolgf12309@gmail.com',
            'password' => $password,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $samuel->roles()->attach($empleadoRole->id, ['area_id' => $marketingArea->id]);
        $samuel->roles()->attach($empleadoRole->id, ['area_id' => $generalArea->id]);
        $samuel->areas()->attach([$marketingArea->id, $generalArea->id]);

        // ===================================
        // Usuario 17: Supervisor (David)
        // ===================================
        $david = User::create([
            'name' => 'David Correa Coyac',
            'email' => 'dcoyac@outlook.com',
            'password' => $password,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        // Rol de Supervisor (solo lectura global)
        $david->roles()->attach($supervisorRole->id, ['area_id' => $generalArea->id]);
        // Pertenece solo al área General
        $david->areas()->attach([$generalArea->id]);

        $this->command->info('17 usuarios reales creados exitosamente.');
        $this->command->info('Contraseña por defecto: Emprendedores2025');
    }
}

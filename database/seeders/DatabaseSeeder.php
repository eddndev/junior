<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Ejecutar seeders en orden de dependencias
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            RolePermissionSeeder::class,
            AreaSeeder::class,
            UserSeeder::class,
        ]);

        $this->command->info('');
        $this->command->info('=====================================================');
        $this->command->info('  Base de datos poblada exitosamente');
        $this->command->info('=====================================================');
        $this->command->info('');
        $this->command->info('Usuarios de ejemplo creados:');
        $this->command->info('  - director@junior.com (Dirección General)');
        $this->command->info('  - rrhh@junior.com (Admin RRHH)');
        $this->command->info('  - finanzas@junior.com (Gestor Financiero)');
        $this->command->info('  - marketing@junior.com (Gestor Marketing)');
        $this->command->info('  - produccion@junior.com (Director Producción)');
        $this->command->info('');
        $this->command->info('Contraseña por defecto: password');
        $this->command->info('=====================================================');
    }
}

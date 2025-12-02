<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Crear usuario administrador
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@barberia.com',
            'password' => Hash::make('admin123'),
            'role' => User::ROLE_ADMIN,
        ]);

        // Crear algunos barberos de ejemplo
        User::create([
            'name' => 'Juan Barber',
            'email' => 'juan@barberia.com',
            'password' => Hash::make('barbero123'),
            'role' => User::ROLE_BARBERO,
        ]);

        User::create([
            'name' => 'Carlos Estilista',
            'email' => 'carlos@barberia.com',
            'password' => Hash::make('barbero123'),
            'role' => User::ROLE_BARBERO,
        ]);

        $this->command->info('Usuarios de ejemplo creados exitosamente!');
        $this->command->info('Admin: admin@barberia.com / admin123');
        $this->command->info('Barberos: juan@barberia.com / barbero123');
    }
}
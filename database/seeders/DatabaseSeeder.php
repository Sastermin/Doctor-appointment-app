<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //LLamar al RoleSeeder creado
        $this->call([
            RoleSeeder::class
        ]);
        

        //Crear un usuario de prueba cada que ejecuto migrations
        User::factory()->create([
            'name' => 'Rodrigo Gaxiola',
            'email' => 'rodrigo@software.com.mx',
            'password' => bcrypt('12345678'),
        ]);
    }
}

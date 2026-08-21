<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::factory()->create([
            'name' => 'Administrador',
            'email' => 'admin@fotosonline.test',
            'password' => Hash::make('admin1234'),
            'is_admin' => true,
        ]);

        User::factory()->create([
            'name' => 'Cliente Demo',
            'email' => 'cliente@fotosonline.test',
            'password' => Hash::make('cliente1234'),
        ]);

        $categories = [
            ['slug' => 'naturaleza', 'name_es' => 'Naturaleza', 'name_en' => 'Nature', 'description_es' => 'Paisajes, montañas, bosques y vida salvaje.', 'description_en' => 'Landscapes, mountains, forests and wildlife.'],
            ['slug' => 'ciudad', 'name_es' => 'Ciudad', 'name_en' => 'City', 'description_es' => 'Arquitectura, calles y vida urbana.', 'description_en' => 'Architecture, streets and urban life.'],
            ['slug' => 'retratos', 'name_es' => 'Retratos', 'name_en' => 'Portraits', 'description_es' => 'Retratos y fotografía de personas.', 'description_en' => 'Portraits and people photography.'],
            ['slug' => 'abstracto', 'name_es' => 'Abstracto', 'name_en' => 'Abstract', 'description_es' => 'Texturas, patrones y composiciones abstractas.', 'description_en' => 'Textures, patterns and abstract compositions.'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}

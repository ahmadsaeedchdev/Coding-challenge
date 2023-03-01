<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ingredients = [
            ['name' => 'Beef', 'total_amount' => 20000, 'current_amount' => 20000],
            ['name' => 'Fish', 'total_amount' => 20000, 'current_amount' => 20000],
            ['name' => 'Cheese', 'total_amount' => 5000, 'current_amount' => 5000],
            ['name' => 'Onion', 'total_amount' => 1000, 'current_amount' => 1000],
        ];

        foreach ($ingredients as $ingredient) {
            Ingredient::updateOrCreate(['name' => $ingredient['name']], $ingredient);
        }
    }
}

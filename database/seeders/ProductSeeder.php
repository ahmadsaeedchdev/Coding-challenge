<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Validation\Rules\In;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * @description Seeding product data for testing.
         */

        $product = Product::updateOrCreate(['name' => 'Burger'], ['name' => 'Burger']);

        $beefIngredient = Ingredient::where('name', 'Beef')->first();
        $cheeseIngredient = Ingredient::where('name', 'Cheese')->first();
        $onionIngredient = Ingredient::where('name', 'Onion')->first();

        $product->ingredients()->sync([
            $beefIngredient->id => ['amount' => 150],
            $cheeseIngredient->id => ['amount' => 30],
            $onionIngredient->id => ['amount' => 20],
        ]);

        //Second Product
        $product = Product::updateOrCreate(['name' => 'Fish Burger'], ['name' => 'Fish Burger']);

        $fishIngredient = Ingredient::where('name', 'Fish')->first();

        $product->ingredients()->sync([
            $fishIngredient->id => ['amount' => 150],
            $onionIngredient->id => ['amount' => 20],
        ]);
    }

}

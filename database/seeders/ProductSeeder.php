<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = [
            [
                'name' => 'PHP',
                'image' => 'https://dummyimage.com/200x200/4D588E/fff&text=PHP',
                'price' => 120,
                'description' => 'PHP Language'
            ],
            [
                'name' => 'Laravel',
                'image' => 'https://dummyimage.com/200x200/E83A2D/fff&text=Laravel',
                'price' => 220,
                'description' => 'Laravel freamwork'
            ],
            [
                'name' => 'Python',
                'image' => 'https://dummyimage.com/200x200/000/00ff04&text=python',
                'price' => 300,
                'description' => 'Python Language'
            ],
            [
                'name' => 'Codeigniter',
                'image' => 'https://dummyimage.com/200x200/F03B06/000&text=CI',
                'price' => 110,
                'description' => 'Codeigniter freamwork'
            ]
        ];

        foreach ($products as $key => $value) {
            Product::create($value);
        }
    }
}

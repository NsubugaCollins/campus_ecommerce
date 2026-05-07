<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'product_id' => 'ELEC-001',
                'name' => 'Wireless Bluetooth Headphones',
                'category' => 'Electronics',
                'price' => 340000,
            ],
            [
                'product_id' => 'FURN-001',
                'name' => 'Ergonomic Office Chair',
                'category' => 'Furniture',
                'price' => 950000,
            ],
            [
                'product_id' => 'BED-001',
                'name' => 'Memory Foam Mattress',
                'category' => 'Beddings',
                'price' => 1500000,
            ],
            [
                'product_id' => 'FASH-001',
                'name' => 'Cotton Summer Dress',
                'category' => 'Fashion',
                'price' => 220000,
            ],
            [
                'product_id' => 'ACC-001',
                'name' => 'Leather Crossbody Bag',
                'category' => 'Accessories',
                'price' => 290000,
            ],
            [
                'product_id' => 'SCH-001',
                'name' => 'Oxford Mathematical Set',
                'category' => 'Scholastic Materials',
                'price' => 5000,
            ],
            [
                'product_id' => 'SCH-002',
                'name' => 'Picasso Fountain Pen',
                'category' => 'Scholastic Materials',
                'price' => 15000,
            ],
            [
                'product_id' => 'SCH-003',
                'name' => 'Hardcover Notebook (A4)',
                'category' => 'Scholastic Materials',
                'price' => 3500,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\Comment;
use App\Models\Feedback;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data
        User::truncate();
        Product::truncate();
        Comment::truncate();
        Feedback::truncate();

        // Create admin user
        User::create([
            'name' => 'Admin Matcha',
            'email' => 'admin@matchastore.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Create sample customer
        User::create([
            'name' => 'John Doe',
            'email' => 'customer@example.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'email_verified_at' => now(),
        ]);

        // Create products
        $products = [
            [
                'name' => 'Premium Ceremonial Matcha',
                'description' => 'Highest quality ceremonial grade matcha powder from Uji, Japan. Perfect for traditional tea ceremonies.',
                'price' => 29.99,
                'stock' => 50,
                'image' => 'matcha1.jpg',
                'category' => 'matcha_powder',
                'is_featured' => true,
            ],
            [
                'name' => 'Organic Culinary Matcha',
                'description' => 'Versatile culinary grade matcha ideal for smoothies, lattes, and baking.',
                'price' => 19.99,
                'stock' => 100,
                'image' => 'matcha2.jpg',
                'category' => 'matcha_powder',
                'is_featured' => true,
            ],
            [
                'name' => 'Matcha Tea Set',
                'description' => 'Complete set including matcha powder, bamboo whisk, and traditional bowl.',
                'price' => 49.99,
                'stock' => 30,
                'image' => 'matcha3.jpg',
                'category' => 'matcha_accessories',
                'is_featured' => true,
            ],
            [
                'name' => 'Matcha Latte Mix',
                'description' => 'Ready-to-make matcha latte powder with natural sweeteners.',
                'price' => 14.99,
                'stock' => 75,
                'image' => 'matcha4.jpg',
                'category' => 'matcha_tea',
            ],
            [
                'name' => 'Matcha Cookies',
                'description' => 'Delicious buttery cookies with premium matcha infusion.',
                'price' => 9.99,
                'stock' => 120,
                'image' => 'matcha5.jpg',
                'category' => 'matcha_dessert',
            ],
            [
                'name' => 'Bamboo Matcha Whisk',
                'description' => 'Traditional bamboo whisk (chasen) for perfect matcha preparation.',
                'price' => 12.99,
                'stock' => 60,
                'image' => 'matcha6.jpg',
                'category' => 'matcha_accessories',
            ],
            [
                'name' => 'Matcha Ice Cream',
                'description' => 'Premium matcha flavored ice cream made with real matcha powder.',
                'price' => 6.99,
                'stock' => 200,
                'image' => 'matcha7.jpg',
                'category' => 'matcha_dessert',
            ],
            [
                'name' => 'Matcha Energy Bars',
                'description' => 'Healthy energy bars with matcha, nuts, and natural sweeteners.',
                'price' => 4.99,
                'stock' => 150,
                'image' => 'matcha8.jpg',
                'category' => 'matcha_dessert',
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }

        // Create sample comments
        $comments = [
            [
                'user_id' => 2,
                'product_id' => 1,
                'content' => 'Excellent quality matcha! Very smooth and vibrant green color.',
                'rating' => 5,
            ],
            [
                'user_id' => 2,
                'product_id' => 2,
                'content' => 'Great for daily use in smoothies. Good value for money.',
                'rating' => 4,
            ],
        ];

        foreach ($comments as $comment) {
            Comment::create($comment);
        }

        // Create sample feedback
        Feedback::create([
            'user_id' => 2,
            'name' => 'John Doe',
            'email' => 'customer@example.com',
            'subject' => 'Great website!',
            'message' => 'I love your matcha products. The website is easy to use.',
            'status' => 'new',
        ]);

        echo "Database seeded successfully!\n";
    }
}
<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $menus = [
            [
                'name' => 'Grilled Chicken with Rice',
                'description' => 'Juicy grilled chicken served with steamed rice and seasonal vegetables',
                'price' => 250,
                'category' => 'Non-Veg',
                'is_available' => true,
            ],
            [
                'name' => 'Vegetable Biryani',
                'description' => 'Fragrant basmati rice cooked with mixed vegetables and aromatic spices',
                'price' => 180,
                'category' => 'Veg',
                'is_available' => true,
            ],
            [
                'name' => 'Beef Curry with Naan',
                'description' => 'Tender beef curry cooked in traditional spices served with butter naan',
                'price' => 320,
                'category' => 'Non-Veg',
                'is_available' => true,
            ],
            [
                'name' => 'Mixed Fried Rice',
                'description' => 'Egg, chicken, and shrimp fried rice with Chinese vegetables',
                'price' => 280,
                'category' => 'Non-Veg',
                'is_available' => true,
            ],
            [
                'name' => 'Paneer Butter Masala',
                'description' => 'Cottage cheese cubes in creamy tomato gravy',
                'price' => 220,
                'category' => 'Veg',
                'is_available' => true,
            ],
            [
                'name' => 'Fish Curry with Rice',
                'description' => 'Fresh river fish cooked in mustard and coconut gravy',
                'price' => 290,
                'category' => 'Non-Veg',
                'is_available' => true,
            ],
        ];

        foreach ($menus as $menu) {
            Menu::create($menu);
        }
    }
}

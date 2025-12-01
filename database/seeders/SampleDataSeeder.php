<?php

namespace Database\Seeders;

use App\Models\CartItem;
use App\Models\Notification;
use App\Models\Seller;
use App\Models\User;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SampleDataSeeder extends Seeder
{
    public function run(): void
    {
        $sellerUser = User::firstOrCreate(
            ['email' => 'seller@example.com'],
            [
                'name' => 'Sample Seller',
                'password' => Hash::make('password'),
                'role' => 'seller',
                'phone' => '+63 917 000 0000',
                'shipping_address' => '123 Supply Street, Quezon City',
            ]
        );

        $seller = Seller::updateOrCreate(
            ['user_id' => $sellerUser->id],
            [
                'business_name' => 'Campus Supply Depot',
                'tax_id' => 'TIN-123456789',
                'contact_email' => 'seller@example.com',
                'contact_phone' => '+63 917 000 0000',
                'business_address' => '123 Supply Street, Quezon City, Metro Manila',
            ]
        );

        $products = [
            [
                'name' => 'Premium Notebook Bundle',
                'description' => 'Set of 5 hardbound notebooks with 100gsm paper, perfect for class notes.',
                'price' => 450.00,
                'stock' => 150,
                'category' => 'Notebooks',
                'variant' => 'A5, Mixed Colors',
            ],
            [
                'name' => 'Mechanical Pencil Set',
                'description' => 'Box of 12 0.5mm mechanical pencils with spare lead refills.',
                'price' => 320.00,
                'stock' => 200,
                'category' => 'Writing',
                'variant' => '0.5mm',
            ],
            [
                'name' => 'Watercolor Supply Kit',
                'description' => 'Complete art kit with brushes, palette, watercolor pad, and paints.',
                'price' => 899.00,
                'stock' => 60,
                'category' => 'Art',
                'variant' => '24-color set',
            ],
        ];

        $createdProducts = [];

        foreach ($products as $productData) {
            $createdProducts[] = $seller->products()->updateOrCreate(
                ['name' => $productData['name']],
                array_merge($productData, [
                    'status' => Product::STATUS_APPROVED,
                    'is_approved' => true,
                    'is_active' => true,
                ])
            );
        }

        $customer = User::firstOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name' => 'Sample Customer',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'phone' => '+63 915 111 2222',
                'shipping_address' => '45 Study Lane, Cebu City',
            ]
        );

        if ($createdProducts && $customer) {
            CartItem::updateOrCreate(
                [
                    'user_id' => $customer->id,
                    'product_id' => $createdProducts[0]->id,
                ],
                ['quantity' => 2]
            );

            if (isset($createdProducts[1])) {
                CartItem::updateOrCreate(
                    [
                        'user_id' => $customer->id,
                        'product_id' => $createdProducts[1]->id,
                    ],
                    ['quantity' => 1]
                );
            }

            Wishlist::updateOrCreate(
                [
                    'user_id' => $customer->id,
                    'product_id' => $createdProducts[0]->id,
                ],
                []
            );

            if (isset($createdProducts[2])) {
                Wishlist::updateOrCreate(
                    [
                        'user_id' => $customer->id,
                        'product_id' => $createdProducts[2]->id,
                    ],
                    []
                );
            }

            Notification::updateOrCreate(
                [
                    'user_id' => $customer->id,
                    'title' => 'Welcome to SchoolSupplies!',
                ],
                [
                    'type' => 'system',
                    'message' => 'Start shopping now—items in your cart are waiting to be checked out.',
                    'related_id' => $createdProducts[0]->id,
                    'related_type' => Product::class,
                    'is_read' => false,
                ]
            );

            Notification::updateOrCreate(
                [
                    'user_id' => $customer->id,
                    'title' => 'Price drop on your favorites',
                ],
                [
                    'type' => 'promotion',
                    'message' => 'Mechanical Pencil Set is now ₱320. Complete checkout to lock in the price.',
                    'related_id' => $createdProducts[1]->id ?? $createdProducts[0]->id,
                    'related_type' => Product::class,
                    'is_read' => false,
                ]
            );
        }
    }
}


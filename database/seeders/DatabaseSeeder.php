<?php

namespace Database\Seeders;

use App\Models\Hotel;
use App\Models\Notification;
use App\Models\Offer;
use App\Models\Room;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Settings ─────────────────────────────────────────────────
        $settings = [
            ['key'=>'default_commission',     'value'=>'10',              'type'=>'number',  'group'=>'payment',  'label'=>'Default Commission %'],
            ['key'=>'min_commission_amount',  'value'=>'99',              'type'=>'number',  'group'=>'payment',  'label'=>'Min Commission (₹)'],
            ['key'=>'max_commission_amount',  'value'=>'499',             'type'=>'number',  'group'=>'payment',  'label'=>'Max Commission (₹)'],
            ['key'=>'razorpay_enabled',       'value'=>'1',               'type'=>'boolean', 'group'=>'payment',  'label'=>'Enable Razorpay'],
            ['key'=>'phonepe_enabled',        'value'=>'1',               'type'=>'boolean', 'group'=>'payment',  'label'=>'Enable PhonePe'],
            ['key'=>'site_name',              'value'=>'MyRoom',          'type'=>'string',  'group'=>'general',  'label'=>'Site Name'],
            ['key'=>'support_phone',          'value'=>'+91 98765 43210', 'type'=>'string',  'group'=>'general',  'label'=>'Support Phone'],
            ['key'=>'support_email',          'value'=>'support@myroom.in','type'=>'string', 'group'=>'general',  'label'=>'Support Email'],
            ['key'=>'booking_accept_timeout', 'value'=>'2',               'type'=>'number',  'group'=>'booking',  'label'=>'Hotel Accept Timeout (hours)'],
        ];
        foreach ($settings as $s) Setting::updateOrCreate(['key' => $s['key']], $s);

        // ── Admin ─────────────────────────────────────────────────────
        $admin = User::updateOrCreate(['email' => 'admin@myroom.in'], [
            'name' => 'MyRoom Admin', 'phone' => '9000000000',
            'password' => Hash::make('Admin@123'), 'role' => 'admin', 'status' => 'active',
        ]);

        // ── Hotel owners ──────────────────────────────────────────────
        $h1 = User::updateOrCreate(['email' => 'hotel.admire@myroom.in'], [
            'name' => 'Hotel Admire', 'phone' => '9111222333',
            'password' => Hash::make('Hotel@123'), 'role' => 'hotel_owner', 'status' => 'active',
        ]);
        $h2 = User::updateOrCreate(['email' => 'spectrum@myroom.in'], [
            'name' => 'Spectrum Hotels', 'phone' => '9222333444',
            'password' => Hash::make('Hotel@123'), 'role' => 'hotel_owner', 'status' => 'active',
        ]);

        // ── Sample customer ───────────────────────────────────────────
        $customer = User::updateOrCreate(['email' => 'demo@myroom.in'], [
            'name' => 'Demo Customer', 'phone' => '9876543210',
            'password' => Hash::make('Demo@123'), 'role' => 'customer', 'status' => 'active',
        ]);

        // ── Hotels ────────────────────────────────────────────────────
        $hotelsData = [
            [
                'user_id' => $h1->id, 'name' => 'Hotel Admire Inn Sector 104',
                'city' => 'Noida', 'area' => 'Sector 104',
                'address' => 'Near Metro Gate 2, Sector 104, Noida UP 201304',
                'description' => 'A comfortable hourly hotel in Sector 104, Noida. Clean rooms, friendly staff, and great location near the metro.',
                'star_rating' => 3, 'amenities' => ['AC','WiFi','TV','Hot Water','Parking'],
                'cover_image' => 'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=800&q=80',
                'listing_priority' => 'top', 'listing_order' => 1,
                'status' => 'active', 'avg_rating' => 4.5, 'total_reviews' => 128,
                'is_featured' => true, 'couple_friendly' => true, 'accepts_local_id' => true,
                'rooms' => [
                    ['name'=>'Deluxe Room','stay_type'=>'both','hourly_price'=>299,'min_hours'=>2,'overnight_price'=>999,'price_3hr'=>799,'price_6hr'=>1299,'price_12hr'=>1799,'capacity'=>2,'amenities'=>['AC','WiFi','TV']],
                    ['name'=>'Super Deluxe','stay_type'=>'both','hourly_price'=>399,'min_hours'=>2,'overnight_price'=>1299,'price_3hr'=>999,'price_6hr'=>1599,'price_12hr'=>2199,'capacity'=>2,'amenities'=>['AC','WiFi','TV','Hot Water']],
                    ['name'=>'Executive Room','stay_type'=>'overnight','overnight_price'=>1599,'capacity'=>2,'amenities'=>['AC','WiFi','TV','Mini Fridge']],
                ]
            ],
            [
                'user_id' => $h2->id, 'name' => 'Hotel Spectrum Inn',
                'city' => 'Delhi', 'area' => 'Connaught Place',
                'address' => 'K-Block, Connaught Place, New Delhi 110001',
                'description' => 'Premium boutique hotel at Connaught Place. Walking distance from Rajiv Chowk Metro.',
                'star_rating' => 4, 'amenities' => ['AC','WiFi','Restaurant','Bar','Gym'],
                'cover_image' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800&q=80',
                'listing_priority' => 'top', 'listing_order' => 2,
                'status' => 'active', 'avg_rating' => 4.7, 'total_reviews' => 214,
                'is_featured' => true, 'couple_friendly' => true, 'accepts_local_id' => true,
                'rooms' => [
                    ['name'=>'Standard Room','stay_type'=>'both','hourly_price'=>499,'min_hours'=>2,'overnight_price'=>1799,'price_3hr'=>1299,'price_6hr'=>1999,'price_12hr'=>2799,'capacity'=>2,'amenities'=>['AC','WiFi','TV']],
                    ['name'=>'Deluxe Suite','stay_type'=>'overnight','overnight_price'=>3499,'capacity'=>2,'amenities'=>['AC','WiFi','TV','Mini Bar']],
                ]
            ],
            [
                'user_id' => $h2->id, 'name' => 'The Centrum Hotel',
                'city' => 'Delhi', 'area' => 'Aerocity',
                'address' => 'Aerocity, IGI Airport Area, New Delhi 110037',
                'description' => 'Ideal for transit travellers. 5 minutes from IGI Airport. 24/7 hourly check-in available.',
                'star_rating' => 4, 'amenities' => ['AC','WiFi','Restaurant','Pool','Gym','Conference Room'],
                'cover_image' => 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=800&q=80',
                'listing_priority' => 'top', 'listing_order' => 3,
                'status' => 'active', 'avg_rating' => 4.8, 'total_reviews' => 312,
                'is_featured' => true, 'couple_friendly' => true, 'accepts_local_id' => true,
                'rooms' => [
                    ['name'=>'Deluxe Room','stay_type'=>'both','hourly_price'=>699,'min_hours'=>2,'overnight_price'=>2499,'price_3hr'=>1699,'price_6hr'=>2499,'price_12hr'=>3299,'capacity'=>2,'amenities'=>['AC','WiFi','TV','Pool Access']],
                ]
            ],
            [
                'user_id' => $h1->id, 'name' => 'Hotel Admire Sector 50',
                'city' => 'Noida', 'area' => 'Sector 50',
                'address' => 'Near Metro, Sector 50, Noida UP',
                'description' => 'Budget-friendly hourly rooms near Sector 50 metro.',
                'star_rating' => 3, 'amenities' => ['AC','WiFi','TV'],
                'cover_image' => 'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?w=800&q=80',
                'listing_priority' => 'middle', 'listing_order' => 5,
                'status' => 'active', 'avg_rating' => 4.1, 'total_reviews' => 56,
                'is_featured' => false, 'couple_friendly' => false, 'accepts_local_id' => true,
                'rooms' => [
                    ['name'=>'Standard Room','stay_type'=>'hourly','hourly_price'=>249,'min_hours'=>2,'price_3hr'=>599,'price_6hr'=>999,'price_12hr'=>1399,'capacity'=>2,'amenities'=>['AC','WiFi']],
                ]
            ],
            [
                'user_id' => $h2->id, 'name' => 'StayEasy Cyber City',
                'city' => 'Gurgaon', 'area' => 'Cyber City',
                'address' => 'DLF Phase 2, Cyber City, Gurugram 122002',
                'description' => 'Modern corporate hotel near Cyber City IT hub.',
                'star_rating' => 3, 'amenities' => ['AC','WiFi','Parking','TV','Conference Room'],
                'cover_image' => 'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=800&q=80',
                'listing_priority' => 'middle', 'listing_order' => 6,
                'status' => 'active', 'avg_rating' => 4.0, 'total_reviews' => 44,
                'is_featured' => false, 'couple_friendly' => true, 'accepts_local_id' => true,
                'rooms' => [
                    ['name'=>'Business Room','stay_type'=>'overnight','overnight_price'=>1299,'capacity'=>2,'amenities'=>['AC','WiFi','Desk']],
                    ['name'=>'Business Plus','stay_type'=>'both','hourly_price'=>450,'min_hours'=>2,'overnight_price'=>1599,'price_3hr'=>1099,'price_6hr'=>1699,'price_12hr'=>2199,'capacity'=>2,'amenities'=>['AC','WiFi','Desk']],
                ]
            ],
        ];

        foreach ($hotelsData as $data) {
            $rooms = $data['rooms'];
            unset($data['rooms']);
            $hotel = Hotel::updateOrCreate(
                ['name' => $data['name'], 'user_id' => $data['user_id']],
                $data
            );
            foreach ($rooms as $rd) {
                Room::updateOrCreate(
                    ['hotel_id' => $hotel->id, 'name' => $rd['name']],
                    array_merge($rd, ['is_available' => true])
                );
            }
        }

        // ── Sample Offers ─────────────────────────────────────────────
        Offer::updateOrCreate(['code' => 'WEEKEND20'], [
            'title' => 'Weekend Special', 'type' => 'percentage', 'discount' => 20,
            'max_discount' => 200, 'stay_type' => 'hourly', 'min_amount' => 300, 'is_active' => true,
        ]);
        Offer::updateOrCreate(['code' => 'OVERNIGHT15'], [
            'title' => 'Overnight Deal', 'type' => 'percentage', 'discount' => 15,
            'max_discount' => 300, 'stay_type' => 'overnight', 'min_amount' => 500, 'is_active' => true,
        ]);
        Offer::updateOrCreate(['code' => 'FLAT100'], [
            'title' => '₹100 Flat Off', 'type' => 'fixed', 'discount' => 100,
            'stay_type' => 'both', 'min_amount' => 400, 'is_active' => true,
        ]);
        Offer::updateOrCreate(['code' => 'NEWUSER50'], [
            'title' => 'New User Offer', 'type' => 'fixed', 'discount' => 50,
            'stay_type' => 'both', 'min_amount' => 200, 'is_active' => true,
        ]);

        // ── Welcome notification for demo customer ────────────────────
        Notification::create([
            'user_id' => $customer->id,
            'type'    => 'welcome',
            'title'   => 'Welcome to MyRoom! 🎉',
            'message' => 'Start by searching for hotels in your city. Use code NEWUSER50 for ₹50 off your first booking!',
        ]);

        $this->command->info('✅ Database seeded successfully!');
        $this->command->table(['Role','Email','Password'],[
            ['Admin',    'admin@myroom.in',         'Admin@123'],
            ['Hotel 1',  'hotel.admire@myroom.in',  'Hotel@123'],
            ['Hotel 2',  'spectrum@myroom.in',       'Hotel@123'],
            ['Customer', 'demo@myroom.in',           'Demo@123'],
        ]);
    }
}

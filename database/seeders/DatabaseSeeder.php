<?php

namespace Database\Seeders;

use App\Models\Outlet;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Shiro Laundry main outlet profile
        $outlet = Outlet::create([
            'nama_cabang' => 'Shiro Laundry',
            'alamat'      => 'Jl. Sutomo No. 12, Samarinda, Kalimantan Timur',
            'no_whatsapp' => '6281234567890',
        ]);

        // Create Super Admin
        User::create([
            'name'      => 'Admin Shiro',
            'email'     => 'admin@shiro.com',
            'password'  => Hash::make('password123'),
            'role'      => 'super_admin',
            'outlet_id' => $outlet->id,
        ]);

        // Create Kasir user
        User::create([
            'name'      => 'Kasir Shiro',
            'email'     => 'kasir@shiro.com',
            'password'  => Hash::make('password123'),
            'role'      => 'kasir',
            'outlet_id' => $outlet->id,
        ]);

        // Sample orders with simple sequential nota (SHR-0001, SHR-0002, ...)
        $sampleOrders = [
            ['nama' => 'Budi Santoso',    'hp' => '081234567801', 'harga' => 35000,  'status' => 'antrean'],
            ['nama' => 'Siti Aminah',     'hp' => '081234567802', 'harga' => 50000,  'status' => 'proses_cuci'],
            ['nama' => 'Ahmad Fauzi',     'hp' => '081234567803', 'harga' => 45000,  'status' => 'proses_cuci'],
            ['nama' => 'Dewi Lestari',    'hp' => '081234567804', 'harga' => 80000,  'status' => 'setrika_packing'],
            ['nama' => 'Rudi Hartono',    'hp' => '081234567805', 'harga' => 60000,  'status' => 'setrika_packing'],
            ['nama' => 'Nur Aisyah',      'hp' => '081234567806', 'harga' => 40000,  'status' => 'selesai'],
            ['nama' => 'Eko Prasetyo',    'hp' => '081234567807', 'harga' => 75000,  'status' => 'selesai'],
            ['nama' => 'Rina Wulandari',  'hp' => '081234567808', 'harga' => 120000, 'status' => 'selesai'],
        ];

        foreach ($sampleOrders as $index => $data) {
            $number = str_pad($index + 1, 4, '0', STR_PAD_LEFT);
            Order::create([
                'nomor_nota'     => "SHR-{$number}",
                'nama_pelanggan' => $data['nama'],
                'no_hp'          => $data['hp'],
                'outlet_id'      => $outlet->id,
                'total_harga'    => $data['harga'],
                'status_cucian'  => $data['status'],
            ]);
        }
    }
}

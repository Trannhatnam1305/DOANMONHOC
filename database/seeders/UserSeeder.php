<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // 1. Tạo tài khoản ADMIN (Role = 1)
        DB::table('users')->insert([
            'username'          => 'admin',
            'name'              => null, // Để trống Name
            'email'             => 'admin@gmail.com',
            'phone'             => null, // Để trống Phone
            'email_verified_at' => now(),
            'password'          => Hash::make('password123'),
            'role'              => 1,
            'remember_token'    => Str::random(10),
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        // 2. Tạo tài khoản USER mẫu (Role = 0)
        DB::table('users')->insert([
            'username'          => 'nhatnam',
            'name'              => null, // Để trống Name
            'email'             => 'nam@gmail.com',
            'phone'             => null, // Để trống Phone
            'email_verified_at' => now(),
            'password'          => Hash::make('123456'),
            'role'              => 0,
            'remember_token'    => Str::random(10),
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        // 3. Tạo thêm 5 tài khoản ngẫu nhiên
        foreach (range(1, 5) as $index) {
            DB::table('users')->insert([
                'username'          => $faker->userName,
                'name'              => null, // Để trống Name
                'email'             => $faker->unique()->safeEmail,
                'phone'             => null, // Để trống Phone
                'email_verified_at' => now(),
                'password'          => Hash::make('123456'),
                'role'              => 0,
                'remember_token'    => Str::random(10),
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);
        }
    }
}
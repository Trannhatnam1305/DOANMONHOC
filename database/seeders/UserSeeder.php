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
            'email'             => 'admin@gmail.com',
            'email_verified_at' => now(),
            'password'          => Hash::make('password123'), // MK: password123
            'role'              => 1, // Quyền Admin
            'remember_token'    => Str::random(10),
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        // 2. Tạo tài khoản USER mẫu (Role = 0)
        DB::table('users')->insert([
            'username'          => 'nhatnam',
            'email'             => 'nam@gmail.com',
            'email_verified_at' => now(),
            'password'          => Hash::make('123456'), // MK: 123456
            'role'              => 0, // Quyền User
            'remember_token'    => Str::random(10),
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        // 3. Tạo thêm 5 tài khoản ngẫu nhiên (Hỗn hợp)
        foreach (range(1, 5) as $index) {
            DB::table('users')->insert([
                'username'          => $faker->userName,
                'email'             => $faker->unique()->safeEmail,
                'email_verified_at' => now(),
                'password'          => Hash::make('123456'),
                'role'              => 0, // Mặc định là user
                'remember_token'    => Str::random(10),
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);
        }
    }
}
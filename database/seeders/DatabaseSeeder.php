<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        DB::table('users')->insert([
            'id' => '1111111111',
            'name' => 'AAACC',
            'email' => 'AAC779@gmail.com',
            'password' =>  Hash::make('123'),
            'phoneNumber' => '0979877988',
            'birth' => '2024-06-12',
            'gender' => '1',
            'avatar' => 'fqfq21',
            'coverimage' => 'rqr123',
            'created_at' => '2024-06-06 01:38:12',
            'updated_at' => '2024-06-14 01:38:16',
        ]);
    }
}

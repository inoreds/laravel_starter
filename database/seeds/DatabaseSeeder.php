<?php

use App\MaUser;
use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        MaUser::create([
            'id' => Uuid::uuid1()->toString(),
            'nama_lengkap' => 'Root', 
            'username' => 'root',
            'password' => Hash::make('root'),
            'role' => 'root',
            'status'=> 'AKTIF'
        ]);

        MaUser::create([
            'id' => Uuid::uuid1()->toString(),
            'nama_lengkap' => 'Admin', 
            'username' => 'admin',
            'password' => Hash::make('admin'),
            'role' => 'admin',
            'status'=> 'AKTIF'
        ]);

        MaUser::create([
            'id' => Uuid::uuid1()->toString(),
            'nama_lengkap' => 'User', 
            'username' => 'user',
            'password' => Hash::make('user'),
            'role' => 'user',
            'status'=> 'AKTIF'
        ]);
    }
}

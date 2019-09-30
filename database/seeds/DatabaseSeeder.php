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
    }
}

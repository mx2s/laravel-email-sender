<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->name = 'Mr Admin';
        $user->email = 'admin@root.com';
        $user->password = Hash::make('someSecurePassword');
        $user->api_token = Str::random(20);
        $user->save();
    }
}

<?php

namespace Modules\User\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserDatabaseSeeder extends Seeder
{
   public function run(): void
    {
        // Create roles if they don't exist
        $managerRole = Role::firstOrCreate(['name' => 'Manager']);
        $userRole    = Role::firstOrCreate(['name' => 'User']);

        // Manager user
        $manager = User::firstOrCreate(
            ['email' => 'manager@manager.com'],
            [
                'name' => 'Manager User',
                'password' => Hash::make('password123'),  
            ]
        );
        $manager->assignRole($managerRole);

    foreach (range(1, 5) as $i) {

                // Normal user
                $user = User::firstOrCreate(
                    ['email' => "user{$i}@user.com"], 
                    [
                        'name' => "Normal User {$i}", 
                        'password' => Hash::make('password123'),
                    ]
                );

                $user->assignRole($userRole);
            }
     }
}

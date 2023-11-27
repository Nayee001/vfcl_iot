<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateSuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'user_id'=>1000,
            'fname' => 'Akshay',
            'lname' => 'Nayee',
            'title' => 'Student',
            'status' => 1,
            'phonenumber' => '8145047499',
            'email' => 'avi003@gannon.edu',
            'password' => bcrypt('Gannon')
        ]);

        $role = Role::create(['name' => 'Super Admin']);

        $permissions = Permission::pluck('id','id')->all();

        $role->syncPermissions($permissions);

        $user->assignRole([$role->id]);
    }
}

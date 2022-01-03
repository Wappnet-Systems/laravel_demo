<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('users')->truncate();

        $user = User::create([
            'first_name' => "Super",
            'last_name' => "Admin",
            'email' => 'test@gmail.com',
            'password' => Hash::make('wappnet@123'),
            'status' => "Enabled",
            'user_type' => "admin",
        ]);
        DB::table('roles')->truncate();

        $role = Role::create(['guard_name' => 'admin', 'name' => 'Super Admin']);

        $permissions = Permission::pluck('id', 'id')->all();

        $role->syncPermissions($permissions);

        $user->assignRole([$role->id]);
    }
}

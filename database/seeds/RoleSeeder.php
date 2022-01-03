<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Model\Roles;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('roles')->truncate(); */

        $roles_admin = [
            'Sub Admin',
            'Accountant',
        ];

        foreach ($roles_admin as $role1) {
            Roles::create(['name' => $role1, 'guard_name' => "admin"]);
        }

        $roles = [
            'Normal User',
            'Premium Users',
            'Business Users',
            'Sub-Business Users',
        ];

        foreach ($roles as $role) {
            Roles::create(['name' => $role, 'guard_name' => "web"]);
        }


    }
}

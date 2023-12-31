<?php

namespace Database\Seeders;

use App\Models\PreRegistration;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CreateRolesAndAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            DB::beginTransaction();
            $adminRole = \Spatie\Permission\Models\Role::create([
                "name"=>"admin",
                "guard_name"=>"sanctum"

            ]);
            $professorRole = \Spatie\Permission\Models\Role::create([
                "name"=>"professor",
                "guard_name"=>"sanctum"

            ]);
            $monitorRole = \Spatie\Permission\Models\Role::create([
                "name"=>"monitor",
                "guard_name"=>"sanctum"
            ]);
            $user  =  PreRegistration::create(
                [


                    "login"=>"SEU LOGIN",
                    "email"=>"SEU EMAIL",
                    "role_id"=>$adminRole->id,


                ]
            );
            DB::commit();
        }catch (\Throwable $e){
            DB::rollBack();
            throw $e;
        }

        //php artisan db:seed --class=CreateRolesAndAdminSeeder


    }
}

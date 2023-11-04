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
                "name"=>"admin"
            ]);
            $professorRole = \Spatie\Permission\Models\Role::create([
                "name"=>"professor"
            ]);
            $monitorRole = \Spatie\Permission\Models\Role::create([
                "name"=>"monitor"
            ]);
            $user  =  PreRegistration::create(
                [


                    "login"=>"savio.gaia",
                    "email"=>"saviogmoiagaia@gmail.com",
                    "role_id"=>$adminRole->id,


                ]
            );
            DB::commit();
        }catch (\Throwable $e){
            DB::rollBack();
            throw $e;
        }

        //php atisan db:seed --class=CreateRolesAndAdminSeeder


    }
}

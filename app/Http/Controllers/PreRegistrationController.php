<?php

namespace App\Http\Controllers;

use App\Models\Laboratory;
use App\Models\PreRegistration;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class PreRegistrationController extends Controller
{
    /*public function __construct()
    {
        $this->authorizeResource(PreRegistration::class, "pre_registration");
    }*/

    public function index(Request $request)
    {
        $preRegistrations = PreRegistration::query()
            ->where("login",'=', $request->user()->login)
            ->with(["user","role","laboratory"])
            ->get();
        return Inertia::render("PreRegistration/IndexPreRegistration",compact("preRegistrations"));

    }
    public function update(PreRegistration $preRegistration, Request $request){
        if($preRegistration->status != "p"){
            return redirect()->back()->withErrors("Esse pré registro ja foi ".($preRegistration->status == "a"?"aceito":"rejeitado"));
        }
        $validated  = $request->validate(
            [
                "response"=>["required","boolean"]
            ]
        );
        try{
            DB::beginTransaction();
            //begin
            if($validated["response"]){

                $user = $request->user();
                $role = null;
                if ( $preRegistration->role_id) {
                    //dd("aqui");
                    $rolet = Role::query()->find($preRegistration->role_id); // essa extend Model
                    $role = new  \Spatie\Permission\Models\Role();
                    $role->id = $rolet->id;
                    $role->name = $rolet->name;

                }
                $dataUser = [];
                switch ($role->name){
                    case "admin":
                        // nao tem relação direta com nenhum lab
                        $dataUser["laboratory_id"] = null;
                        $this->removeUserFromLaboratories($user);
                        break;
                    case "professor":
                        //registrar no lab q o professor é coordenador
                        $dataUser["laboratory_id"] = null;
                        $laboratory = Laboratory::query()->find($preRegistration->laboratory_id);
                        $laboratory->update(["user_id"=>$user->id]);
                        break;
                    case "monitor":
                        $dataUser["laboratory_id"] = $preRegistration->laboratory_id;
                        $this->removeUserFromLaboratories($user);

                        break;
                }
                $user->update($dataUser);

                if(!is_null($role)){
                    $user->syncRoles([$role]);
                }

            }

            //end
            $preRegistration->update(
                [
                    "status"=> $validated["response"]?"a":"r"
                ]
            );
            DB::commit();
        }catch (\Throwable $e){
            DB::rollBack();
            throw $e;
        }

        return redirect()->back();
    }
    private function removeUserFromLaboratories($user): void
    {
        Laboratory::query()
            ->where("user_id","=",$user->id)
            ->get()
            ->each(function ($item) use ($user) {
                $item->update(["user_id"=>null]);
            });
    }
    private function existsPreRegistrationPendentWith(string $login): bool
    {
        return PreRegistration::query()
            ->where("login", "=", $login)
            ->where("status", "=", "p")
            ->exists();
    }

}

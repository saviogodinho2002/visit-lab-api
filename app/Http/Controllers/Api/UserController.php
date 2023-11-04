<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\RequestSIGAA;
use App\Http\Controllers\SIGAALogin;
use App\Models\PreRegistration;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\Concerns\Has;
use PhpParser\Node\Stmt\TryCatch;

class UserController extends Controller
{
    //
    public function loginUser(Request $request)
    {
        $data = $request->all();
        //;;$logou = SIGAALogin::login_sigaa($data["login"],$data["password"]);
        //$logou = RequestSIGAA::get_info_user("savio.gaia");
        //todo
        /*
         * Verificar se o usuario esta cadastrado para algum cargo
         * Criar usuario atribuindo cargo se tiver
         * logar
         *
         * Se ja existir, atualiza os dados na base local
         * */
        try {
            DB::beginTransaction();
            $login = SIGAALogin::login_sigaa($data["login"], $data["password"]);
            if (!$login) {
                return response()->json("Usuário ou senha incorreto", 404);
            }

            $info = RequestSIGAA::get_info_user($data["login"]);
            //dd($info);
            $pre_register = PreRegistration::query()
                ->where("login", "=", $data["login"])
                ->first();
            $user = User::query()->where("login", "=", $data["login"])
                ->first();

            if (is_null($pre_register) || !$pre_register->registered) {

                $user = User::create([
                    "institutional_id" => $info["id-institucional"],
                    "name" => $info["nome-pessoa"],
                    "login" => $info["login"],
                    "password" => Hash::make($data["password"]),
                    "photo_url" => $info["url-foto"],
                    "laboratory_id" => $pre_register?->laboratory_id
                ]);

                if (!is_null($pre_register) && $pre_register->role_id) {

                    $user->assignRole(Role::findById($pre_register->role_id)->name);
                    $pre_register->update(["registered" => true]);
                }


            } else {
                $user->update(
                    [
                        "name" => $info["nome-pessoa"],
                        "login" => $info["login"],
                        "password" => Hash::make($data["password"]),
                        "photo_url" => $info["url-foto"],
                    ]
                );

            }

            $token = $user->createToken($user["institutional_id"]);

            //session(["userInfo"=>"teste"]);
            DB::commit();
            return  response()->json($token->plainTextToken) ;
        }catch (\Throwable $e){
            DB::rollBack();
            throw $e;
        }


    }

    function me(Request $request){
        return  response()->json($request->user()) ;
    }


}

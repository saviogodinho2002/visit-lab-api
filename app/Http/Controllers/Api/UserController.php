<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\RequestSIGAA;
use App\Http\Controllers\SIGAALogin;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function loginUser(Request $request){
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

        return response()->json("") ;

    }
}

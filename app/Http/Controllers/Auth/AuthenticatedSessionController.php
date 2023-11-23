<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\ApiSigaaTimeOutException;
use App\Exceptions\SigaaLoginTimeOutException;
use App\Http\Controllers\Controller;
use App\Http\Controllers\RequestSIGAA;
use App\Http\Controllers\SIGAALogin;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        {
            $data = $request->all();
            //;;$logou = SIGAALogin::login_sigaa($data["login"],$data["password"]);
            //$logou = RequestSIGAA::get_info_user("savio.gaia");
            //todo
            // ve se o sigaa caiu e es ele existe no banco
            DB::beginTransaction();
            $login = null;
            $user = User::query()->where("login", "like",  $data["login"])
                ->first();
            $error = null;
            try {

                $login = SIGAALogin::login_sigaa($data["login"], $data["password"]);

                if (!$login) {
                    return redirect()->back()->withErrors(["Login não encontrado"]);
                }

            }catch (SigaaLoginTimeOutException $e){

                if(!is_null($user) ){ //se tiver usuaŕio
                    if(!$this->verifyPassword($user,$data["password"])){ // e a senha nao for valida

                        return redirect()->back()->withErrors(["Sigaa fora do ar. Senha inválida com último login no sistema"]);
                    }
                }else{ // nao tem usuario e sigaa fora do ar
                    return redirect()->back()->withErrors(["Primeiro login do usuário no sistema e o SIGAA está fora do ar. Suas credenciais não podem ser validadas"]);

                }

            }
            //chegou aqui e user é null ->criar
            // se não atualizar
            $info = null;
            try {

                $info = RequestSIGAA::get_info_user($data["login"]); //no caso user vai sempre existir, ja que ja foi validado pelo sigaa

            }
            catch (ApiSigaaTimeOutException $e){

            }
            catch (\Throwable $e){

            }
            if ( is_null($user) && !is_null($info) ) {
                // dd($user);
                $dataUser = [
                    "institutional_id" => $info["id-institucional"],
                    "name" => $info["nome-pessoa"],
                    "login" => $info["login"],
                    "password" => Hash::make($data["password"]),
                    "photo_url" => $info["url-foto"],
                ];
                $user = User::create(

                    $dataUser
                );


            } else if(!is_null($info)) { /// tem user e recuperou info
                $user->update(
                    [
                        "name" => $info["nome-pessoa"],
                        "login" => $info["login"],
                        "password" => Hash::make($data["password"]),
                        "photo_url" => $info["url-foto"],
                    ]
                );

            }else if(!is_null($user)){ // tem user e NAO recuperou info
                $user->update(
                    [

                        "login" => $data["login"],
                        "password" => Hash::make($data["password"]),
                    ]
                );
            }
            else{
                DB::rollBack();
                return redirect()->back()->withErrors(["Não foi possível recuperar dados do usuário. Api Fora do ar"]);
            }
            Auth::login($user);

            DB::commit();
            return redirect()->intended(RouteServiceProvider::HOME);



        }


    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}

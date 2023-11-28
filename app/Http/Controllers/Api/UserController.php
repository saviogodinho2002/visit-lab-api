<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ApiSigaaTimeOutException;
use App\Exceptions\SigaaLoginTimeOutException;
use App\Http\Controllers\Controller;
use App\Http\Controllers\RequestSIGAA;
use App\Http\Controllers\SIGAALogin;
use App\Models\PreRegistration;
use App\Models\Role;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\Concerns\Has;
use PhpParser\Node\Stmt\TryCatch;
use function Laravel\Prompts\password;


/**
 * @OA\Info(
 *   title="Api Visit Lab",
 *   version="3.0.0",
 *   @OA\Contact(
 *     email="saviogmoiagaia@gmail.com"
 *   )
 * ),
 * @OA\SecurityScheme(
 *     type="http",
 *     description="Access token obtido na autenticação",
 *     name="Authorization",
 *     in="header",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     securityScheme="bearerToken"
 * ),
 * @OA\SecurityScheme(
 *     type="apiKey",
 *     in="header",
 *     securityScheme="api_key",
 *     name="X-Application-Key",
 *     description="Chave de aplicação para autenticação na API"
 * ),
 * security={
 *   {"bearerToken": {}},
 *   {"api_key": {}}
 * }
 */


class UserController extends Controller
{
    public function __construct()
    {
       $this->authorizeResource(User::class,"user");
    }

    /**
     * Loga um  usuário.
     *
     *
     * @OA\Post(
     *      tags={"Usuario"},
     *     path="/api/login",
     *     description="Loga",
     *         security={
     *     {"bearerToken":{}},
     *        {"api_key": {}}
     *     },
     *     @OA\RequestBody(
     *         description="Json com login e senha do usuario",
     *         required=true,
     *      @OA\MediaType(
     *           mediaType="application/json",
     *
     *     @OA\Schema(
                type="object",
     *                 required={
     *     "login",
     *     "password",
     *
     *
     *
     * },
     *     @OA\Property (
     *         property="login",
     *         description="Login do usuario",
     *          type="string"
     *     ),

     *     @OA\Property(
     *         property="password",
     *
     *         description="Senha do usuario",
     *           type="string"
     *     ),

     *
     *
     *     ),
     * ),
     * ),
     *  @OA\Response(
     *    response=200,
     *    description="Logado",
     *    @OA\JsonContent(
     *       @OA\Property(property="token", type="string"),
     *    )
     *  ),
     *     @OA\Response(
     *    response=422,
     *    description="Formulário inválido",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string"),
     *       @OA\Property(property="message", type="string"),
     *    )
     *  )
     *
     * )
     */

    public function loginUser(Request $request)
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
                return response()->json("Login não encontrado", 404);
            }

        }catch (SigaaLoginTimeOutException $e){

            if(!is_null($user) ){ //se tiver usuaŕio
                if(!$this->verifyPassword($user,$data["password"])){ // e a senha nao for valida

                    return response()->json("Sigaa fora do ar. Senha inválida com último login no sistema", 400);
                }
            }else{ // nao tem usuario e sigaa fora do ar
                return response()->json("Primeiro login do usuário no sistema e o SIGAA está fora do ar. Suas credenciais não podem ser validadas", 400);

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
            return response()->json("Não foi possível recuperar dados do usuário. Api Fora do ar", 400);
        }
        $user->tokens()->delete();
        $token = $user->createToken($user["institutional_id"]);


        DB::commit();
        return  response()->json($token->plainTextToken) ;


    }
    private function createUser($dataUser){


        $user = User::create(

            $dataUser
        );
        return $user;
    }
    private function verifyPassword( $user,$password): bool
    {
        return Hash::check($password, $user->password);
    }


    /**
     * @OA\Get(
     *     path="/api/users?login={login}",
     *     summary="Obtém um user pelo login",
     *     tags={"Usuario"},
     *          security={
     *     {"bearerToken":{}},
     *        {"api_key": {}} },
     *     @OA\Parameter(
     *         name="login",
     *         in="path",
     *         description="login do usuario",
     *         required=true
     *     ),
     *     @OA\Response(response="200", description="Uma lista de responsaveis"),
     *
     * )
     */
    function getUserByLogin(Request $request){

        $data = $request->all();
        $info = null;
        if(isset($data["login"]) && !is_null($data["login"])){
            try {

                $info = RequestSIGAA::get_info_user($data["login"]);
            }catch (\Throwable $e){

            }
        }
        return  response()->json($info) ;
    }
    /**
     * @OA\Get(
     *     path="/api/users/{id}",
     *     summary="Obtém um user pelo id",
     *     tags={"Usuario"},
     *          security={
     *     {"bearerToken":{}},
     *        {"api_key": {}}
     *      },
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do usuario",
     *         required=true
     *     ),
     *     @OA\Response(response="200", description="Um usuario"),
     *
     * )
     */
    public function show(User $user){
        return  response()->json(User::with("roles","laboratories","laboratory")->find($user->id ) ) ;

    }


}

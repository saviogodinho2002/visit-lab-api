<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\HasPreRegistrationPendentException;
use App\Http\Controllers\Controller;
use App\Http\Controllers\RequestSIGAA;
use App\Http\Requests\StorePreRegistrationRequest;
use App\Http\Requests\UpdatePreRegistrationRequest;
use App\Models\Laboratory;
use App\Models\PreRegistration;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Exceptions\RoleDoesNotExist;

class PreRegistrationController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(PreRegistration::class,"pre_registration");
    }
    /**
     * Display a listing of the resource.
     */
    /**
     * @OA\Get(
     *     path="/api/pre-registrations",
     *     tags={"Pre registro"},
     *     summary="Retorna os pre registro feitos pelo usuario",
     *          security={ {"bearerToken":{}} },
     *     @OA\Response(response="200", description="Retorna os pre registro feitos pelo usuario"),
     * )
     */
    public function index(Request $request)
    {
        return response()->json(
            PreRegistration::query()
                ->where("user_id",'=', $request->user()->id)
                ->get());
    }
    /**
     * @OA\Get(
     *     path="/api/pre-registrations/my",
     *     tags={"Pre registro"},
     *     summary="Retorna o pre registro para o usuario",
     *          security={ {"bearerToken":{}} },
     *     @OA\Response(response="200", description="Retorna o pre registro para o usuario"),
     * )
     */
    public function indexMy(Request $request)
    {
        return response()->json(
            PreRegistration::query()
                ->where("login",'=', $request->user()->login)
                ->get());
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Cria um pré registro de usuário
     *
     *
     * @OA\Post(
     *      tags={"Pre registro"},
     *     path="/api/pre-registrations/",
     *     description="Cria um pre registro para proximo login de um usuário",
     *         security={ {"bearerToken":{}} },
     *     @OA\RequestBody(
     *         description="Json informações necessárias",
     *         required=true,
     *      @OA\MediaType(
     *           mediaType="application/json",
     *
     *     @OA\Schema(
     *      type="object",
     *                 required={
     *     "login",
     *       "role_id",
     *
     *
     * },
     *     @OA\Property (
     *         property="login",
     *         description="Login do usuario",
     *          type="string"
     *     ),
     *     @OA\Property (
     *          property="role_id",
     *          description="Role que será atribuída a um usuário",
     *           type="number"
     *      ),
     *     @OA\Property(
     *         property="laboratory_id",
     *
     *         description="Laboratório que será atribuído do usuario",
     *           type="number"
     *     ),
     *
     *
     *     ),
     * ),
     * ),
     *  @OA\Response(
     *    response=200,
     *    description="Registro criado",
     *    @OA\JsonContent(
     *       @OA\Property(property="id", type="string"),
     *    )
     *  ),
     *     @OA\Response(
     *    response=404,
     *    description="Login não encontrado",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string"),
     *
     *    )
     *  )
     *
     * )
     */

    public function store(Request $request)
    {

        $validated = $request->validate(
            [
                "login" => ["required"],

                "role_id" => ["required"],
                "laboratory_id" => ["nullable"]
            ]
        );
        try {
            DB::beginTransaction();
            $info = RequestSIGAA::get_info_user($validated ["login"]);

            if ($this->existsPreRegistrationPendentWith($validated["login"])) {
                throw new HasPreRegistrationPendentException();
            }


            $validated["user_id"] = $request->user()->id;
            $validated["email"] = $info["email"];

            $role = Role::findById($validated["role_id"]);

            if($role->name == "admin" && $request->laboratory_id != 0 && !is_null($request->laboratory_id) ){
                throw new \Exception("Admins não podem ser relacionados a nenhum laboratório diretamente");
            }elseif ($role->name != "admin"  && ($request->laboratory_id == 0  || is_null($request->laboratory_id )) ){
                throw new \Exception("Professores ou monitores devem estar relacionados à algum laboratório");

            }

            if (!is_null($request->laboratory_id ) &&  $request->laboratory_id !=  0  ) {

                Laboratory::query()->findOrFail($request->laboratory_id);
            }



            $register = PreRegistration::create(
                $validated
            );
            DB::commit();
            return response()->json($register);
        } catch (RoleDoesNotExist|\ErrorException|HasPreRegistrationPendentException|\Exception  $e) {
            DB::rollBack();

            if ($e instanceof RoleDoesNotExist)
                return response()->json("Role não encontrada ", 400);
            elseif ($e instanceof \ErrorException)
                return response()->json("Login não encontrado", 400);
            else//($e instanceof HasPreRegistrationPendentException)
                return response()->json($e->getMessage(), 400);
        }

    }
    /**
     * Usuario aceita ou rejeita um cargo
     *
     *
     * @OA\Patch(
     *      tags={"Pre registro"},
     *     path="/api/pre-registrations/{preRegistration}",
     *     description="Atualiza o status de um pre registro",
     *         security={ {"bearerToken":{}} },
     *          @OA\Parameter(
     *          name="preRegistration",
     *          in="path",
     *          description="id do preregistro",
     *          required=true
     *      ),
     *     @OA\RequestBody(
     *         description="Json informações necessárias",
     *         required=true,
     *      @OA\MediaType(
     *           mediaType="application/json",
     *
     *     @OA\Schema(
     *      type="object",
     *                 required={
     *     "response",
     *
     *
     *
     * },
     *     @OA\Property (
     *         property="response",
     *         description="Resposta do usuario",
     *          type="number"
     *     ),

     *
     *     ),
     * ),
     * ),
     *  @OA\Response(
     *    response=201,
     *    description="Atualizado",
     *    @OA\JsonContent(
     *       @OA\Property(property="id", type="string"),
     *    )
     *  ),
     *     @OA\Response(
     *    response=400,
     *    description="formulario invalido",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string"),
     *
     *    )
     *  )
     *
     * )
     */

    public function acceptOrReject(Request $request, PreRegistration $preRegistration){
        if($preRegistration->status != "p"){
            return response()->json("Esse pré registro ja foi ".($preRegistration->status == "a"?"aceito":"rejeitado"), 400);
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
                    $role = \Spatie\Permission\Models\Role::findById($preRegistration->role_id);
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

        return response()->json([
            "message"=>"Atualizado com sucesso",
            "preRegistration"=>$preRegistration
        ], 201);
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


    public function show(PreRegistration $preRegistration)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PreRegistration $preRegistration)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePreRegistrationRequest $request, PreRegistration $preRegistration)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PreRegistration $preRegistration)
    {
        //
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\RequestSIGAA;
use App\Http\Requests\StorePreRegistrationRequest;
use App\Http\Requests\UpdatePreRegistrationRequest;
use App\Models\PreRegistration;
use Illuminate\Http\Request;

class PreRegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(PreRegistration::get());
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
     *     path="api/pre-registration/store",
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
                "login"=>["required"],

                "role_id"=>["required"],
                "laboratory_id"=>[]
            ]
        );
        try {
            $info = RequestSIGAA::get_info_user($validated ["login"]);
            $validated["user_id"] = $request->user()->id;
            $validated["email"] = $info["email"];
            $register  =  PreRegistration::create(
                $validated
            );

            return response()->json($register);
        }catch (\Throwable $e){
            return response()->json("Login não encontrado",403);

        }

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

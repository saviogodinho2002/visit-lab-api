<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLaboratoryRequest;
use App\Http\Requests\UpdateLaboratoryRequest;
use App\Models\Laboratory;
use Illuminate\Http\Request;

class LaboratoryController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Laboratory::class,"laboratory");
    }
    /**
     * Display a listing of the resource.
     */
    /**
     * @OA\Get(
     *     path="/api/laboratory",
     *     tags={"Laboratorio"},
     *     summary="Retorna os laboratorios. Usuario deve ser um professor ou administrador. Se for um professor, vai retornar apenas os laboratórios que ele é coordenador",
     *          security={ {"bearerToken":{}} },
     *     @OA\Response(response="200", description="Retorna os laboratórios"),
     * )
     */
    public function index()
    {
        return response()->json(
            Laboratory::query()
            ->own()
            ->get()
        )   ;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * Cria um laboratório
     *
     *
     * @OA\Post(
     *      tags={"Laboratorio"},
     *     path="/api/laboratory/",
     *     description="Cria um laboratorio",
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
     *     "name",
     *       "local",
     *
     *
     *
     * },
     *     @OA\Property (
     *         property="name",
     *         description="nome do laboratório",
     *          type="string"
     *     ),
     *     @OA\Property (
     *          property="local",
     *          description="Local com predio e sala do laboratorio",
     *           type="string"
     *      ),

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

    public function store(StoreLaboratoryRequest $request)
    {
        $validated = $request->validated();
        //$validated["user_id"] = null;
        //$user_id = $validated["user_id"];

        $validated["user_id"] = null;
        $lab = Laboratory::create($validated);
       /* if( !is_null($user_id) ){

            $response = route("pre-registration.store",
                [
                    "user_id"=>$user_id
                ]
            );
        }*/

        return response()->json($lab,200);

    }

    /**
     * Display the specified resource.
     */
    /**
     * @OA\Get(
     *     path="/api/laboratory/{laboratory}",
     *     summary="Obtém um laboratorio pelo id",
     *     tags={"Laboratorio"},
     *          security={ {"bearerToken":{}} },
     *     @OA\Parameter(
     *         name="laboratory",
     *         in="path",
     *         description="Id do laboratório",
     *         required=true
     *     ),
     *     @OA\Response(response="200", description="Pega um laboratorio"),
     *
     * )
     */
    public function show(Laboratory $laboratory)
    {
        return response()->json($laboratory,200);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Laboratory $laboratory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * Edita um laboratório
     *
     *
     * @OA\Put(
     *      tags={"Laboratorio"},
     *     path="/api/laboratory/{laboratory}",
     *     description="Atualiza um laboratorio",
     *         security={ {"bearerToken":{}} },
     *          @OA\Parameter(
     *          name="laboratory",
     *          in="path",
     *          description="Id do laboratório",
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
     *     "name",
     *       "local",
     *
     *
     *
     * },
     *     @OA\Property (
     *         property="name",
     *         description="nome do laboratório",
     *          type="string"
     *     ),
     *     @OA\Property (
     *          property="local",
     *          description="Local com predio e sala do laboratorio",
     *           type="string"
     *      ),
     *     @OA\Property(
     *         property="login",
     *
     *         description="Login pra qual será criado uma solicitação para o usuário ser coordenador",
     *           type="number"
     *     ),
     *
     *
     *     ),
     * ),
     * ),
     *  @OA\Response(
     *    response=201,
     *    description="Registro atualizado",
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

    public function update(UpdateLaboratoryRequest $request, Laboratory $laboratory)
    {
        $validated = $request->validated();
        $validated["user_id"] = null;
         $laboratory->update($validated);

        return response()->json($laboratory,201);
    }

    /**
     * Remove the specified resource from storage.
     */
    /**
     * @OA\Delete(
     *     path="/api/laboratory/{laboratory}",
     *     summary="Deleta um laboratorio pelo id",
     *     tags={"Laboratorio"},
     *          security={ {"bearerToken":{}} },
     *     @OA\Parameter(
     *         name="laboratory",
     *         in="path",
     *         description="Id do laboratório",
     *         required=true
     *     ),
     *     @OA\Response(response="200", description="Laboratorio deletado"),
     *     @OA\Response(response="400", description="Erro"),
     * )
     */
    public function destroy(Laboratory $laboratory)
    {
        if($laboratory->coordinator()->exists() || $laboratory->monitors()->exists()){
            return response()->json("O laboratório possui coordenador ou monitores relacionados e não pode ser excluído", 400);

        }
        $laboratory->delete();
        return response()->json("Laboratorio deletado",200);

    }
}

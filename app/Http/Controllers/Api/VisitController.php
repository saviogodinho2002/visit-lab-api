<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVisitRequest;
use App\Http\Requests\UpdateVisitRequest;
use App\Models\Laboratory;
use App\Models\Visit;
use Illuminate\Validation\Rule;

class VisitController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Visit::class,"visit");
    }
    /**
     * Display a listing of the resource.
     */
    /**
     * @OA\Get(
     *     path="/api/visits",
     *     tags={"Visitas"},
     *     summary="Retorna as visitas dos laboratorios",
     *          security={ {"bearerToken":{}} },
     *     @OA\Response(response="200", description="Retorna as visitas dos laboratorios"),
     * )
     */
    public function index()
    {
        $visits =
            Visit::query()
                ->with(["laboratory"])
            ->own()
            ->get();
        return response()->json($visits);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * Cria uma visita.
     *
     *
     * @OA\Post(
     *      tags={"Visitas"},
     *     path="/api/visit",
     *     description="Cria uma visita",
     *         security={ {"bearerToken":{}} },
     *     @OA\RequestBody(
     *         description="Json com informações necessárias",
     *         required=true,
     *      @OA\MediaType(
     *           mediaType="application/json",
     *
     *     @OA\Schema(
    type="object",
     *                 required={
     *     "visitor_name",
     *     "visitor_document",
     *
     *
     *
     * },
     *     @OA\Property (
     *         property="visitor_name",
     *         description="Nome do visitante",
     *          type="string"
     *     ),

     *     @OA\Property(
     *         property="visitor_document",
     *
     *         description="Identificação do visitante",
     *           type="string"
     *     ),
     *          @OA\Property(
     *          property="laboratory_id",
     *
     *          description="id do laboratorio onde foi feita visita",
     *            type="string"
     *      ),

     *
     *
     *     ),
     * ),
     * ),
     *  @OA\Response(
     *    response=200,
     *    description="Criado",
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
    public function store(StoreVisitRequest $request)
    {
        $validated = $request->validated();
        try {
            if($request->user()->hasRole(["monitor"])&& isset($validated["laboratory_id"])){
                throw new \ErrorException("Monitores não escolhe laboratório");
            }
            if($request->user()->hasRole(["monitor"])){

                $validated["laboratory_id"] = $request->user()->laboratory_id;
            }else{

                $lab = Laboratory::query()->find( $validated["laboratory_id"]);
                if($lab->user_id != $request->user()->id){
                    throw new \ErrorException("O professor deve gerir o laboratorio escolhido");

                }
            }
            date_default_timezone_set("America/Santarem");
            $validated["entry_at"] = date("Y-m-d H:i:s");
            $validated["user_id"] =$request->user()->id;
            return response()->json(
                Visit::create(
                    $validated
                )
            );

        }catch (\ErrorException $e){
            return response()->json($e->getMessage(), 400);

        }

    }

    /**
     * Display the specified resource.
     */
    /**
     * @OA\Get(
     *     path="/api/visits/{visit}",
     *     summary="Obtém uma visita pelo id",
     *     tags={"Visitas"},
     *          security={ {"bearerToken":{}} },
     *     @OA\Parameter(
     *         name="visit",
     *         in="path",
     *         description="Id da visita",
     *         required=true
     *     ),
     *     @OA\Response(response="200", description="Pega um laboratorio"),
     *
     * )
     */
    public function show(Visit $visit)
    {
        return response()->json($visit);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Visit $visit)
    {
        //
    }

    /**
     * Atualiza uma visita.
     *
     * @OA\Patch(
     *      tags={"Visitas"},
     *      path="/api/visits/{visit}",
     *      description="Atualiza uma visita, colocando a data e hora de saída",
     *      security={ {"bearerToken":{}} },
     *      @OA\Parameter(
     *          name="visit",
     *          in="path",
     *          description="Id da visita",
     *          required=true
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Atualizado",
     *          @OA\JsonContent(
     *              @OA\Property(property="token", type="string"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Formulário inválido",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string"),
     *              @OA\Property(property="message", type="string"),
     *          )
     *      )
     * )
     */

    public function update(UpdateVisitRequest $request, Visit $visit)
    {
        if(!is_null($visit->out_at)){
            return response()->json("Essa visita ja foi atualizada", 400);
        }
        date_default_timezone_set("America/Santarem");

        $visit->update(
            [
                "out_at"=>date("Y-m-d H:i:s")
            ]
        );
        return response()->json($visit);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Visit $visit)
    {
        //
    }
}

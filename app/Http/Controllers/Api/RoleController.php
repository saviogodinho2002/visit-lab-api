<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/role",
     *     tags={"Roles"},
     *     summary="Retorna as roles do sistema",
     *          security={ {"bearerToken":{}} },
     *     @OA\Response(response="200", description="roles do sistema"),
     * )
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        return response()->json(Role::get());
    }
}

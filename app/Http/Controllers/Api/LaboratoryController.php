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
    public function store(StoreLaboratoryRequest $request)
    {
        $validated = $request->validated();
        $validated["user_id"] = null;
        $lab = Laboratory::create($validated);

        return response()->json($lab,200);

    }

    /**
     * Display the specified resource.
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
    public function destroy(Laboratory $laboratory)
    {
        //
    }
}

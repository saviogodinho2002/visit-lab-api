<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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


    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                "login"=>["required"],
                "email"=>["required"],
                "role_id"=>["required"],
                "laboratory_id"=>[]
            ]
        );
        $register  =  PreRegistration::create(
           $validated
        );

       return response()->json($register);
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

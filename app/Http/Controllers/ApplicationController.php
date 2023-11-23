<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreApplicationRequest;
use App\Http\Requests\UpdateApplicationRequest;
use App\Models\Application;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use function Termwind\render;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if(!Auth::user()->hasRole("admin")){
            return redirect()->back();
        }
        return Inertia::render("Application/CreateApplication");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreApplicationRequest $request)
    {
        $validated = $request->validated();
        $validated["key"] = uniqid();
        $validated["user_id"] = $request->user()->id;
        Application::create($validated);

        return redirect()->route("dashboard");
    }

    /**
     * Display the specified resource.
     */
    public function show(Application $application)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Application $application)
    {
        if(!Auth::user()->hasRole("admin")){
            return redirect()->back();
        }
        return Inertia::render("Application/EditApplication",["application"=>  $application]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateApplicationRequest $request, Application $application)
    {
        $validated = $request->validated();
        $application->update($validated);
        return redirect()->route("dashboard");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Application $application)
    {
      $application->logs()->get()->each->delete();
      $application->delete();

      return redirect()->route("dashboard");


    }
}

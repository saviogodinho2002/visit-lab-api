<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pre_registrations', function (Blueprint $table) {
            $table->id();
            $table->string("login");
            $table->string("email");
            $table->enum("status",["p","a","r"])->default("p");
            $table->foreignIdFor(\App\Models\Role::class)->constrained();//cargo
            $table->foreignIdFor(\App\Models\Laboratory::class)->nullable()->constrained();
            $table->foreignIdFor(\App\Models\User::class)->nullable()->constrained();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pre_registrations');
    }
};

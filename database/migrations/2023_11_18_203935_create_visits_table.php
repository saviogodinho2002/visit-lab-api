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
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->string("visitor_name");
            $table->string("visitor_document");

            $table->foreignIdFor(\App\Models\Laboratory::class)
                ->constrained();
            $table->foreignIdFor(\App\Models\User::class)
                ->constrained();

            $table->dateTime("entry_at");
            $table->dateTime("out_at")
                ->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};

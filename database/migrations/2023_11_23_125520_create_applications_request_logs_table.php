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
        Schema::create('applications_request_logs', function (Blueprint $table) {
            $table->id();
            $table->string('method');
            $table->string('path');
            $table->json('query_parameters')->nullable();
            $table->json('headers')->nullable();
            $table->ipAddress('ip');
            $table->text('user_agent')->nullable();
            $table->foreignIdFor(\App\Models\Application::class)
                ->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications_request_logs');
    }
};

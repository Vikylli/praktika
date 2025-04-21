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
        Schema::create('spacecraft_crew', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('spacecraft_id');
            $table->unsignedBigInteger('crew_member_id');
            $table->string('role', 255);
            $table->timestamps();

            $table->foreign('spacecraft_id')->references('id')->on('spacecrafts')->onDelete('cascade');
            $table->foreign('crew_member_id')->references('id')->on('crew_members')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spacecraft_crew');
    }
};

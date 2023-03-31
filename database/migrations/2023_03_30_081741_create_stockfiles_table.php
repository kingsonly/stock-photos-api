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
        Schema::create('stock_files', function (Blueprint $table) {
            $table->id();
            $table->integer("user_id");
            $table->string("file_path");
            $table->string("file_type");
            $table->string("location")->nullable();
            $table->string("name")->nullable();
            $table->integer("views")->nullable();
            $table->timestamps();
        });
    }

    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stockfiles');
    }
};

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
        Schema::create('divbelongtos', function (Blueprint $table) {
            $table->increments("divbelongtoid");
            $table->integer("empid");
            $table->integer("divid");
            $table->enum("usertype",["normal","chief"]);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('divbelongtos');
    }
};

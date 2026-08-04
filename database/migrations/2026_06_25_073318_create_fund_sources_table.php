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
        Schema::create('fund_sources', function (Blueprint $table) {
            $table->increments("fsrcid");
			$table->string("fundname");
			$table->string("fundvalue");
			$table->string("yearactive");
			$table->integer("divisionid");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fund_sources');
    }
};

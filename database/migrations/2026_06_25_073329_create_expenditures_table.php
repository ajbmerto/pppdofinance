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
        Schema::create('expenditures', function (Blueprint $table) {
            $table->increments("expid");
			$table->integer("fsrcidfk");
			$table->string("name");
			$table->string("fundvalue");
			$table->date("startdate");
			$table->date("enddate");
			$table->enum("expendituretype",["obligated","underproc","Planning stage","Pre-procurement","undef"])->default("undef");
            $table->enum("expdeep",["Market Scoping","Activity Design","AWFP/APP/PPMP","abc","pr","rfq","posting","bidding","bac reso","ntp/noa","po","Activity done","liquidation done"]);
			$table->enum("qtr",["1st","2nd","3rd","4th","undef"])->default("undef");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenditures');
    }
};

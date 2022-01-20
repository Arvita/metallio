<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_exams', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('id_exam');            
            $table->uuid('id_question');
            $table->uuid('id_answer');
            $table->tinyInteger('complete');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detail_exams');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailCreateExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_create_exams', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('id_create_exam');
            $table->uuid('id_bank_question');
            $table->uuid('id_detail_bank_question');
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
        Schema::dropIfExists('detail_create_exams');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('test_num')->nullable();
            $table->string('name');
            $table->string('duration');
            $table->string('degree');
            $table->bigInteger('stage_id')->unsigned();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('stage_id')
                ->references('id')
                ->on('stages')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tests');
    }
}

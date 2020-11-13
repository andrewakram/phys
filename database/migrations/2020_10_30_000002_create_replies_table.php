<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRepliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('replies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('answer')->nullable();
            $table->string('description')->nullable();
            $table->string('image')->nullable();
            $table->tinyInteger('is_true')->default(0);
            $table->bigInteger('querry_id')->unsigned();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('querry_id')
                ->references('id')
                ->on('querries')
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
        Schema::dropIfExists('replies');
    }
}

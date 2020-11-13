<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersQuerriesResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_querries_results', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('querry_id')->unsigned();
            $table->bigInteger('reply_id')->unsigned();
            $table->bigInteger('correct_reply_id')->unsigned();
            $table->bigInteger('group_test_id')->unsigned();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('querry_id')
                ->references('id')
                ->on('querries')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('reply_id')
                ->references('id')
                ->on('replies')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('correct_reply_id')
                ->references('id')
                ->on('replies')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('group_test_id')
                ->references('id')
                ->on('groups_tests')
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
        Schema::dropIfExists('users_querries_results');
    }
}

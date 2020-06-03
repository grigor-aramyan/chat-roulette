<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRemainingColumnsToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('purpose');
            // want to connect status
            $table->string('status');
            $table->string('username');
            $table->integer('age');
            $table->string('sex');
            $table->text('city');
            $table->string('photo')->nullable(true);
            $table->string('question_one');
            $table->string('question_two');
            $table->string('question_three');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['purpose', 'status', 'username', 'age', 'sex', 'city', 'photo', 'question_one', 'question_two', 'question_three']);
        });
    }
}

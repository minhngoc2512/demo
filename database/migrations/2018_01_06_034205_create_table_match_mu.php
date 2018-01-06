<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableMatchMu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //- Matches: date, team(home,away),result, score,competition,season,detail_match.
        Schema::create('match_history', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('date');
            $table->string('home_team');
            $table->string('away_team');
            $table->string('result');
            $table->string('score');
            $table->string('competition');
            $table->string('season');
            $table->text('detail_match')->nullable();
            $table->timestamps();
        });
        //
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('match_history');
        //
    }
}

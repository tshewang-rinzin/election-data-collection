<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('votes', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('constituency_id');
            $table->unsignedBigInteger('party_id');
            $table->integer('evm')->default(0);
            $table->integer('postal_ballot')->default(0);
            $table->foreign('constituency_id')->references('id')->on('constituencies')->onDelete('cascade');
            $table->foreign('party_id')->references('id')->on('parties')->onDelete('cascade');
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
        Schema::dropIfExists('votes');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCandidatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('party_id');
            $table->unsignedBigInteger('constituency_id');
            $table->string('profile_image')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('party_id')->references('id')->on('parties')->onDelete('cascade');
            $table->foreign('constituency_id')->references('id')->on('constituencies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('candidates');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLibrariansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('librarian', function (Blueprint $table) {
            $table->bigInteger('librarian_number');
            $table->string("name");
            $table->string("surname");
            $table->string("email");

            //relationships
            $table->bigInteger("library_id")->unsigned();
            $table->foreign("library_id")->references("id")->on("library")->onDelete("cascade");
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
        Schema::dropIfExists('librarian');
    }
}

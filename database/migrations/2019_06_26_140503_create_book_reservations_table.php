<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book_reservation', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger("book_id");
            $table->bigInteger("count")->default(0);
            $table->bigInteger("librarian_number")->nullable();
            $table->bigInteger("student_number");
            $table->string("status");
            $table->boolean('is_accepted')->default(false);
            $table->boolean('is_rejected')->default(false);

            //relationships
            $table->bigInteger("department_id")->unsigned();
            $table->foreign("department_id")->references("id")->on("department")->onDelete("cascade");
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
        Schema::dropIfExists('book_reservation');
    }
}

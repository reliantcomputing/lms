<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book_request', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger("book_id")->unsigned();
            $table->bigInteger("department_id")->unsigned();
            $table->bigInteger("staff_number");
            $table->string("status");
            $table->boolean("is_accepted")->default(false);
            $table->boolean("is_rejected")->default(false);
            $table->bigInteger('librarian_number')->nullable();
            $table->bigInteger('number_of_books');

            $table->foreign("department_id")->references("id")->on("department")->onDelete("cascade");
            $table->foreign("book_id")->references("id")->on("book")->onDelete("cascade");
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
        Schema::dropIfExists('book_request');
    }
}

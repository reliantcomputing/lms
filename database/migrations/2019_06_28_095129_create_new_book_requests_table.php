<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewBookRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('new_book_request', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger("department_id")->unsigned();
            $table->string("title");
            $table->bigInteger('edition');
            $table->bigInteger("staff_number");
            $table->bigInteger("isbn_number");
            $table->string("author");
            $table->string("status");
            $table->decimal("price")->nullable();
            $table->bigInteger("librarian_number")->nullable();
            $table->text("place_of_publication");
            $table->bigInteger("quantity");
            $table->boolean("library_rejected")->default(false);
            $table->boolean("is_processed")->default(false);
            $table->boolean("library_accepted")->default(false);
            $table->boolean("department_rejected")->default(false);
            $table->boolean("department_accepted")->default(false);
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
        Schema::dropIfExists('new_book_request');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("title");
            $table->bigInteger("isbn_number")->unique();
            $table->string("author");
            $table->string("place_of_publication");
            $table->bigInteger("edition");
            $table->decimal("stock_price");
            $table->decimal("sell_price");
            $table->bigInteger("count")->default(0);

            //relationships
            $table->bigInteger("department_id")->unsigned();
            $table->foreign("department_id")->references("id")->on("department")->onDelete("cascade");
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
        Schema::dropIfExists('book');
    }
}

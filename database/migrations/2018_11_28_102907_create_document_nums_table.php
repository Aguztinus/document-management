<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentNumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_nums', function (Blueprint $table) {
            $table->increments('id');
            $table->string('number', 30);
            $table->string('name', 150);
            $table->boolean('used');
            $table->integer('document_type_id')->unsigned()->index();
            $table->timestamps();

            $table->foreign('document_type_id')->references('id')->on('document_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('document_nums');
    }
}

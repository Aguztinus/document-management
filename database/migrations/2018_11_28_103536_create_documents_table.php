<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('description')->nullable();
            $table->string('file_ext');
            $table->string('url');
            $table->string('size');
            $table->integer('size_int');
            $table->string('slug')->unique();
            $table->string('status');
            $table->integer('owner_id')->unsigned()->index();
            $table->integer('document_type_id')->unsigned()->index();
            $table->integer('unit_id')->unsigned()->index();
            $table->timestamps();

            $table->foreign('owner_id')->references('id')->on('users');
            $table->foreign('document_type_id')->references('id')->on('document_types');
            $table->foreign('unit_id')->references('id')->on('units');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('document_user');
        Schema::dropIfExists('document_reference');
        Schema::dropIfExists('document_history');
        Schema::dropIfExists('documents');
    }
}

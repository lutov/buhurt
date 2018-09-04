<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElementsListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('elements_lists', function (Blueprint $table) {
            $table->increments('id');
			$table->unsignedInteger('element_id');
			$table->string('element_type');
			$table->unsignedInteger('list_id');
			$table->unsignedInteger('user_id');
			$table->foreign('list_id')->references('id')->on('lists')->onDelete('cascade');
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('elements_lists');
    }
}

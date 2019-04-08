<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class KanamaniaFileManagerCreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('Kanamania_file_manager_files');
        Schema::create('Kanamania_file_manager_files', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('original_name');
            $table->string('hash');
            $table->string('ext');
            $table->string('mime');
            $table->string('size');
            $table->enum('status', ['delete', 'keep'])->default('delete');
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
        Schema::dropIfExists('Kanamania_file_manager_files');
    }
}

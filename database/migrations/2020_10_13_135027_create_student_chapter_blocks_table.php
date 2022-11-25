<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentChapterBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_chapter_blocks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longtext('content');
            $table->text('metadatas')->nullable();
            $table->smallInteger('block_type')->nullable();
            $table->bigInteger('chapter_id')->nullable();
            $table->bigInteger('book_id')->nullable();
            $table->integer('note_id')->nullable();
            $table->integer('order')->nullable();
            $table->tinyInteger('is_file')->nullable();
            $table->tinyInteger('active')->nullable();
            $table->integer('user_id')->nullable();
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
        Schema::dropIfExists('student_chapter_blocks');
    }
}

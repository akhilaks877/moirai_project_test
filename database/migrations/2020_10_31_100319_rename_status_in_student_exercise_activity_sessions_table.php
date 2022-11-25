<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameStatusInStudentExerciseActivitySessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_exercise_activity_sessions', function (Blueprint $table) {
            $table->renameColumn('examinaton_status', 'status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_exercise_activity_sessions', function (Blueprint $table) {
            $table->renameColumn('status', 'examinaton_status');
        });
    }
}

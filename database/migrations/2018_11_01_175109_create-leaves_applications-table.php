<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeavesApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave_applications', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('leave_id');
            $table->integer('employee_id');
            $table->date('from');
            $table->date('to');
            $table->smallInteger('leave_days');
            $table->dateTime('applied_on');
            $table->dateTime('granted_on');
            $table->string('status')->default('applied');
            $table->string('application_copy')->nullable();
            $table->string('reason')->nullable();
            $table->integer('port_id');
            $table->timestamps();
           // $table->foreign('leave_id')->references('id')->on('leaves');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leave_applications');
    }
}

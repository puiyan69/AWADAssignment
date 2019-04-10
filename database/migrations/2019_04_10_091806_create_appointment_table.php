<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppointmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->increments('id');
            $table->time('appTime');
            $table->date('appDate');
            $table->string('appReason');
            
            //foreign key 
            $table->unsignedInteger('doctor_id');
            $table->foreign('doctor_id')->references('id')->on('doctors');

            //foreign key

            $table->unsignedInteger('patient_id');
            $table->foreign('patient_id')->references('id')->on('patients');
            
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
        Schema::dropIfExists('appointments');
    }
}

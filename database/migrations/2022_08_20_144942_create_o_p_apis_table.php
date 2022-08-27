<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('o_p_apis', function (Blueprint $table) {
            $table->id();
            $table->string("job_id")->unique();   
            $table->string("client_f_name")->nullable();   
            $table->string("client_l_name")->nullable();   
            $table->string("access_details")->nullable();   
            $table->string("access_person_f_name")->nullable();   
            $table->string("access_person_l_name")->nullable();   
            $table->string("access_person_sms")->nullable();   
            $table->string("access_person_email")->nullable();   
            $table->text("ontraport_link")->nullable();   
            $table->datetime("date_of_inspection")->nullable();   
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
        Schema::dropIfExists('o_p_apis');
    }
};

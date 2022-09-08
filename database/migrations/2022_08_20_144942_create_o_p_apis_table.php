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

            $table->string("inspection_status")->nullable(); 
            $table->string("inspection_status_name")->nullable();
            
            $table->string("suburb_state")->nullable(); 
            $table->string("suburb_lat")->nullable(); 
            $table->string("suburb_long")->nullable(); 

            $table->string("address-1")->nullable();
            $table->string("address-2")->nullable();
            $table->string("postal-code")->nullable();
            $table->string("state")->nullable();
            $table->string("country")->nullable();


            //'suburb_state','suburb_lat','suburb_long'
            

            $table->text("access_details")->nullable();   
            $table->string("access_person_type")->nullable();   
            $table->string("access_person_type_name")->nullable();   

            $table->string("access_person_f_name")->nullable();   
            $table->string("access_person_l_name")->nullable();   
            $table->string("access_person_sms")->nullable();   
            $table->string("access_person_email")->nullable();   

            $table->text("ontraport_link")->nullable();   
            
            $table->string("date_of_inspection")->nullable();   
            //$table->datetime("date_of_inspection")->nullable();   
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

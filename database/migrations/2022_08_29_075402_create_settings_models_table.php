<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\SettingsModel;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings_models', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('start');
            $table->integer('range')->nullable();
            $table->integer('upperLimit')->nullable();
            $table->bigInteger('startCheck')->nullable();
            $table->bigInteger('rangeCheck')->nullable();
            $table->timestamps();
        });
        $settings = new SettingsModel([
            'start' => 1,
            'range' => 10,
            'upperLimit' => 2000,
            'startCheck' => 1,
            'rangeCheck' => 30,
        ]);
        $settings->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings_models');
    }
};

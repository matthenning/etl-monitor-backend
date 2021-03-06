<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSlaStatisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dwh_control_sla__sla_statistics', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('sla_id');
            $table->string('type');

            $table->double('average_lower')->nullable();
            $table->double('average_upper')->nullable();
            $table->json('progress_history')->nullable();
            $table->json('achievement_history')->nullable();

            $table->timestamps();
        });

        Schema::table('dwh_control_sla__sla_statistics', function (Blueprint $table) {
            $table->foreign('sla_id', 'sla_statistics__sla_foreign')->references('id')->on('dwh_control_sla__slas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dwh_control_sla__sla_statistics');
    }
}

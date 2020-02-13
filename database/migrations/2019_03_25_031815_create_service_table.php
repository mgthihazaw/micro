<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::beginTransaction();

        Schema::create('services', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('staff_id')->unsigned();
            $table->integer('service_engineer_id')->unsigned()->nullable();
            $table->integer('customer_id')->unsigned()->nullable();
            $table->integer('business_id')->unsigned();
            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            $table->string('customer_address')->nullable();
            $table->text('received_description')->nullable();
            $table->text('received_remark')->nullable();
            $table->text('service_description')->nullable();
            $table->text('check_results')->nullable();

            $table->boolean('pending')->default(false);

            $table->foreign('business_id')->references('id')->on('businesses')->onDelete('cascade');
            $table->foreign('staff_id')->references('id')->on('staffs')->onDelete('cascade');
            $table->foreign('service_engineer_id')->references('id')->on('staffs')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->timestamp('received_date')->nullable();
            $table->timestamp('finished_date')->nullable();
        });

        DB::commit();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropForeign(['business_id']);
            $table->dropForeign(['staff_id']);
            $table->dropForeign(['service_engineer_id']);
            $table->dropForeign(['customer_id']);
        });
        Schema::dropIfExists('services');
    }
}
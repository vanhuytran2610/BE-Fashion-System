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
        Schema::create('districts', function (Blueprint $table) {
            $table->string('code')->primary();
            $table->string('name');
            $table->string('name_en');
            $table->string('full_name');
            $table->string('full_name_en');
            $table->string('code_name');
            $table->string('province_code');
            $table->unsignedBigInteger('administrative_unit_id');
            $table->timestamps();

            $table->foreign('administrative_unit_id')->references('id')->on('administrative_units')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('province_code')->references('code')->on('provinces')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('districts');
    }
};

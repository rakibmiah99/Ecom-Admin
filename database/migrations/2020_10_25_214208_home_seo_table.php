<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class HomeSeoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site_seo',function (Blueprint $table){
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('des',1000);
            $table->string('keywords',1000);
            $table->string('og_title');
            $table->string('og_des',1000);
            $table->string('og_sitename');
            $table->string('og_url');
            $table->string('og_img');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

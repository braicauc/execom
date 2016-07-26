<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateDirectorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('directors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('categorie',100);
            $table->string('slug',100);
            $table->integer('back_to')->default(0);
            $table->text('settings')->nullable();
            $table->timestamps();
        });

        DB::statement('ALTER TABLE directors ADD FULLTEXT INDEX `slug` (`slug`)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('directors');
    }
}

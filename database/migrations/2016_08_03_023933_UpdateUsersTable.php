<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('users');

        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',100);
            $table->string('username',30)->unique();
            $table->string('password',100);
            $table->string('email',100)->unique();
            $table->string('facebook_id',30);
            $table->string('avatar',100)->nullable();
            $table->enum('gender', ['male', 'female']);
            $table->rememberToken();
            $table->smallInteger('status');
            $table->string('localitatea',100);
            $table->smallInteger('loc_id');
            $table->string('judetul',50);
            $table->smallInteger('judet_id');
            $table->string('telefon',50);
            $table->double('lat')->nullable();
            $table->double('lng')->nullable();
            $table->tinyInteger('role')->default(0);
            $table->timestamp('last_login_at');
            $table->timestamps();
            $table->softDeletes();
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}

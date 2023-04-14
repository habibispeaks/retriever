<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UploadReportItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */


    public function up()
    {
        Schema::create('uploaditem', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('NO ACTION');
            $table->string('itemname');
            $table->string('category');
            $table->date('date');
            $table->time('time');
            $table->string('area');
            $table->string('street');
            $table->string('city');
            $table->string('file');
            $table->string('description');
            $table->timestamps();
        });

        Schema::create('reportitem', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('itemname');
            $table->string('category');
            $table->date('date');
            $table->time('time');
            $table->string('area');
            $table->string('street');
            $table->string('city');
            $table->string('file');
            $table->string('description');
            // $table->timestamp('created_at')->useCurrent();
            // $table->timestamp('updated_at')->useCurrent();
            $table->timestamps();
        });

        Schema::create('feedback', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('name');
            $table->string('phone');
            $table->string('email');
            $table->string('comment');
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
        Schema::dropIfExists('uploaditem');

        Schema::dropIfExists('reportitem');

        Schema::dropIfExists('feedback');

    }
}

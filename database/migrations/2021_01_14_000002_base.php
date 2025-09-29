<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Base extends Migration
{
    public function up()
    {
        // Código para crear todas las tablas, incluyendo `configurations` y `devices`
        Schema::create('configurations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('key')->unique();
            $table->string('value')->nullable();
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('locale')->default('en');
            $table->string('timezone')->default('UTC');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('devices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->string('name');
            $table->integer('position_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('device_attributes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('device_id')->unsigned()->index();
            $table->string('key');
            $table->string('value')->nullable();
            $table->timestamps();
        });

        Schema::create('positions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('device_id')->unsigned()->index();
            $table->string('protocol')->nullable();
            $table->boolean('valid')->default(false);
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->decimal('altitude', 10, 2)->default(0);
            $table->decimal('speed', 6, 2)->default(0);
            $table->decimal('course', 6, 2)->default(0);
            $table->text('attributes')->nullable();
            $table->dateTime('fix_time');
            $table->timestamps();
        });

        Schema::create('device_sensors', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('device_id')->unsigned()->index();
            $table->string('key');
            $table->string('name')->nullable();
            $table->string('unit')->nullable();
            $table->string('formula')->nullable();
            $table->integer('type')->default(0);
            $table->timestamps();
        });

        Schema::create('device_alarms', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('device_id')->unsigned()->index();
            $table->string('key');
            $table->integer('type')->default(0);
            $table->timestamps();
        });

        Schema::create('commands', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('device_id')->unsigned()->index();
            $table->string('type')->index();
            $table->text('message')->nullable();
            $table->timestamps();
        });

        Schema::create('user_alerts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->integer('device_id')->unsigned()->index();
            $table->string('type');
            $table->timestamps();
        });

        Schema::table('devices', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('device_attributes', function (Blueprint $table) {
            $table->foreign('device_id')->references('id')->on('devices')->onDelete('cascade');
        });

        Schema::table('positions', function (Blueprint $table) {
            $table->foreign('device_id')->references('id')->on('devices')->onDelete('cascade');
        });

        Schema::table('device_sensors', function (Blueprint $table) {
            $table->foreign('device_id')->references('id')->on('devices')->onDelete('cascade');
        });

        Schema::table('device_alarms', function (Blueprint $table) {
            $table->foreign('device_id')->references('id')->on('devices')->onDelete('cascade');
        });

        Schema::table('commands', function (Blueprint $table) {
            $table->foreign('device_id')->references('id')->on('devices')->onDelete('cascade');
        });

        Schema::table('user_alerts', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('device_id')->references('id')->on('devices')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_alerts');
        Schema::dropIfExists('commands');
        Schema::dropIfExists('device_alarms');
        Schema::dropIfExists('device_sensors');
        Schema::dropIfExists('positions');
        Schema::dropIfExists('device_attributes');
        Schema::dropIfExists('devices');
        Schema::dropIfExists('users');
        Schema::dropIfExists('configurations');
    }
}
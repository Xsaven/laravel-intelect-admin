<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLiaTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $connection = config('lia.database.connection') ?: config('database.default');

        Schema::connection($connection)->create(config('lia.database.users_table'), function (Blueprint $table) {
            $table->increments('id');
            $table->string('username', 190)->unique();
            $table->string('password', 60);
            $table->string('name');
            $table->string('avatar')->nullable();
            $table->string('remember_token', 100)->nullable();
            $table->timestamps();
        });

        Schema::connection($connection)->create(config('lia.database.roles_table'), function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50)->unique();
            $table->string('slug', 50);
            $table->timestamps();
        });

        Schema::connection($connection)->create(config('lia.database.permissions_table'), function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50)->unique();
            $table->string('slug', 50);
            $table->string('http_method')->nullable();
            $table->text('http_path')->nullable();
            $table->timestamps();
        });

        Schema::connection($connection)->create(config('lia.database.menu_table'), function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->default(0);
            $table->integer('order')->default(0);
            $table->string('title', 50);
            $table->string('icon', 50);
            $table->string('uri', 50)->nullable();
            $table->string('type', 50);
            $table->string('hotkey', 50)->nullable();

            $table->timestamps();
        });

        Schema::connection($connection)->create(config('lia.database.role_users_table'), function (Blueprint $table) {
            $table->integer('role_id');
            $table->integer('user_id');
            $table->index(['role_id', 'user_id']);
            $table->timestamps();
        });

        Schema::connection($connection)->create(config('lia.database.role_permissions_table'), function (Blueprint $table) {
            $table->integer('role_id');
            $table->integer('permission_id');
            $table->index(['role_id', 'permission_id']);
            $table->timestamps();
        });

        Schema::connection($connection)->create(config('lia.database.user_permissions_table'), function (Blueprint $table) {
            $table->integer('user_id');
            $table->integer('permission_id');
            $table->index(['user_id', 'permission_id']);
            $table->timestamps();
        });

        Schema::connection($connection)->create(config('lia.database.role_menu_table'), function (Blueprint $table) {
            $table->integer('role_id');
            $table->integer('menu_id');
            $table->index(['role_id', 'menu_id']);
            $table->timestamps();
        });

        Schema::connection($connection)->create(config('lia.database.operation_log_table'), function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('path');
            $table->string('method', 10);
            $table->string('ip', 15);
            $table->text('input');
            $table->index('user_id');
            $table->timestamps();
        });

        Schema::connection($connection)->create(config('lia.database.reporter_table'), function (Blueprint $table) {
            $table->increments('id');
            $table->string('type');
            $table->string('code');
            $table->string('message');
            $table->string('file');
            $table->integer('line');
            $table->text('trace');
            $table->string('method');
            $table->string('path');
            $table->text('query');
            $table->text('body');
            $table->text('cookies');
            $table->text('headers');
            $table->string('ip');
            $table->timestamps();
        });
        Schema::connection($connection)->create(config('lia.database.translate_manager'), function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('status')->default(0);
            $table->string('locale');
            $table->string('group');
            $table->string('key');
            $table->text('value')->nullable();
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
        $connection = config('lia.database.connection') ?: config('database.default');

        Schema::connection($connection)->dropIfExists(config('lia.database.users_table'));
        Schema::connection($connection)->dropIfExists(config('lia.database.roles_table'));
        Schema::connection($connection)->dropIfExists(config('lia.database.permissions_table'));
        Schema::connection($connection)->dropIfExists(config('lia.database.menu_table'));
        Schema::connection($connection)->dropIfExists(config('lia.database.user_permissions_table'));
        Schema::connection($connection)->dropIfExists(config('lia.database.role_users_table'));
        Schema::connection($connection)->dropIfExists(config('lia.database.role_permissions_table'));
        Schema::connection($connection)->dropIfExists(config('lia.database.role_menu_table'));
        Schema::connection($connection)->dropIfExists(config('lia.database.operation_log_table'));
        Schema::connection($connection)->dropIfExists(config('lia.database.reporter_table'));
        Schema::connection($connection)->dropIfExists(config('lia.database.translate_manager'));
    }
}

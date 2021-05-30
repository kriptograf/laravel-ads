<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateBannersTable
 * @author Виталий Москвин <foreach@mail.ru>
 */
class CreateBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->integer('category_id')->references('id')->on('categories');
            $table->integer('region_id')->nullable()->references('id')->on('regions');
            $table->string('name');
            $table->integer('views')->nullable();
            $table->integer('limit');
            $table->integer('clicks')->nullable();
            $table->integer('cost')->nullable();
            $table->string('url');
            $table->string('format');
            $table->string('file');
            $table->string('status', 16);
            $table->timestamp('published_at')->nullable();
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
        Schema::dropIfExists('banners');
    }
}

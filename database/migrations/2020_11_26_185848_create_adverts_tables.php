<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvertsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adverts', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->integer('category_id')->references('id')->on('categories')->onDelete('CASCADE');
            $table->integer('region_id')->nullable()->references('id')->on('regions')->onDelete('CASCADE');
            $table->string('title');
            $table->integer('price');
            $table->text('address');
            $table->text('content');
            $table->string('status', 16);
            $table->text('reject_reason')->nullable();
            $table->timestamps();
            $table->timestamp('published_at')->nullable();
            $table->timestamp('expires_at')->nullable();
        });

        Schema::create('advert_values', function (Blueprint $table) {
            $table->integer('advert_id')->references('id')->on('adverts')->onDelete('CASCADE');
            $table->integer('attribute_id')->references('id')->on('attributes')->onDelete('CASCADE');
            $table->string('value');
            $table->primary(['advert_id', 'attribute_id']);
        });

        Schema::create('advert_photos', function (Blueprint $table) {
            $table->id();
            $table->integer('advert_id')->references('id')->on('adverts')->onDelete('CASCADE');
            $table->string('file');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('adverts');
        Schema::dropIfExists('advert_values');
        Schema::dropIfExists('advert_photos');
    }
}

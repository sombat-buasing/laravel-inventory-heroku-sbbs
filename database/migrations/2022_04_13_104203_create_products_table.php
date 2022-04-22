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
        Schema::create('products', function (Blueprint $table) {

            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';

            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->string('description')->nullable();
            $table->decimal('price',9, 2); // 2,859,893.50
            $table->string('image')->nullable(); 
            $table->unsignedBigInteger('user_id')->comment('Created By user');
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('products');
    }
};

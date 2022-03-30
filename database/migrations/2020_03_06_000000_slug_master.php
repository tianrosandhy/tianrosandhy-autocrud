<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //struktur migrate dari database lama nggak dipakai.
        Schema::create('slug_masters', function (Blueprint $table) {
            $table->increments('id');
            $table->string('table', 50)->nullable();
            $table->string('primary_key', 50)->nullable(); //jaga2 kalo ada yg PK non integer
            $table->string('slug', 190)->nullable();
            $table->string('language', 20)->nullable();
            $table->timestamps();

            $table->index(['table', 'primary_key']);
            $table->index('slug');
            $table->index('language');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('slug_masters');
    }
};
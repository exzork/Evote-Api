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
        Schema::create('votes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('event_id')->references('id')->on('events');
            $table->foreignUuid('voter_id')->references('id')->on('voters');
            $table->json('image_paths')->nullable(); // array of images paths
            $table->json('image_urls')->nullable(); // array of images urls
            $table->json('votes'); // JSON array of vote options
            $table->boolean('is_valid')->nullable();
            $table->unique(['event_id', 'voter_id']);
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
        Schema::dropIfExists('votes');
    }
};

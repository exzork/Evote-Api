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
        Schema::create('candidates', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('election_id')->references('id')->on('elections');
            $table->foreignUuid('leader_id')->references('id')->on('users');
            $table->foreignUuid('vice_leader_id')->nullable()->references('id')->on('users');
            $table->longText('description');
            $table->string('image_path')->nullable();
            $table->string('image_url');
            $table->integer('votes')->default(0);
            $table->foreignUuid('created_by')->references('id')->on('committees');
            $table->foreignUuid('updated_by')->nullable()->references('id')->on('committees');
            $table->softDeletes();
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
        Schema::dropIfExists('candidates');
    }
};

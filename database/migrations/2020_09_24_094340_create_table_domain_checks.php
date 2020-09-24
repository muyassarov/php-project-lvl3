<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableDomainChecks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('domain_checks', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->foreignId('domain_id');
            $table->unsignedTinyInteger('status_code')->nullable();
            $table->string('h1', 255)->nullable();
            $table->text('keywords')->nullable();
            $table->text('description')->nullable();
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
        Schema::dropIfExists('domain_checks');
    }
}

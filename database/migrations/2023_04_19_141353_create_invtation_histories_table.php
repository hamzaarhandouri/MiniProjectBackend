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
        Schema::create('invtation_histories', function (Blueprint $table) {
            $table->id();
            $table->text('status');
            $table->foreignId('company_id')->nullable()
            ->constrained('companies')
            ->onDelete('cascade');
            $table->foreignId('userSender_id')->nullable()
            ->constrained('users')
            ->onDelete('cascade');
            $table->foreignId('userRece_id')->nullable()
            ->constrained('users')
            ->onDelete('cascade');
            $table->foreignId('invitaion_id')->nullable()
            ->constrained('invitations')
            ->onDelete('cascade');
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
        Schema::dropIfExists('invtation_histories');
    }
};

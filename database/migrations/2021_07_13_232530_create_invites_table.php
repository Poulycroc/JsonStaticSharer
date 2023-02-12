<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invites', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code');
            $table->string('email')->nullable();
            $table->string('type')->nullable();
            $table->nullableMorphs('creator');
            $table->timestamp('claimed_at')->nullable();
            $table->longText('message')->nullable();
            $table->integer('claimer_id')->unsigned()->index()->nullable();
            $table->string('claimer_type')->nullable();
            $table->nullableMorphs('object');
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
        Schema::dropIfExists('invites');
    }
}

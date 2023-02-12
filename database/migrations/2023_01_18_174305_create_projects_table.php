<?php

use App\Traits\HasUuid;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    use HasUuid;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('api_key', 64)->nullable();
            $table->longText('description')->nullable();
            $table->dateTime('archived_at')->nullable();
            $table->string('slug')->nullable();
            $table->timestamps();
        });

        $this->createUuidField('projects');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
};

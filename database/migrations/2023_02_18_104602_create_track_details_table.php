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
        Schema::create('track_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_track')->constrained('tracks','id');
            $table->foreignId('id_biota')->constrained('biotas','id');
            $table->foreignId('id_lokasi')->constrained('lokasis','id');
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->text('image');
            $table->text('keterangan');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('track_details');
    }
};

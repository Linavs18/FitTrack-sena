<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('activity', function (Blueprint $table) {
            $table->id()->comment('Id de la actividad');
            $table->foreignId('user_id')->constrained('users')
            ->onDelete('cascade')
            ->onUpdate('cascade')
            ->comment('Fk de tabla users');
            $table->string('type_activity')->comment('tipo de actividad');
            $table->date('date');
            $table->time('time');
            $table->decimal('distance', 6, 2)->nullable();
            $table->unsignedInteger('calories')->nullable();;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity');
    }
};

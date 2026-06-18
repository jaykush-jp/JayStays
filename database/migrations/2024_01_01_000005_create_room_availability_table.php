<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('room_availability', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->boolean('is_blocked')->default(false);
            $table->string('block_reason')->nullable();
            $table->decimal('custom_price', 10, 2)->nullable();
            $table->timestamps();
            $table->unique(['room_id', 'date']);
        });
    }
    public function down(): void { Schema::dropIfExists('room_availability'); }
};

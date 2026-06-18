<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('stay_type', ['hourly', 'overnight', 'both'])->default('both');
            $table->decimal('hourly_price', 10, 2)->nullable();
            $table->unsignedTinyInteger('min_hours')->default(2);
            $table->decimal('overnight_price', 10, 2)->nullable();
            $table->decimal('price_3hr', 10, 2)->nullable();
            $table->decimal('price_6hr', 10, 2)->nullable();
            $table->decimal('price_12hr', 10, 2)->nullable();
            $table->unsignedTinyInteger('capacity')->default(2);
            $table->json('amenities')->nullable();
            $table->json('images')->nullable();
            $table->boolean('is_available')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }
    public function down(): void { Schema::dropIfExists('rooms'); }
};

<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // hotel_owner
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('city');
            $table->string('area')->nullable();
            $table->text('address');
            $table->text('description')->nullable();
            $table->unsignedTinyInteger('star_rating')->default(3);
            $table->json('amenities')->nullable();
            $table->json('images')->nullable();
            $table->string('cover_image')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->enum('listing_priority', ['top', 'middle', 'lower'])->default('middle');
            $table->unsignedInteger('listing_order')->default(100);
            $table->decimal('commission_percent', 5, 2)->nullable();
            $table->enum('status', ['pending', 'active', 'inactive', 'rejected'])->default('pending');
            $table->string('rejection_reason')->nullable();
            $table->decimal('avg_rating', 3, 2)->default(0);
            $table->unsignedInteger('total_reviews')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('couple_friendly')->default(true);
            $table->boolean('accepts_local_id')->default(true);
            $table->json('tags')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['city', 'status']);
            $table->index(['listing_priority', 'listing_order']);
        });
    }
    public function down(): void { Schema::dropIfExists('hotels'); }
};

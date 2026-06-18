<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('code', 30)->unique();
            $table->enum('type', ['percentage', 'fixed'])->default('percentage');
            $table->decimal('discount', 10, 2);
            $table->decimal('max_discount', 10, 2)->nullable();
            $table->decimal('min_amount', 10, 2)->default(0);
            $table->enum('stay_type', ['hourly', 'overnight', 'both'])->default('both');
            $table->unsignedInteger('usage_limit')->nullable();
            $table->unsignedInteger('used_count')->default(0);
            $table->date('valid_from')->nullable();
            $table->date('valid_to')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('offers'); }
};

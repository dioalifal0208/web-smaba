<?php

use App\Enums\ContentStatus;
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
        Schema::create('digital_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('url', 2048)->nullable();
            $table->string('category')->nullable();
            $table->string('publication_status')->default(ContentStatus::Draft->value);
            $table->string('operational_status');
            $table->string('access_type');
            $table->boolean('show_on_homepage')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->dateTime('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['publication_status', 'published_at']);
            $table->index(['show_on_homepage', 'sort_order']);
            $table->index('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('digital_services');
    }
};

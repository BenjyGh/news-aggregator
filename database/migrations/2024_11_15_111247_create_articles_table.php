<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->mediumText('content');
            $table->text('url')->nullable();
            $table->text('image_url')->nullable();

            $table->foreignId('news_source_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->foreignId('author_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->foreignId('category_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->timestamp('published_at');
            $table->timestamps();

            $table->fullText(['title', 'content']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};

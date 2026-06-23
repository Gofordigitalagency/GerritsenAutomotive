<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('landing_pages', function (Blueprint $table) {
            $table->id();

            $table->string('slug')->unique();          // URL: /{slug}
            $table->string('title');                    // interne naam in admin

            // SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();

            // Hero (zelfde opmaak als homepage)
            $table->string('hero_eyebrow')->nullable();
            $table->string('hero_title');               // H1
            $table->text('hero_subtitle')->nullable();
            $table->string('hero_image')->nullable();   // opslagpad; leeg = homepage-default
            $table->string('cta_label')->nullable();
            $table->string('cta_url')->nullable();

            // Optioneel het actuele occasion-aanbod tonen
            $table->boolean('show_occasions')->default(false);

            // Inhoud
            $table->longText('body')->nullable();       // Markdown
            $table->json('faq')->nullable();            // [{question, answer}, ...]

            $table->boolean('is_published')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('landing_pages');
    }
};

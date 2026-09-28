<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('content_blocks', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->json('data');
            $table->timestamps();
        });

        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->string('path');
            $table->string('original_name');
            $table->string('mime', 120);
            $table->unsignedBigInteger('size');
            $table->unsignedInteger('width')->nullable();
            $table->unsignedInteger('height')->nullable();
            $table->string('alt')->nullable();
            $table->timestamps();
        });

        Schema::create('lead_forms', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('type', 40);
            $table->string('title');
            $table->text('intro')->nullable();
            $table->string('button_label')->default('Send the enquiry');
            $table->string('success_title')->default('Thank you.');
            $table->text('success_message')->nullable();
            $table->json('fields');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('funnels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('channel', 40);
            $table->foreignId('lead_form_id')->constrained()->cascadeOnDelete();
            $table->string('headline');
            $table->string('headline_accent')->nullable();
            $table->text('subline')->nullable();
            $table->foreignId('media_id')->nullable()->constrained('media')->nullOnDelete();
            $table->string('utm_source')->nullable();
            $table->string('utm_medium')->nullable();
            $table->string('utm_campaign')->nullable();
            $table->unsignedBigInteger('visits')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('form_type', 40);
            $table->foreignId('lead_form_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('funnel_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('interest')->nullable();
            $table->date('preferred_date')->nullable();
            $table->string('preferred_time')->nullable();
            $table->string('visit_type')->nullable();
            $table->string('residence_type')->nullable();
            $table->unsignedTinyInteger('guests')->nullable();
            $table->string('contact_channel')->nullable();
            $table->text('message')->nullable();
            $table->string('source')->nullable();
            $table->string('utm_source')->nullable();
            $table->string('utm_medium')->nullable();
            $table->string('utm_campaign')->nullable();
            $table->string('referrer', 500)->nullable();
            $table->string('status', 30)->default('new');
            $table->text('notes')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index('form_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
        Schema::dropIfExists('funnels');
        Schema::dropIfExists('lead_forms');
        Schema::dropIfExists('media');
        Schema::dropIfExists('content_blocks');
    }
};

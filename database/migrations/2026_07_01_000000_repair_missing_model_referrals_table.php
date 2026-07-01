<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('model_referrals')) {
            return;
        }

        Schema::create('model_referrals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('referrer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('model_application_id')->nullable()->unique()->constrained('model_applications')->nullOnDelete();
            $table->string('candidate_name');
            $table->string('candidate_email')->nullable();
            $table->string('candidate_phone')->nullable();
            $table->string('candidate_social_handle')->nullable();
            $table->string('experience_level', 64)->nullable();
            $table->text('note')->nullable();
            $table->json('photo_paths')->nullable();
            $table->boolean('consent_confirmed')->default(false);
            $table->string('source', 32)->default('member_form')->index();
            $table->string('status', 32)->default('referred')->index();
            $table->string('reward_status', 32)->default('not_eligible')->index();
            $table->timestamp('joined_at')->nullable();
            $table->timestamp('reward_marked_paid_at')->nullable();
            $table->foreignId('reward_marked_paid_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['referrer_id', 'status']);
            $table->index(['candidate_email']);
            $table->index(['status', 'model_application_id'], 'model_referrals_status_application_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('model_referrals');
    }
};

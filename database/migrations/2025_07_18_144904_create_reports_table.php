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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // reporting user
            $table->morphs('reportable'); // reportable_id + reportable_type (post or comment)
            $table->string('reason')->default(\App\Enums\ReportReason::Other->value); // predefined reason
            $table->text('description')->nullable(); // user-submitted details
            $table->string('status')->default(\App\Enums\ReportStatus::Pending->value); // pending, reviewed, action_taken, dismissed
            $table->text('action_text')->nullable(); // admin response/action
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};

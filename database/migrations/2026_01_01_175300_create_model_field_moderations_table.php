<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('model_field_moderations', function (Blueprint $table) {
            $table->id();

            $table->string('moderatable_type');

            $table->unsignedBigInteger('moderatable_id');

            $table->string('field');

            $table->string('status', 50);

            $table->text('rejection_reason')->nullable();

            $table->timestamp('moderated_at')->nullable();

            $table->unsignedBigInteger('moderated_by')->nullable();

            $table->timestamps();

            $table->index(
                ['moderatable_type', 'moderatable_id'],
                'mfm_moderatable_index'
            );

            $table->index(
                ['moderatable_type', 'moderatable_id', 'field'],
                'mfm_moderatable_field_index'
            );

            $table->index(
                ['status'],
                'mfm_status_index'
            );

            $table->unique(
                ['moderatable_type', 'moderatable_id', 'field'],
                'mfm_unique_field_per_model'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('model_field_moderations');
    }
};

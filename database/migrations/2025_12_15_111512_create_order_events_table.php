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
        Schema::create('order_events', function (Blueprint $table) {
            $table->id();
            // TODO: would this be a good idea to have both id and uuid, and we expose only one 
            $table->char('uuid', 36)->unique();
            // constrained only flow in one direction (parent to child, NOT reverse)
            // if Order (parent here) is deleted, then all related child's records are deleted
            // foreginId() here creates relationship at the database level only
            // in the application level, still have to define in the model
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('sequence');
            $table->string('event_type', 50);
            $table->json('payload');
            $table->timestamp('published_at')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['order_id', 'sequence']);
            $table->index(['order_id', 'sequence']);
            // this is to find unpublished events faster eg WHERE published_at IS NULL ORDER BY created_at
            $table->index(['published_at', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_events');
    }
};

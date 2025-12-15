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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            // references is to generate human readable references
            $table->string('references', 20)->unique();
            // status is varchar instead of enum, because the validation is done on the application level. 
            // application level is the one who enforces valid values, not database
            $table->string('status', 20)->default('placed');
            $table->string('customer_name');
            $table->timestamps();

            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

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
        Schema::create('network_events', function (Blueprint $table) {
            $table->id();

            // Network information
            $table->ipAddress('source_ip');
            $table->ipAddress('destination_ip')->nullable();
            $table->unsignedInteger('source_port')->nullable();
            $table->unsignedInteger('destination_port')->nullable();
            $table->string('protocol', 20)->nullable();

            // Security event information
            $table->string('event_type', 100);
            $table->string('severity', 20)->default('medium');
            $table->text('description')->nullable();

            // Event management
            $table->string('status', 20)->default('unresolved');
            $table->timestamp('detected_at')->useCurrent();

            $table->timestamps();

            // Useful indexes for filtering and reporting
            $table->index('source_ip');
            $table->index('event_type');
            $table->index('severity');
            $table->index('status');
            $table->index('detected_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('network_events');
    }
};
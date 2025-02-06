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
            $table->string('title');
            $table->string('version');
            $table->date('date');
            $table->string('classification');
            $table->string('auditor_name');
            $table->string('auditor_certification');
            $table->integer('total_risks');
            $table->integer('critical_risks');
            $table->decimal('compliance_status', 5, 2);
            $table->text('toc_sections')->nullable();
            $table->text('purpose');
            $table->text('background');
            $table->text('audit_scope');
            $table->text('auditor_independence');
            $table->text('assessment_timings');
            $table->text('audit_exclusions');
            $table->text('sources_of_information');
            $table->text('limitations');
            $table->text('executive_summary');
            $table->text('key_findings');
            $table->text('key_recommendations');
            $table->boolean('risk_heat_map')->default(true);
            $table->boolean('audit_checklist')->default(true);
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

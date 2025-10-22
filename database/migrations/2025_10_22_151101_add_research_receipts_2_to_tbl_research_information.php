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
        Schema::table('tbl_research_information', function (Blueprint $table) {
            $table->string('research_Contact')->after('research_CoInvestigator');
        });
    }

    public function down(): void
    {
        Schema::table('tbl_research_information', function (Blueprint $table) {
            $table->dropColumn('research_Contact');
        });
    }
};

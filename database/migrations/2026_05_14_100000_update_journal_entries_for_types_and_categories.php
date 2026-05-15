<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add columns if they don't exist
        Schema::table('journal_entries', function (Blueprint $table) {
            if (! Schema::hasColumn('journal_entries', 'entry_type')) {
                $table->string('entry_type', 20)->default('patient')->after('entry_date');
            }
        });

        Schema::table('journal_entries', function (Blueprint $table) {
            if (! Schema::hasColumn('journal_entries', 'category')) {
                $table->string('category', 40)->nullable()->after('entry_type');
            }
        });

        // Update constraints and indexes
        try {
            Schema::table('journal_entries', function (Blueprint $table) {
                $table->dropUnique('journal_entries_user_id_entry_date_unique');
            });
        } catch (\Exception $e) {
            // Constraint may not exist, continue
        }

        try {
            Schema::table('journal_entries', function (Blueprint $table) {
                $table->unique(['user_id', 'entry_date', 'entry_type']);
            });
        } catch (\Exception $e) {
            // Constraint may already exist, continue
        }

        try {
            Schema::table('journal_entries', function (Blueprint $table) {
                $table->index('entry_type');
                $table->index('category');
            });
        } catch (\Exception $e) {
            // Indexes may already exist, continue
        }
    }

    public function down(): void
    {
        try {
            Schema::table('journal_entries', function (Blueprint $table) {
                $table->dropUnique('journal_entries_user_id_entry_date_entry_type_unique');
            });
        } catch (\Exception $e) {
            // Already dropped
        }

        try {
            Schema::table('journal_entries', function (Blueprint $table) {
                $table->dropIndex('journal_entries_entry_type_index');
                $table->dropIndex('journal_entries_category_index');
            });
        } catch (\Exception $e) {
            // Already dropped
        }

        try {
            Schema::table('journal_entries', function (Blueprint $table) {
                $table->unique(['user_id', 'entry_date']);
            });
        } catch (\Exception $e) {
            // Already exists
        }

        try {
            Schema::table('journal_entries', function (Blueprint $table) {
                $table->dropColumn(['entry_type', 'category']);
            });
        } catch (\Exception $e) {
            // Already dropped
        }
    }
};

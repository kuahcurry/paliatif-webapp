<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Alter users table columns first to accommodate encrypted strings
        Schema::table('users', function (Blueprint $table) {
            $table->text('patient_birth_date')->nullable()->change();
        });

        // 2. Alter ecog_assessments table columns to accommodate encrypted strings
        Schema::table('ecog_assessments', function (Blueprint $table) {
            $table->text('respondent_initials')->nullable()->change();
            $table->text('gender')->nullable()->change();
            $table->text('marital_status')->nullable()->change();
            $table->text('cancer_stage')->nullable()->change();
            $table->text('score_label')->change();
        });

        // 3. Encrypt users
        DB::table('users')->orderBy('id')->chunk(100, function ($users) {
            foreach ($users as $user) {
                $updates = [];
                
                foreach (['name', 'religion', 'patient_gender', 'patient_birth_date', 'marital_status'] as $field) {
                    if (isset($user->$field) && !empty($user->$field)) {
                        if (!$this->isEncrypted($user->$field)) {
                            $updates[$field] = Crypt::encryptString($user->$field);
                        }
                    }
                }
                
                if (!empty($updates)) {
                    DB::table('users')->where('id', $user->id)->update($updates);
                }
            }
        });

        // 4. Encrypt journal_entries
        DB::table('journal_entries')->orderBy('id')->chunk(100, function ($entries) {
            foreach ($entries as $entry) {
                $updates = [];
                
                foreach (['content', 'provider_response'] as $field) {
                    if (isset($entry->$field) && !empty($entry->$field)) {
                        if (!$this->isEncrypted($entry->$field)) {
                            $updates[$field] = Crypt::encryptString($entry->$field);
                        }
                    }
                }
                
                if (!empty($updates)) {
                    DB::table('journal_entries')->where('id', $entry->id)->update($updates);
                }
            }
        });

        // 5. Encrypt emotional_evaluations
        DB::table('emotional_evaluations')->orderBy('id')->chunk(100, function ($evaluations) {
            foreach ($evaluations as $evaluation) {
                if (isset($evaluation->note) && !empty($evaluation->note)) {
                    if (!$this->isEncrypted($evaluation->note)) {
                        DB::table('emotional_evaluations')
                            ->where('id', $evaluation->id)
                            ->update(['note' => Crypt::encryptString($evaluation->note)]);
                    }
                }
            }
        });

        // 6. Encrypt ecog_assessments
        DB::table('ecog_assessments')->orderBy('id')->chunk(100, function ($assessments) {
            foreach ($assessments as $assessment) {
                $updates = [];
                
                foreach (['respondent_initials', 'gender', 'marital_status', 'cancer_stage', 'score_label'] as $field) {
                    if (isset($assessment->$field) && !empty($assessment->$field)) {
                        if (!$this->isEncrypted($assessment->$field)) {
                            $updates[$field] = Crypt::encryptString($assessment->$field);
                        }
                    }
                }
                
                if (!empty($updates)) {
                    DB::table('ecog_assessments')->where('id', $assessment->id)->update($updates);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Decrypt users
        DB::table('users')->orderBy('id')->chunk(100, function ($users) {
            foreach ($users as $user) {
                $updates = [];
                foreach (['name', 'religion', 'patient_gender', 'patient_birth_date', 'marital_status'] as $field) {
                    if (isset($user->$field) && !empty($user->$field)) {
                        if ($this->isEncrypted($user->$field)) {
                            try {
                                $updates[$field] = Crypt::decryptString($user->$field);
                            } catch (DecryptException $e) {
                                // Keep it as is if decrypt fails
                            }
                        }
                    }
                }
                if (!empty($updates)) {
                    DB::table('users')->where('id', $user->id)->update($updates);
                }
            }
        });

        // 2. Decrypt journal_entries
        DB::table('journal_entries')->orderBy('id')->chunk(100, function ($entries) {
            foreach ($entries as $entry) {
                $updates = [];
                foreach (['content', 'provider_response'] as $field) {
                    if (isset($entry->$field) && !empty($entry->$field)) {
                        if ($this->isEncrypted($entry->$field)) {
                            try {
                                $updates[$field] = Crypt::decryptString($entry->$field);
                            } catch (DecryptException $e) {
                                // Keep it as is if decrypt fails
                            }
                        }
                    }
                }
                if (!empty($updates)) {
                    DB::table('journal_entries')->where('id', $entry->id)->update($updates);
                }
            }
        });

        // 3. Decrypt emotional_evaluations
        DB::table('emotional_evaluations')->orderBy('id')->chunk(100, function ($evaluations) {
            foreach ($evaluations as $evaluation) {
                if (isset($evaluation->note) && !empty($evaluation->note)) {
                    if ($this->isEncrypted($evaluation->note)) {
                        try {
                            DB::table('emotional_evaluations')
                                ->where('id', $evaluation->id)
                                ->update(['note' => Crypt::decryptString($evaluation->note)]);
                        } catch (DecryptException $e) {
                            // Keep it as is
                        }
                    }
                }
            }
        });

        // 4. Decrypt ecog_assessments
        DB::table('ecog_assessments')->orderBy('id')->chunk(100, function ($assessments) {
            foreach ($assessments as $assessment) {
                $updates = [];
                foreach (['respondent_initials', 'gender', 'marital_status', 'cancer_stage', 'score_label'] as $field) {
                    if (isset($assessment->$field) && !empty($assessment->$field)) {
                        if ($this->isEncrypted($assessment->$field)) {
                            try {
                                $updates[$field] = Crypt::decryptString($assessment->$field);
                            } catch (DecryptException $e) {
                                // Keep it as is
                            }
                        }
                    }
                }
                if (!empty($updates)) {
                    DB::table('ecog_assessments')->where('id', $assessment->id)->update($updates);
                }
            }
        });

        // 5. Restore users table columns
        Schema::table('users', function (Blueprint $table) {
            $table->date('patient_birth_date')->nullable()->change();
        });

        // 6. Restore ecog_assessments table columns
        Schema::table('ecog_assessments', function (Blueprint $table) {
            $table->string('respondent_initials', 20)->nullable()->change();
            $table->string('gender', 20)->nullable()->change();
            $table->string('marital_status', 30)->nullable()->change();
            $table->string('cancer_stage', 30)->nullable()->change();
            $table->string('score_label', 20)->change();
        });
    }

    /**
     * Check if a value is encrypted.
     */
    private function isEncrypted(string $value): bool
    {
        try {
            Crypt::decryptString($value);
            return true;
        } catch (DecryptException $e) {
            return false;
        }
    }
};

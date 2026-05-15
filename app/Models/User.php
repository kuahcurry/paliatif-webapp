<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'religion', 'email', 'password', 'is_admin', 'patient_gender', 'patient_age', 'patient_rm', 'patient_room'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'patient_age' => 'integer',
        ];
    }

    public function spiritualRadarLogs(): HasMany
    {
        return $this->hasMany(SpiritualRadarLog::class);
    }

    public function prayers(): HasMany
    {
        return $this->hasMany(Prayer::class);
    }

    public function journalEntries(): HasMany
    {
        return $this->hasMany(JournalEntry::class);
    }

    public function caregiverAssessments(): HasMany
    {
        return $this->hasMany(CaregiverAssessment::class);
    }

    public function ecogAssessments(): HasMany
    {
        return $this->hasMany(EcogAssessment::class);
    }

    public function esasAssessments(): HasMany
    {
        return $this->hasMany(EsasAssessment::class);
    }

    public function swbsAssessments(): HasMany
    {
        return $this->hasMany(SwbsAssessment::class);
    }

    public function emotionalEvaluations(): HasMany
    {
        return $this->hasMany(EmotionalEvaluation::class);
    }

    public function spiritualIntervention(): HasMany
    {
        return $this->hasMany(SpiritualIntervention::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(UserNotification::class);
    }

    public function unreadNotifications(): HasMany
    {
        return $this->hasMany(UserNotification::class)->where('is_read', false);
    }
}

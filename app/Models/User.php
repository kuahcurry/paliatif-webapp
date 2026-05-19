<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\VerifyEmailNotification;

#[Fillable(['name', 'religion', 'email', 'password', 'patient_gender', 'patient_age', 'patient_birth_date', 'marital_status'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmail
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
            'patient_birth_date' => 'date',
        ];
    }

    /**
     * Send the email verification notification using the custom Indonesian template.
     */
    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new VerifyEmailNotification);
    }

    public function getPatientAgeAttribute(): ?int
    {
        if ($this->patient_birth_date) {
            return $this->patient_birth_date->age;
        }
        return $this->attributes['patient_age'] ?? null;
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

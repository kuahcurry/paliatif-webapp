<?php

use App\Http\Controllers\Admin\JournalController as AdminJournalController;
use App\Http\Controllers\Admin\PrayerController as AdminPrayerController;
use App\Http\Controllers\CaregiverAssessmentController;
use App\Http\Controllers\EcogAssessmentController;
use App\Http\Controllers\EsasAssessmentController;
use App\Http\Controllers\SwbsAssessmentController;
use App\Http\Controllers\EducationModuleController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\PrayerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SpiritualDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/pohon-doa', [PrayerController::class, 'index'])->name('prayers.index');
Route::post('/pohon-doa', [PrayerController::class, 'store'])->name('prayers.store');
Route::post('/pohon-doa/{prayer}/support', [PrayerController::class, 'support'])
    ->middleware('throttle:10,1')
    ->name('prayers.support');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [SpiritualDashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/radar', [SpiritualDashboardController::class, 'store'])->name('dashboard.radar.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/pengkajian-awal', [CaregiverAssessmentController::class, 'index'])->name('menu.assessment');
    Route::post('/pengkajian-awal', [CaregiverAssessmentController::class, 'store'])->name('menu.assessment.store');
    Route::post('/pengkajian-awal/swbs', [SwbsAssessmentController::class, 'store'])->name('menu.assessment.swbs.store');
    Route::post('/pengkajian-awal/ecog', [EcogAssessmentController::class, 'store'])->name('menu.assessment.ecog.store');
    Route::post('/pengkajian-awal/esas', [EsasAssessmentController::class, 'store'])->name('menu.assessment.esas.store');

    Route::get('/kebutuhan-spiritual', function () {
        return view('menu.spiritual-needs');
    })->name('menu.spiritual-needs');

    Route::get('/evaluasi-perasaan', function () {
        return view('menu.emotional-evaluation');
    })->name('menu.emotional-evaluation');

    Route::get('/edukasi-caregiver', [EducationModuleController::class, 'index'])->name('education.index');

    Route::get('/jurnal', [JournalController::class, 'index'])->name('journals.index');
    Route::post('/jurnal', [JournalController::class, 'store'])->name('journals.store');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/prayers', [AdminPrayerController::class, 'index'])->name('prayers.index');
    Route::delete('/prayers/{prayer}', [AdminPrayerController::class, 'destroy'])->name('prayers.destroy');
    Route::get('/journals', [AdminJournalController::class, 'index'])->name('journals.index');
    Route::patch('/journals/{journalEntry}', [AdminJournalController::class, 'update'])->name('journals.update');
});

require __DIR__.'/auth.php';

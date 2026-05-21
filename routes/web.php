<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\EducationModuleController as AdminEducationModuleController;
use App\Http\Controllers\Admin\EmotionalEvaluationController as AdminEmotionalEvaluationController;
use App\Http\Controllers\Admin\JournalController as AdminJournalController;
use App\Http\Controllers\Admin\PrayerController as AdminPrayerController;
use App\Http\Controllers\CaregiverAssessmentController;
use App\Http\Controllers\EcogAssessmentController;
use App\Http\Controllers\EsasAssessmentController;
use App\Http\Controllers\SwbsAssessmentController;
use App\Http\Controllers\EducationModuleController;
use App\Http\Controllers\EmotionalEvaluationController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PrayerTreeController;
use App\Http\Controllers\PrayerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SpiritualDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/setup-storage', function () {
    $docRoot = $_SERVER['DOCUMENT_ROOT'];
    $sourceDir = base_path();
    
    $output = "Document Root: $docRoot<br>";
    $output .= "Source Dir: $sourceDir<br><br>";
    
    $storagePath = $docRoot . '/storage';
    $targetPath = $sourceDir . '/storage/app/public';
    
    if (file_exists($storagePath)) {
        if (is_link($storagePath)) {
            $output .= "storage in doc root is already a link.<br>";
            $output .= "Points to: " . readlink($storagePath) . "<br>";
        } else {
            $output .= "storage in doc root exists but is NOT a link. It's a regular directory.<br>";
            $backupPath = $docRoot . '/storage_backup_' . time();
            rename($storagePath, $backupPath);
            $output .= "Renamed existing storage folder to: " . basename($backupPath) . "<br>";
            
            try {
                symlink($targetPath, $storagePath);
                $output .= "Successfully created symlink in doc root!<br>";
            } catch (\Exception $e) {
                $output .= "Failed to create symlink: " . $e->getMessage() . "<br>";
            }
        }
    } else {
        $output .= "storage in doc root DOES NOT exist.<br>";
        try {
            symlink($targetPath, $storagePath);
            $output .= "Successfully created symlink in doc root!<br>";
        } catch (\Exception $e) {
            $output .= "Failed to create symlink: " . $e->getMessage() . "<br>";
        }
    }
    
    return $output;
});

Route::get('/pohon-doa', [PrayerController::class, 'index'])->name('prayers.index');
Route::post('/pohon-doa', [PrayerController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('prayers.store');
Route::post('/pohon-doa/{prayer}/support', [PrayerController::class, 'support'])
    ->middleware('throttle:10,1')
    ->name('prayers.support');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [SpiritualDashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/radar', [SpiritualDashboardController::class, 'store'])->name('dashboard.radar.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/pengkajian-awal', [CaregiverAssessmentController::class, 'index'])->name('menu.assessment');
    Route::post('/pengkajian-awal', [CaregiverAssessmentController::class, 'store'])->name('menu.assessment.store');
    Route::post('/pengkajian-awal/swbs', [SwbsAssessmentController::class, 'store'])->name('menu.assessment.swbs.store');
    Route::post('/pengkajian-awal/ecog', [EcogAssessmentController::class, 'store'])->name('menu.assessment.ecog.store');
    Route::post('/pengkajian-awal/esas', [EsasAssessmentController::class, 'store'])->name('menu.assessment.esas.store');

    Route::get('/pohon-spiritual', [PrayerTreeController::class, 'index'])->name('menu.prayer-tree');

    Route::get('/evaluasi-perasaan', [EmotionalEvaluationController::class, 'index'])->name('menu.emotional-evaluation');
    Route::post('/evaluasi-perasaan', [EmotionalEvaluationController::class, 'store'])->name('menu.emotional-evaluation.store');

    Route::get('/edukasi-caregiver', [EducationModuleController::class, 'index'])->name('education.index');
    Route::get('/edukasi-caregiver/{educationModule}', [EducationModuleController::class, 'show'])->name('education.show');

    Route::get('/jurnal', [JournalController::class, 'index'])->name('journals.index');
    Route::post('/jurnal', [JournalController::class, 'store'])->name('journals.store');

    Route::get('/notifikasi', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifikasi/{userNotification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifikasi/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllRead');

    Route::get('/faq', function () {
        return view('faq');
    })->name('faq');

    Route::get('/hubungi-kami', function () {
        return view('contact');
    })->name('contact');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/prayers', [AdminPrayerController::class, 'index'])->name('prayers.index');
    Route::delete('/prayers/{prayer}', [AdminPrayerController::class, 'destroy'])->name('prayers.destroy');
    Route::get('/journals', [AdminJournalController::class, 'index'])->name('journals.index');
    Route::patch('/journals/{journalEntry}', [AdminJournalController::class, 'update'])->name('journals.update');

    Route::get('/education', [AdminEducationModuleController::class, 'index'])->name('education.index');
    Route::get('/education/create', [AdminEducationModuleController::class, 'create'])->name('education.create');
    Route::post('/education', [AdminEducationModuleController::class, 'store'])->name('education.store');
    Route::get('/education/{educationModule}/edit', [AdminEducationModuleController::class, 'edit'])->name('education.edit');
    Route::put('/education/{educationModule}', [AdminEducationModuleController::class, 'update'])->name('education.update');
    Route::delete('/education/{educationModule}', [AdminEducationModuleController::class, 'destroy'])->name('education.destroy');

    Route::get('/evaluations', [AdminEmotionalEvaluationController::class, 'index'])->name('evaluations.index');

});

require __DIR__.'/auth.php';

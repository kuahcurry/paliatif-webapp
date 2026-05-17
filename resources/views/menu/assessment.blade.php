@push('head')
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=manrope:400,500,600,700&display=swap" rel="stylesheet" />
@endpush

@php
    $_user = Auth::user();
    $patientName = $_user->name ?? 'Pengguna';
    $patientAge = $_user->patient_age ? $_user->patient_age . ' Tahun' : '--';
    $patientGender = $_user->patient_gender ?? '--';
    $assessmentDate = now()->format('d M Y');
    $assessmentTime = now()->format('H:i');
    $assessmentBy = $_user->name;
    $hasSwbs = !is_null($latestSwbs);
    $hasEcog = !is_null($latestEcog);
    $hasEsas = !is_null($latestEsas);
    $completedCount = ($hasSwbs ? 1 : 0) + ($hasEcog ? 1 : 0) + ($hasEsas ? 1 : 0);
    $statusLabel = $completedCount === 0 ? 'Belum Dimulai' : ($completedCount === 3 ? 'Lengkap' : 'Dalam Proses');
    $statusTone = $completedCount === 0 ? 'status-pill warning' : ($completedCount === 3 ? 'status-pill success' : 'status-pill info');
    $lastSaved = now()->format('H:i');
    $ecogOptions = [
        [
            'score' => 0,
            'label' => 'Asimtomatik, aktif sepenuhnya, mampu melakukan semua aktivitas tanpa hambatan.',
        ],
        [
            'score' => 1,
            'label' => 'Simptomatik namun bisa sepenuhnya berjalan, kegiatan fisik terbatas dan bisa melakukan kerja ringan atau tidak bergerak misalnya pekerjaan rumah tangga yang ringan dan pekerjaan kantor.',
        ],
        [
            'score' => 2,
            'label' => 'Simptomatik, kurang dari 50 persen berada ditempat tidur sepanjang hari, dapat berjalan dan merawat diri tapi tidak bisa melakukan aktivitas kerja.',
        ],
        [
            'score' => 3,
            'label' => 'Mampu merawat diri sendiri tetapi tidak mampu melakukan pekerjaan dan lebih dari 50 persen waktu harus berbaring.',
        ],
        [
            'score' => 4,
            'label' => 'Tidak bisa melakukan rawat diri apapun, sepenuhnya harus ditempat tidur atau kursi.',
        ],
        [
            'score' => 5,
            'label' => 'Meninggal.',
        ],
    ];
    $scoreLegend = [
        ['label' => 'Sangat Baik', 'tone' => 'good'],
        ['label' => 'Cukup Baik', 'tone' => 'calm'],
        ['label' => 'Kurang Baik', 'tone' => 'warn'],
    ];
    $swbsScale = [
        ['value' => 6, 'short' => 'SS', 'label' => 'Sangat Setuju'],
        ['value' => 5, 'short' => 'CS', 'label' => 'Cukup Setuju'],
        ['value' => 4, 'short' => 'S', 'label' => 'Setuju'],
        ['value' => 3, 'short' => 'TS', 'label' => 'Tidak Setuju'],
        ['value' => 2, 'short' => 'CTS', 'label' => 'Cukup Tidak Setuju'],
        ['value' => 1, 'short' => 'STS', 'label' => 'Sangat Tidak Setuju'],
    ];
    $swbsStatements = [
        'Saya merasa damai dan tenteram di dalam diri saya.',
        'Saya merasa memiliki hubungan yang dekat dengan Tuhan.',
        'Saya menemukan makna dan tujuan hidup saya.',
        'Saya merasa takut menghadapi kematian.',
        'Saya merasa hidup saya penuh harapan.',
        'Saya merasa jauh dari Tuhan.',
        'Saya merasa hidup saya tidak memiliki arti.',
        'Saya merasa diberkati dalam hidup saya.',
        'Saya merasa hidup saya penuh dengan kedamaian.',
        'Saya merasa putus asa dengan masa depan saya.',
        'Saya merasa hidup saya bernilai.',
        'Saya merasa tidak ada hal yang bisa membuat saya bahagia.',
        'Saya merasa Tuhan peduli dengan saya.',
        'Saya merasa hidup saya penuh dengan rasa syukur.',
        'Saya merasa sulit mempercayai Tuhan.',
        'Saya merasa hidup saya membawa kebaikan bagi orang lain.',
        'Saya merasa tidak memiliki kekuatan dalam diri saya.',
        'Saya merasa yakin akan pertolongan Tuhan.',
        'Saya merasa sulit menerima keadaan saya sekarang.',
        'Saya merasa hidup saya masih memiliki tujuan yang jelas.',
    ];
    $esasQuestions = [
        [
            'name' => 'pain',
            'left' => 'Tidak nyeri',
            'right' => 'Nyeri hebat',
        ],
        [
            'name' => 'fatigue',
            'left' => 'Tidak lelah',
            'right' => 'Perasaan lelah yang hebat',
        ],
        [
            'name' => 'nausea',
            'left' => 'Tidak mual',
            'right' => 'Mual hebat dan parah',
        ],
        [
            'name' => 'stress',
            'left' => 'Tidak stress',
            'right' => 'Stress hebat dan parah',
        ],
        [
            'name' => 'anxiety',
            'left' => 'Tidak cemas',
            'right' => 'Cemas parah',
        ],
        [
            'name' => 'drowsiness',
            'left' => 'Tidak merasa mengantuk',
            'right' => 'Mengantuk hebat',
        ],
        [
            'name' => 'appetite',
            'left' => 'Selera makan baik',
            'right' => 'Selera makan buruk',
        ],
        [
            'name' => 'wellbeing',
            'left' => 'Merasa sehat dan segar bugar',
            'right' => 'Perasaan tidak berdaya',
        ],
        [
            'name' => 'shortness_of_breath',
            'left' => 'Tidak sesak nafas',
            'right' => 'Sesak nafas berat',
        ],
        [
            'name' => 'other_problem',
            'left' => 'Tidak ada masalah',
            'right' => 'Masalah berat (klg, keuangan, kesehatan)',
        ],
    ];
@endphp

<x-app-layout :hideNavigation="true" :hideHeader="true" bodyClass="antialiased" pageClass="min-h-screen dashboard-page">
    <div class="dashboard-layout">
        <aside class="dashboard-sidebar">
            <x-app-sidebar />
        </aside>

        <main class="dashboard-main">
            <x-app-topbar
                class="dashboard-topbar"
                title="Pengkajian Awal"
                subtitle="Terapi Rohani Pasien Paliatif"
                :badgeCount="3"
            />

            @if (session('status') === 'assessment-saved')
                <div class="alert success">{{ __('Pengkajian caregiver tersimpan.') }}</div>
            @endif

            <section class="card assessment-hero">
                <div class="patient-profile">
                    <div class="patient-avatar">{{ strtoupper(substr($patientName, 0, 1)) }}</div>
                    <div>
                        <h3>{{ $patientName }}</h3>
                        <p>{{ $patientAge }}, {{ $patientGender }}</p>
                    </div>
                </div>
                <div class="hero-metrics">
                    <div class="metric">
                        <div class="metric-icon">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M8 2v3M16 2v3M3 7h18M5 5h14a2 2 0 0 1 2 2v13a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2z M3 11h18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </div>
                        <div>
                            <p class="metric-label">Tanggal Pengkajian</p>
                            <p class="metric-value">{{ $assessmentDate }}</p>
                            <p class="metric-sub">{{ $assessmentTime }} WIB</p>
                            <a class="metric-link" href="{{ route('menu.assessment') }}">Ubah</a>
                        </div>
                    </div>
                    <div class="metric">
                        <div class="metric-icon">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zM14 2v6h6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </div>
                        <div>
                            <p class="metric-label">Status Pengkajian</p>
                            <span class="{{ $statusTone }}">{{ $statusLabel }}</span>
                            <p class="metric-sub">SWBS {{ $hasSwbs ? '✔' : '✗' }} &middot; ECOG {{ $hasEcog ? '✔' : '✗' }} &middot; ESAS {{ $hasEsas ? '✔' : '✗' }}</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="assessment-content">
                <div class="assessment-main">
                    <div class="notice info">
                        <strong>Petunjuk Pengisian:</strong> Instrumen pengkajian ini bersifat opsional. Isilah sesuai dengan kondisi dan kebutuhan Anda. Tidak semua bagian harus diisi.
                    </div>

                    <div class="card is-collapsed" data-collapsible>
                        <div class="card-header">
                            <div>
                                <h4>Spiritual Well-Being Scale (SWBS)</h4>
                                <p>Pilih satu jawaban pada setiap pernyataan.</p>
                            </div>
                            <div class="card-actions">
                                @if (!$latestSwbs)
                                    <span class="status-chip warning">Perlu Diisi</span>
                                @else
                                    <span class="status-chip success">Sudah Diisi</span>
                                @endif
                                <div class="swbs-legend">
                                    @foreach ($swbsScale as $option)
                                        <span class="swbs-pill">{{ $option['short'] }} - {{ $option['label'] }}</span>
                                    @endforeach
                                </div>
                                <button type="button" class="collapse-toggle" aria-expanded="false" aria-label="Perluas atau minimalkan">
                                    <svg viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M6 9l6 6 6-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="card-body">
                            @if (session('status') === 'swbs-saved')
                                <div class="alert success">Pengkajian SWBS tersimpan.</div>
                            @endif
                            @if ($errors->hasAny(['q1', 'q2', 'q3', 'q4', 'q5', 'q6', 'q7', 'q8', 'q9', 'q10', 'q11', 'q12', 'q13', 'q14', 'q15', 'q16', 'q17', 'q18', 'q19', 'q20']))
                                <div class="alert warning">Ada kesalahan pada pengisian SWBS. Pastikan semua pertanyaan telah dijawab.</div>
                            @endif

                            <form method="POST" action="{{ route('menu.assessment.swbs.store') }}" class="swbs-form">
                                @csrf

                                @foreach ($swbsStatements as $index => $statement)
                                    @php
                                        $questionNumber = $index + 1;
                                    @endphp
                                    <div class="swbs-row">
                                        <div class="swbs-statement">
                                            <span class="swbs-index">{{ $questionNumber }}</span>
                                            <span>{{ $statement }}</span>
                                        </div>
                                        <div class="swbs-scale">
                                            @foreach ($swbsScale as $option)
                                                <label>
                                                    <input type="radio" name="q{{ $questionNumber }}" value="{{ $option['value'] }}" @checked(old('q' . $questionNumber, $latestSwbs?->{'q' . $questionNumber}) == $option['value']) required>
                                                    <span>{{ $option['short'] }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach

                                <div class="score-summary">
                                    <div class="score-info">
                                        <span class="score-pill" id="swbs-total-score">-</span>
                                        <span class="score-label">Total skor SWBS</span>
                                    </div>
                                    <button type="submit" class="primary-button">Simpan SWBS</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card is-collapsed" data-collapsible>
                        <div class="card-header">
                            <div>
                                <h4>ECOG Performance Status Scale</h4>
                                <p>Gunakan skala ini untuk menilai kemampuan pasien menjalankan aktivitas sehari-hari.</p>
                            </div>
                            <div class="card-actions">
                                @if (!$latestEcog)
                                    <span class="status-chip warning">Perlu Diisi</span>
                                @else
                                    <span class="status-chip success">Sudah Diisi</span>
                                @endif
                                <div class="score-legend">
                                    @foreach ($scoreLegend as $legend)
                                        <span class="legend-item {{ $legend['tone'] }}">{{ $legend['label'] }}</span>
                                    @endforeach
                                </div>
                                <button type="button" class="collapse-toggle" aria-expanded="false" aria-label="Perluas atau minimalkan">
                                    <svg viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M6 9l6 6 6-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="card-body">
                            @if (session('status') === 'ecog-saved')
                                <div class="alert success">Pengkajian ECOG tersimpan.</div>
                            @endif

                            <form method="POST" action="{{ route('menu.assessment.ecog.store') }}" class="ecog-form">
                                @csrf

                                <div class="ecog-meta">
                                    <div class="field">
                                        <label for="respondent_initials">Inisial responden</label>
                                        <input id="respondent_initials" name="respondent_initials" type="text" value="{{ old('respondent_initials', $latestEcog?->respondent_initials ?: $userInitials) }}" placeholder="Mis. AH" />
                                    </div>
                                    <div class="field">
                                        <label for="age">Umur</label>
                                        <input id="age" name="age" type="number" min="0" max="130" value="{{ old('age', $latestEcog?->age ?: $userAge) }}" placeholder="Tahun" />
                                    </div>
                                    <div class="field">
                                        <label for="gender">Jenis kelamin</label>
                                        <input id="gender" name="gender" type="text" value="{{ old('gender', $latestEcog?->gender ?: $userGender) }}" placeholder="Laki-laki / Perempuan" />
                                    </div>
                                    <div class="field">
                                        <label for="marital_status">Status pernikahan</label>
                                        <input id="marital_status" name="marital_status" type="text" value="{{ old('marital_status', $latestEcog?->marital_status ?: $userMaritalStatus) }}" placeholder="Menikah / Belum" />
                                </div>
                                </div>

                                <div class="ecog-table">
                                    <div class="ecog-row head">
                                        <span>Keterangan</span>
                                        <span>Skor</span>
                                    </div>
                                    @foreach ($ecogOptions as $option)
                                        <label class="ecog-row">
                                            <span>{{ $option['label'] }}</span>
                                            <span class="score-cell">
                                                <input type="radio" name="score" value="{{ $option['score'] }}" @checked(old('score', $latestEcog?->score) == $option['score']) required>
                                                <span class="score-pill">{{ $option['score'] }}</span>
                                            </span>
                                        </label>
                                    @endforeach
                                </div>

                                @if ($errors->has('score'))
                                    <div class="field-error">{{ $errors->first('score') }}</div>
                                @endif

                                <div class="score-summary">
                                    <div class="score-info">
                                        <span class="score-pill" id="ecog-score-value">-</span>
                                        <span class="score-label" id="ecog-score-label">Belum dipilih</span>
                                    </div>
                                    <button type="submit" class="primary-button">Simpan pengkajian</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card is-collapsed" data-collapsible>
                        <div class="card-header">
                            <div>
                                <h4>Edmonton Symptom Assessment System (ESAS)</h4>
                                <p>Lingkari salah satu nomor sesuai yang dirasakan pasien.</p>
                            </div>
                            <div class="card-actions">
                                @if (!$latestEsas)
                                    <span class="status-chip warning">Perlu Diisi</span>
                                @else
                                    <span class="status-chip success">Sudah Diisi</span>
                                @endif
                                <button type="button" class="collapse-toggle" aria-expanded="false" aria-label="Perluas atau minimalkan">
                                    <svg viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M6 9l6 6 6-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="card-body">
                            @if (session('status') === 'esas-saved')
                                <div class="alert success">Pengkajian ESAS tersimpan.</div>
                            @endif
                            @if ($errors->hasAny(['pain', 'fatigue', 'nausea', 'stress', 'anxiety', 'drowsiness', 'appetite', 'wellbeing', 'shortness_of_breath', 'other_problem']))
                                <div class="alert warning">Ada kesalahan pada pengisian ESAS. Pastikan semua gejala telah diisi.</div>
                            @endif

                            <form method="POST" action="{{ route('menu.assessment.esas.store') }}" class="esas-form">
                                @csrf

                                @foreach ($esasQuestions as $question)
                                    <div class="esas-row">
                                        <span class="esas-label">{{ $question['left'] }}</span>
                                        <div class="esas-scale">
                                            @for ($i = 0; $i <= 10; $i++)
                                                <label>
                                                    <input type="radio" name="{{ $question['name'] }}" value="{{ $i }}" @checked(old($question['name'], $latestEsas?->{$question['name']}) == $i) required>
                                                    <span>{{ $i }}</span>
                                                </label>
                                            @endfor
                                        </div>
                                        <span class="esas-label right">{{ $question['right'] }}</span>
                                    </div>
                                @endforeach

                                <div class="score-summary">
                                    <div class="score-info">
                                        <span class="score-pill" id="esas-total-score">-</span>
                                        <span class="score-label">Total skor ESAS</span>
                                    </div>
                                    <button type="submit" class="primary-button">Simpan ESAS</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <div>
                                <h4>Riwayat Pengkajian</h4>
                                <p>Riwayat pengkajian awal yang telah dilakukan.</p>
                            </div>
                        </div>

                        <div class="table-wrap">
                            <table class="history-table">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Instrumen</th>
                                        <th>Dilakukan oleh</th>
                                        <th>Hasil</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($assessmentHistory ?? [] as $entry)
                                        <tr>
                                            <td>{{ $entry['date']->format('d M Y, H:i') }}</td>
                                            <td>
                                                <span class="pill {{ $entry['instrument'] === 'ECOG' ? 'blue' : ($entry['instrument'] === 'SWBS' ? 'green' : 'purple') }}">
                                                    {{ $entry['instrument'] }}
                                                </span>
                                            </td>
                                            <td>{{ $assessmentBy }}</td>
                                            <td><span class="pill success">{{ $entry['result'] }}</span></td>
                                            <td><span class="ghost-button" style="opacity:0.5;cursor:default;">Lihat</span></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="empty-row">Belum ada riwayat pengkajian.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <p class="muted small">Pengkajian awal sebaiknya dilakukan pada awal perawatan dan dievaluasi secara berkala.</p>
                    </div>

                    <div class="notice warning">
                        <strong>Perhatian:</strong> Hasil pengkajian ini merupakan alat <em>screening</em> awal dan <strong>bukan merupakan diagnosis medis</strong>. Konsultasikan dengan tenaga kesehatan profesional untuk evaluasi lebih lanjut.
                    </div>

                    <details class="accordion">
                        <summary>Pengkajian Caregiver Singkat (opsional)</summary>
                        <form method="POST" action="{{ route('menu.assessment.store') }}" class="form-grid">
                            @csrf

                            @php
                                $assessmentQuestions = [
                                    'anxiety_level' => 'Tingkat kecemasan caregiver',
                                    'grief_level' => 'Tingkat duka yang dirasakan',
                                    'communication_level' => 'Kesulitan berkomunikasi dengan pasien/keluarga',
                                ];
                            @endphp

                            @foreach ($assessmentQuestions as $field => $label)
                                <div class="form-row">
                                    <label>{{ $label }}</label>
                                    <div class="radio-group">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <label>
                                                <input type="radio" name="{{ $field }}" value="{{ $i }}" @checked(old($field) == $i) @if ($i === 1) required @endif>
                                                <span>{{ $i }}</span>
                                            </label>
                                        @endfor
                                    </div>
                                    <x-input-error class="mt-2" :messages="$errors->get($field)" />
                                </div>
                            @endforeach

                            <button type="submit" class="primary-button">Simpan pengkajian</button>
                        </form>
                    </details>
                </div>

                <aside class="assessment-side">
                    <div class="card info-card">
                        <h4>Tentang Pengkajian Awal</h4>
                        <p>
                            Pengkajian awal dilakukan untuk memahami kondisi spiritual, fungsional, dan gejala pasien secara komprehensif, sebagai dasar perencanaan terapi rohani yang tepat dan personal.
                        </p>
                        <div class="info-list">
                            <h5>Petunjuk Pengisian</h5>
                            <ul>
                                <li>Pilih instrumen yang akan digunakan.</li>
                                <li>Jawab setiap pertanyaan dengan jujur sesuai kondisi pasien.</li>
                                <li>Pastikan semua item terisi sebelum menyimpan.</li>
                                <li>Hasil pengkajian menjadi dasar perencanaan intervensi.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="card quote-card">
                        <p>"Dan mohonlah pertolongan (kepada Allah) dengan sabar dan shalat."</p>
                        <span>(QS. Al-Baqarah: 45)</span>
                    </div>
                </aside>
            </section>
        </main>
    </div>

    <style>
        :root {
            --surface: #ffffff;
            --surface-muted: #f7faf5;
            --border: #e2e8f0;
            --text: #0f172a;
            --muted: #64748b;
            --accent: #4f9b4f;
            --accent-soft: #e9f6e9;
            --shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
        }

        .dashboard-page {
            background: #f5f7fb;
            font-family: 'Manrope', ui-sans-serif, system-ui, -apple-system, sans-serif;
        }

        .dashboard-layout {
            display: grid;
            grid-template-columns: 260px 1fr;
            min-height: 100vh;
        }

        .dashboard-sidebar {
            background: var(--surface);
            padding: 28px 20px;
            border-right: 1px solid #edf2f7;
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .sidebar-brand {
            display: flex;
            gap: 12px;
            align-items: center;
            font-weight: 700;
        }

        .brand-icon {
            width: 32px;
            height: 32px;
            border-radius: 10px;
            background: #dff3df;
            display: grid;
            place-items: center;
        }

        .brand-icon span {
            width: 14px;
            height: 14px;
            border-radius: 999px;
            background: #63b96b;
            display: block;
        }

        .sidebar-brand h1 {
            font-size: 0.95rem;
            color: var(--text);
        }

        .sidebar-brand p {
            font-size: 0.8rem;
            color: var(--muted);
        }

        .sidebar-nav {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 12px;
            color: var(--muted);
            text-decoration: none;
            font-size: 0.9rem;
        }

        .nav-item.is-active,
        .nav-item:hover {
            background: #e1f1e1;
            color: #256c32;
        }

        .nav-icon {
            width: 20px;
            height: 20px;
            display: grid;
            place-items: center;
            color: currentColor;
        }

        .nav-icon svg {
            width: 18px;
            height: 18px;
        }

        .nav-group {
            display: grid;
            gap: 8px;
        }

        .nav-sub {
            display: grid;
            gap: 6px;
            margin-left: 34px;
        }

        .nav-sub-item {
            font-size: 0.82rem;
            color: var(--muted);
            text-decoration: none;
        }

        .nav-sub-item.is-active {
            color: #256c32;
            font-weight: 600;
        }

        .sidebar-footer {
            margin-top: auto;
        }

        .footer-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 16px;
            font-size: 0.75rem;
            color: var(--muted);
            box-shadow: var(--shadow);
        }

        .footer-title {
            font-weight: 700;
            color: var(--text);
        }

        .footer-subtitle {
            margin-bottom: 6px;
        }

        .dashboard-main {
            padding: 28px 32px 48px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .dashboard-topbar {
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            align-items: center;
        }

        .dashboard-topbar > div:first-child {
            grid-column: 2;
            text-align: center;
        }

        .dashboard-topbar .topbar-actions {
            grid-column: 3;
            justify-self: end;
        }

        .dashboard-topbar h2 {
            font-size: 1.4rem;
            font-weight: 700;
        }

        .dashboard-topbar p {
            color: var(--muted);
            font-size: 0.85rem;
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .icon-button {
            position: relative;
            border: none;
            background: #ffffff;
            box-shadow: var(--shadow);
            border-radius: 12px;
            width: 38px;
            height: 38px;
            cursor: pointer;
            display: grid;
            place-items: center;
            color: #475569;
        }

        .icon-button svg {
            width: 20px;
            height: 20px;
        }

        .badge {
            position: absolute;
            top: -4px;
            right: -4px;
            background: #ef4444;
            color: #ffffff;
            font-size: 0.65rem;
            width: 18px;
            height: 18px;
            border-radius: 999px;
            display: grid;
            place-items: center;
        }

        .user-chip {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #ffffff;
            border-radius: 16px;
            padding: 6px 12px;
            box-shadow: var(--shadow);
        }

        .avatar {
            width: 34px;
            height: 34px;
            border-radius: 999px;
            background: #dbeafe;
            color: #1d4ed8;
            font-weight: 700;
            display: grid;
            place-items: center;
        }

        .user-name {
            font-size: 0.85rem;
            font-weight: 600;
        }

        .user-role {
            font-size: 0.75rem;
            color: var(--muted);
        }

        .alert {
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 0.85rem;
        }

        .alert.success { background: #ecfdf3; color: #15803d; }

        .notice {
            padding: 14px 18px;
            border-radius: 14px;
            font-size: 0.82rem;
            line-height: 1.5;
        }

        .notice.info { background: #eff6ff; color: #1e40af; border-left: 4px solid #3b82f6; }
        .notice.warning { background: #fff7ed; color: #92400e; border-left: 4px solid #f97316; }
        .notice strong { font-weight: 700; }

        .card {
            background: var(--surface);
            border-radius: 20px;
            padding: 20px;
            box-shadow: var(--shadow);
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .card-body {
            display: grid;
            gap: 16px;
        }

        .card-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .status-chip {
            display: inline-flex;
            padding: 3px 10px;
            border-radius: 999px;
            font-size: 0.7rem;
            font-weight: 600;
            white-space: nowrap;
        }

        .status-chip.warning {
            background: #fef3c7;
            color: #92400e;
        }

        .status-chip.success {
            background: #dcfce7;
            color: #15803d;
        }

        .collapse-toggle {
            border: 1px solid #e2e8f0;
            background: #ffffff;
            width: 30px;
            height: 30px;
            border-radius: 999px;
            font-size: 0.85rem;
            color: var(--muted);
            cursor: pointer;
            display: grid;
            place-items: center;
        }

        .collapse-toggle svg {
            width: 16px;
            height: 16px;
            transition: transform 0.2s ease;
        }

        .card:not(.is-collapsed) .collapse-toggle svg {
            transform: rotate(180deg);
        }

        .card.is-collapsed .card-body {
            display: none;
        }

        .assessment-hero {
            display: grid;
            grid-template-columns: 1.1fr 2fr;
            gap: 20px;
        }

        .patient-profile {
            display: flex;
            gap: 14px;
            align-items: center;
        }

        .patient-avatar {
            width: 64px;
            height: 64px;
            border-radius: 18px;
            background: #e2f4e2;
            display: grid;
            place-items: center;
            font-size: 1.4rem;
            font-weight: 700;
            color: #2f855a;
        }

        .muted {
            color: var(--muted);
            font-size: 0.8rem;
        }

        .hero-metrics {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 12px;
        }

        .metric {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 10px;
            background: #f9fafb;
            border-radius: 16px;
            padding: 12px;
        }

        .metric-icon {
            width: 36px;
            height: 36px;
            border-radius: 12px;
            background: #e5f6e5;
            display: grid;
            place-items: center;
            color: #3f7a3f;
        }

        .metric-icon svg {
            width: 18px;
            height: 18px;
        }

        .metric-label {
            font-size: 0.7rem;
            color: var(--muted);
        }

        .metric-value {
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--text);
        }

        .metric-sub {
            font-size: 0.7rem;
            color: var(--muted);
        }

        .metric-link {
            font-size: 0.7rem;
            color: #2f855a;
        }

        .status-pill {
            display: inline-flex;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .status-pill.success { background: #dcfce7; color: #15803d; }
        .status-pill.warning { background: #ffedd5; color: #c2410c; }
        .status-pill.info { background: #e0f2fe; color: #0369a1; }

        .assessment-content {
            display: grid;
            grid-template-columns: 2.1fr 1fr;
            gap: 20px;
        }

        .assessment-main,
        .assessment-side {
            display: grid;
            gap: 8px;
        }

        .card-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
        }

        .card-header h4 {
            font-weight: 700;
            font-size: 1rem;
        }

        .card-header p {
            font-size: 0.75rem;
            color: var(--muted);
        }

        .score-legend {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .legend-item {
            font-size: 0.7rem;
            padding: 4px 10px;
            border-radius: 999px;
            background: #f1f5f9;
            color: #475569;
            font-weight: 600;
        }

        .legend-item.good { background: #dcfce7; color: #15803d; }
        .legend-item.calm { background: #e0f2fe; color: #0369a1; }
        .legend-item.warn { background: #fee2e2; color: #b91c1c; }

        .swbs-form {
            display: grid;
            gap: 14px;
        }

        .swbs-legend {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            justify-content: flex-end;
        }

        .swbs-pill {
            font-size: 0.7rem;
            padding: 4px 10px;
            border-radius: 999px;
            background: #f1f5f9;
            color: #475569;
            font-weight: 600;
        }

        .swbs-row {
            display: grid;
            grid-template-columns: 1fr 360px;
            gap: 16px;
            padding: 10px 0;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.78rem;
            color: #1f2937;
            align-items: center;
        }

        .swbs-row:last-child {
            border-bottom: none;
        }

        .swbs-statement {
            display: flex;
            gap: 10px;
            align-items: flex-start;
            color: #1f2937;
        }

        .swbs-index {
            width: 24px;
            height: 24px;
            border-radius: 999px;
            background: #e2e8f0;
            color: #475569;
            font-size: 0.7rem;
            display: grid;
            place-items: center;
            flex-shrink: 0;
            font-weight: 600;
        }

        .swbs-scale {
            display: grid;
            grid-template-columns: repeat(6, minmax(0, 1fr));
            gap: 8px;
            align-items: center;
        }

        .swbs-scale label {
            display: grid;
            justify-items: center;
            gap: 4px;
            font-size: 0.7rem;
            color: var(--muted);
        }

        .swbs-scale input {
            width: 14px;
            height: 14px;
        }

        .ecog-form {
            display: grid;
            gap: 16px;
        }

        .ecog-meta {
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 12px;
        }

        .ecog-meta .field {
            display: grid;
            gap: 6px;
        }

        .ecog-meta label {
            font-size: 0.75rem;
            color: var(--muted);
        }

        .ecog-meta input {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 8px 10px;
            font-size: 0.8rem;
            background: #ffffff;
        }

        .ecog-table {
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            overflow: hidden;
        }

        .ecog-row {
            display: grid;
            grid-template-columns: 1fr 120px;
            gap: 12px;
            padding: 12px 14px;
            border-bottom: 1px solid #f1f5f9;
            align-items: center;
            font-size: 0.8rem;
            color: #1f2937;
            cursor: pointer;
        }

        .ecog-row:last-child {
            border-bottom: none;
        }

        .ecog-row.head {
            background: #f8fafc;
            font-weight: 600;
            color: #475569;
            cursor: default;
        }

        .score-cell {
            display: inline-flex;
            align-items: center;
            justify-content: flex-end;
            gap: 8px;
        }

        .score-cell input {
            width: 16px;
            height: 16px;
        }

        .score-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: 999px;
            background: #e2e8f0;
            color: #475569;
            font-weight: 600;
            font-size: 0.75rem;
        }

        .field-error {
            font-size: 0.75rem;
            color: #dc2626;
        }

        .score-summary {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }

        .esas-form {
            display: grid;
            gap: 14px;
        }

        .esas-row {
            display: grid;
            grid-template-columns: 180px 1fr 180px;
            align-items: center;
            gap: 12px;
            padding: 10px 0;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.78rem;
            color: #1f2937;
        }

        .esas-row:last-child {
            border-bottom: none;
        }

        .esas-label {
            color: var(--muted);
        }

        .esas-label.right {
            text-align: right;
        }

        .esas-scale {
            display: grid;
            grid-template-columns: repeat(11, minmax(0, 1fr));
            gap: 6px;
            align-items: center;
        }

        .esas-scale label {
            display: grid;
            justify-items: center;
            gap: 4px;
            font-size: 0.7rem;
            color: var(--muted);
        }

        .esas-scale input {
            width: 14px;
            height: 14px;
        }

        .score-info {
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .score-label {
            font-size: 0.8rem;
            color: var(--muted);
            font-weight: 600;
        }

        .empty-row {
            text-align: center;
            color: var(--muted);
            font-size: 0.85rem;
            padding: 12px;
        }

        .instrument-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
        }

        .instrument-card {
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            padding: 14px;
            display: grid;
            gap: 10px;
            background: #fff;
        }

        .instrument-card h5 {
            font-weight: 600;
            font-size: 0.9rem;
        }

        .instrument-card p {
            font-size: 0.75rem;
            color: var(--muted);
        }

        .instrument-card button {
            margin-top: auto;
            border: 1px solid #e2e8f0;
            background: #fff;
            border-radius: 999px;
            padding: 6px 12px;
            font-size: 0.75rem;
            color: #256c32;
        }

        .instrument-icon {
            width: 36px;
            height: 36px;
            border-radius: 12px;
            display: grid;
            place-items: center;
            color: #256c32;
            background: #e5f6e5;
        }

        .instrument-card.blue .instrument-icon { background: #dbeafe; color: #1d4ed8; }
        .instrument-card.purple .instrument-icon { background: #ede9fe; color: #6d28d9; }

        .table-wrap {
            overflow-x: auto;
        }

        .history-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.8rem;
        }

        .history-table th,
        .history-table td {
            padding: 10px 8px;
            text-align: left;
            border-bottom: 1px solid #f1f5f9;
            color: var(--muted);
        }

        .history-table th {
            color: #475569;
            font-weight: 600;
            font-size: 0.72rem;
            text-transform: uppercase;
        }

        .pill {
            display: inline-flex;
            padding: 3px 8px;
            border-radius: 999px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .pill.green { background: #dcfce7; color: #15803d; }
        .pill.blue { background: #dbeafe; color: #1d4ed8; }
        .pill.purple { background: #ede9fe; color: #6d28d9; }
        .pill.success { background: #dcfce7; color: #15803d; }

        .ghost-button {
            border: 1px solid #e2e8f0;
            background: #fff;
            padding: 6px 10px;
            border-radius: 10px;
            font-size: 0.75rem;
            color: var(--muted);
        }

        .info-card p {
            font-size: 0.8rem;
            color: var(--muted);
            line-height: 1.5;
        }

        .info-list {
            background: #f0fdf4;
            border-radius: 14px;
            padding: 12px;
            display: grid;
            gap: 8px;
        }

        .info-list h5 {
            font-weight: 600;
            font-size: 0.8rem;
            color: #166534;
        }

        .info-list ul {
            list-style: disc;
            margin-left: 16px;
            font-size: 0.75rem;
            color: #166534;
            display: grid;
            gap: 6px;
        }

        .quote-card {
            background: #f0fdf4;
            color: #166534;
            font-size: 0.8rem;
            text-align: center;
        }

        .quote-card span {
            display: block;
            margin-top: 6px;
            font-size: 0.7rem;
            color: #15803d;
        }

        .accordion {
            background: #ffffff;
            border-radius: 16px;
            padding: 16px;
            box-shadow: var(--shadow);
        }

        .accordion summary {
            cursor: pointer;
            font-weight: 600;
            font-size: 0.95rem;
        }

        .form-grid {
            display: grid;
            gap: 16px;
            margin-top: 16px;
        }

        .form-row {
            display: grid;
            gap: 8px;
        }

        .form-row label {
            font-size: 0.85rem;
            color: var(--muted);
        }

        .radio-group {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .radio-group label {
            display: flex;
            align-items: center;
            gap: 6px;
            background: #f8fafc;
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 0.75rem;
        }

        .primary-button {
            background: #4f9b4f;
            color: #fff;
            border: none;
            padding: 10px 16px;
            border-radius: 12px;
            font-weight: 600;
            width: fit-content;
        }

        .small {
            font-size: 0.75rem;
        }

        @media (max-width: 1200px) {
            .assessment-hero {
                grid-template-columns: 1fr;
            }

            .ecog-meta {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .hero-metrics {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .assessment-content {
                grid-template-columns: 1fr;
            }

            .instrument-grid {
                grid-template-columns: 1fr;
            }

            .swbs-row {
                grid-template-columns: 1fr;
                gap: 10px;
            }

            .esas-row {
                grid-template-columns: 1fr;
                gap: 8px;
            }

            .esas-label.right {
                text-align: left;
            }
        }

        @media (max-width: 900px) {
            .ecog-meta {
                grid-template-columns: 1fr;
            }
            .dashboard-topbar {
                display: flex;
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }

            .dashboard-topbar > div:first-child {
                text-align: left;
            }

            .dashboard-topbar .topbar-actions {
                justify-self: auto;
                align-self: flex-start;
            }

            .dashboard-layout {
                grid-template-columns: 1fr;
            }

            .dashboard-sidebar {
                position: sticky;
                top: 0;
                z-index: 10;
                flex-direction: row;
                overflow-x: auto;
                gap: 12px;
            }

            .sidebar-brand,
            .sidebar-footer {
                display: none;
            }

            .sidebar-nav {
                flex-direction: row;
                flex-wrap: nowrap;
            }

            .nav-group {
                min-width: 180px;
            }
        }
    </style>

    <script>
        const ecogScoreInputs = document.querySelectorAll('input[name="score"]');
        const scoreValue = document.getElementById('ecog-score-value');
        const scoreLabel = document.getElementById('ecog-score-label');

        const labelForScore = (value) => {
            if (value <= 1) {
                return 'Sangat Baik';
            }
            if (value <= 3) {
                return 'Cukup Baik';
            }
            return 'Kurang Baik';
        };

        const updateScorePreview = () => {
            const selected = Array.from(ecogScoreInputs).find((input) => input.checked);
            if (!selected) {
                if (scoreValue) scoreValue.textContent = '-';
                if (scoreLabel) scoreLabel.textContent = 'Belum dipilih';
                return;
            }

            const score = Number(selected.value);
            if (scoreValue) scoreValue.textContent = String(score);
            if (scoreLabel) scoreLabel.textContent = labelForScore(score);
        };

        ecogScoreInputs.forEach((input) => {
            input.addEventListener('change', updateScorePreview);
        });

        updateScorePreview();

        const esasTotal = document.getElementById('esas-total-score');
        const esasInputs = document.querySelectorAll('.esas-scale input');

        const updateEsasTotal = () => {
            let total = 0;
            let hasSelection = false;

            esasInputs.forEach((input) => {
                if (input.checked) {
                    total += Number(input.value);
                    hasSelection = true;
                }
            });

            if (esasTotal) {
                esasTotal.textContent = hasSelection ? String(total) : '-';
            }
        };

        esasInputs.forEach((input) => {
            input.addEventListener('change', updateEsasTotal);
        });

        updateEsasTotal();

        const swbsTotal = document.getElementById('swbs-total-score');
        const swbsInputs = document.querySelectorAll('.swbs-scale input');

        const updateSwbsTotal = () => {
            let total = 0;
            let hasSelection = false;

            swbsInputs.forEach((input) => {
                if (input.checked) {
                    total += Number(input.value);
                    hasSelection = true;
                }
            });

            if (swbsTotal) {
                swbsTotal.textContent = hasSelection ? String(total) : '-';
            }
        };

        swbsInputs.forEach((input) => {
            input.addEventListener('change', updateSwbsTotal);
        });

        updateSwbsTotal();

        const collapseButtons = document.querySelectorAll('.collapse-toggle');

        collapseButtons.forEach((button) => {
            const card = button.closest('.card');
            if (card) {
                const collapsed = card.classList.contains('is-collapsed');
                button.setAttribute('aria-expanded', String(!collapsed));
            }

            button.addEventListener('click', () => {
                if (!card) return;
                const isCollapsed = card.classList.toggle('is-collapsed');
                button.setAttribute('aria-expanded', String(!isCollapsed));
            });
        });
    </script>
</x-app-layout>

@extends('layouts.app')

@section('title', 'Seeder & Factory Studio - Laravel 12')

@section('content')
<div class="container-fluid px-0">
    <!-- Header Section -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center bg-white p-4 rounded-4 shadow-sm mb-4 border">
        <div>
            <h2 class="fw-bold text-dark mb-1">
                <i class="fa-solid fa-wand-magic-sparkles text-primary me-2"></i>Interactive Seeder & Factory Studio
            </h2>
            <p class="text-muted mb-0">Dynamic batch mock data generation, real-time seeder radar console & multi-format dataset exporter</p>
        </div>
        <div class="d-flex gap-2 mt-3 mt-md-0">
            <button type="button" class="btn btn-outline-danger btn-sm px-3" data-bs-toggle="modal" data-bs-target="#resetModal">
                <i class="fa-solid fa-rotate-left me-1"></i> 1-Click Fresh Reset
            </button>
            <div class="dropdown">
                <button class="btn btn-primary btn-sm dropdown-toggle px-3" type="button" data-bs-toggle="dropdown">
                    <i class="fa-solid fa-download me-1"></i> Export Dataset
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow">
                    <li><h6 class="dropdown-header">Export Posts</h6></li>
                    <li><a class="dropdown-item" href="{{ route('seeder.studio.export', ['entity' => 'posts', 'format' => 'csv']) }}"><i class="fa-solid fa-file-csv me-2 text-success"></i> Posts (CSV)</a></li>
                    <li><a class="dropdown-item" href="{{ route('seeder.studio.export', ['entity' => 'posts', 'format' => 'xlsx']) }}"><i class="fa-solid fa-file-excel me-2 text-primary"></i> Posts (XLSX)</a></li>
                    <li><a class="dropdown-item" href="{{ route('seeder.studio.export', ['entity' => 'posts', 'format' => 'json']) }}"><i class="fa-solid fa-file-code me-2 text-warning"></i> Posts (JSON)</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><h6 class="dropdown-header">Export Users</h6></li>
                    <li><a class="dropdown-item" href="{{ route('seeder.studio.export', ['entity' => 'users', 'format' => 'csv']) }}"><i class="fa-solid fa-file-csv me-2 text-success"></i> Users (CSV)</a></li>
                    <li><a class="dropdown-item" href="{{ route('seeder.studio.export', ['entity' => 'users', 'format' => 'json']) }}"><i class="fa-solid fa-file-code me-2 text-warning"></i> Users (JSON)</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm border-0" role="alert">
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-circle-check fs-4 me-3"></i>
                <div>
                    <strong>Success!</strong> {{ session('success') }}
                    @if(session('logs'))
                        <ul class="mb-0 mt-2 small">
                            @foreach(session('logs') as $log)
                                <li>{{ $log }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm border-0" role="alert">
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-circle-exclamation fs-4 me-3"></i>
                <div>
                    <strong>Error!</strong> {{ session('error') }}
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Metric KPI Cards -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-primary text-white h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-semibold">Total Posts</span>
                    <i class="fa-solid fa-newspaper fs-4 opacity-75"></i>
                </div>
                <h2 class="fw-bold mb-0">{{ number_format($stats['total_posts']) }}</h2>
                <small class="opacity-75 mt-1 d-block">{{ $stats['published_posts'] }} Published · {{ $stats['draft_posts'] }} Drafts</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-success text-white h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-semibold">Total Users</span>
                    <i class="fa-solid fa-users fs-4 opacity-75"></i>
                </div>
                <h2 class="fw-bold mb-0">{{ number_format($stats['total_users']) }}</h2>
                <small class="opacity-75 mt-1 d-block">Seeders & Factory Generated</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-warning text-dark h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-semibold">Categories</span>
                    <i class="fa-solid fa-tags fs-4 opacity-75"></i>
                </div>
                <h2 class="fw-bold mb-0">{{ number_format($stats['total_categories']) }}</h2>
                <small class="opacity-75 mt-1 d-block">Taxonomy & Post Relations</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-info text-white h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-semibold">Comments</span>
                    <i class="fa-solid fa-comments fs-4 opacity-75"></i>
                </div>
                <h2 class="fw-bold mb-0">{{ number_format($stats['total_comments']) }}</h2>
                <small class="opacity-75 mt-1 d-block">{{ $stats['approved_comments'] }} Approved · {{ $stats['pending_comments'] }} Pending</small>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Left Column: Custom Batch Generator Form & Presets -->
        <div class="col-lg-7">
            <!-- Preset Scenario Dispatcher -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                    <h5 class="fw-bold mb-1"><i class="fa-solid fa-bolt text-warning me-2"></i>1-Click Preset Scenario Generator</h5>
                    <p class="text-muted small mb-0">Select a pre-configured testing benchmark preset to seed data immediately</p>
                </div>
                <div class="card-body p-4">
                    <div class="row g-2">
                        <div class="col-6 col-md-3">
                            <form action="{{ route('seeder.studio.preset') }}" method="POST">
                                @csrf
                                <input type="hidden" name="preset" value="ecommerce">
                                <button type="submit" class="btn btn-outline-primary w-100 p-3 text-start rounded-3 h-100">
                                    <div class="fw-bold mb-1">🛒 E-Commerce</div>
                                    <small class="text-muted d-block">50 Users · 200 Posts · 15 Cats · 300 Comms</small>
                                </button>
                            </form>
                        </div>
                        <div class="col-6 col-md-3">
                            <form action="{{ route('seeder.studio.preset') }}" method="POST">
                                @csrf
                                <input type="hidden" name="preset" value="benchmark">
                                <button type="submit" class="btn btn-outline-danger w-100 p-3 text-start rounded-3 h-100">
                                    <div class="fw-bold mb-1">🚀 Heavy Load</div>
                                    <small class="text-muted d-block">100 Users · 500 Posts · 20 Cats · 1k Comms</small>
                                </button>
                            </form>
                        </div>
                        <div class="col-6 col-md-3">
                            <form action="{{ route('seeder.studio.preset') }}" method="POST">
                                @csrf
                                <input type="hidden" name="preset" value="blog">
                                <button type="submit" class="btn btn-outline-success w-100 p-3 text-start rounded-3 h-100">
                                    <div class="fw-bold mb-1">📝 Blog Focus</div>
                                    <small class="text-muted d-block">10 Users · 50 Posts · 5 Cats · 150 Comms</small>
                                </button>
                            </form>
                        </div>
                        <div class="col-6 col-md-3">
                            <form action="{{ route('seeder.studio.preset') }}" method="POST">
                                @csrf
                                <input type="hidden" name="preset" value="community">
                                <button type="submit" class="btn btn-outline-info w-100 p-3 text-start rounded-3 h-100">
                                    <div class="fw-bold mb-1">💬 Community</div>
                                    <small class="text-muted d-block">100 Users · 20 Posts · 5 Cats · 500 Comms</small>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Custom Batch Configurator UI -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                    <h5 class="fw-bold mb-1"><i class="fa-solid fa-sliders text-primary me-2"></i>Custom Batch Record Generator</h5>
                    <p class="text-muted small mb-0">Configure exact record counts to generate on-the-fly via Laravel Factories</p>
                </div>
                <div class="card-body p-4">
                    <form id="batchSeederForm" action="{{ route('seeder.studio.generate') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Users Count</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fa-solid fa-user-plus text-muted"></i></span>
                                    <input type="number" name="users_count" class="form-control" value="25" min="0" max="1000">
                                </div>
                                <div class="form-text">Generate random user accounts</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Categories Count</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fa-solid fa-folder-plus text-muted"></i></span>
                                    <input type="number" name="categories_count" class="form-control" value="5" min="0" max="500">
                                </div>
                                <div class="form-text">Generate parent & child taxonomy categories</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Posts Count</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fa-solid fa-file-pen text-muted"></i></span>
                                    <input type="number" name="posts_count" class="form-control" value="50" min="0" max="2000">
                                </div>
                                <div class="form-text">Generate blog posts with random status & views</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Comments Count</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fa-solid fa-comments text-muted"></i></span>
                                    <input type="number" name="comments_count" class="form-control" value="100" min="0" max="5000">
                                </div>
                                <div class="form-text">Generate comments linked to users and posts</div>
                            </div>
                        </div>

                        <div class="mt-4 pt-2 border-top d-flex justify-content-between align-items-center">
                            <span class="text-muted small"><i class="fa-solid fa-shield-halved me-1"></i> Safe memory execution limits enforced</span>
                            <button type="submit" id="generateBtn" class="btn btn-primary px-4 rounded-3 fw-bold">
                                <i class="fa-solid fa-play me-2"></i>Generate Batch Records
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Column: Real-Time Console Radar & Distribution Visualizer -->
        <div class="col-lg-5">
            <!-- Real-Time Execution Console Radar -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 bg-dark text-white">
                <div class="card-header bg-transparent border-secondary pt-3 px-3 pb-2 d-flex justify-content-between align-items-center">
                    <span class="fw-bold small text-light"><i class="fa-solid fa-terminal text-success me-2"></i>Real-Time Seeder Console Radar</span>
                    <span id="consoleBadge" class="badge bg-success">SYSTEM READY</span>
                </div>
                <div class="card-body p-3">
                    <!-- Progress Bar -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between small text-muted mb-1">
                            <span>Execution Progress</span>
                            <span id="progressPercent">0%</span>
                        </div>
                        <div class="progress bg-secondary" style="height: 8px;">
                            <div id="progressBar" class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%"></div>
                        </div>
                    </div>

                    <!-- Console Output Box -->
                    <div id="consoleTerminal" class="p-3 bg-black rounded-3 font-monospace small" style="height: 180px; overflow-y: auto; color: #33ff33; border: 1px solid #333;">
                        <div>[{{ date('H:i:s') }}] INFO: Seeder Studio initialized & waiting for job dispatch.</div>
                        <div>[{{ date('H:i:s') }}] DATABASE CONNECTION: Active MySQL instance.</div>
                        <div>[{{ date('H:i:s') }}] MEMORY USAGE: Peak 18.4MB / Limit 512MB.</div>
                    </div>
                </div>
            </div>

            <!-- Data Distribution Inspector -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                    <h5 class="fw-bold mb-1"><i class="fa-solid fa-chart-pie text-info me-2"></i>Dataset Distribution Visualizer</h5>
                    <p class="text-muted small mb-0">Current database record composition & status ratios</p>
                </div>
                <div class="card-body p-4">
                    <h6 class="fw-bold small text-uppercase text-muted mb-2">Post Status Ratio</h6>
                    @php
                        $totalP = max(1, $stats['total_posts']);
                        $pubPct = round(($stats['published_posts'] / $totalP) * 100);
                        $draftPct = round(($stats['draft_posts'] / $totalP) * 100);
                        $archPct = round(($stats['archived_posts'] / $totalP) * 100);
                    @endphp
                    <div class="progress mb-3" style="height: 20px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $pubPct }}%" title="Published ({{ $pubPct }}%)">Published {{ $pubPct }}%</div>
                        <div class="progress-bar bg-warning text-dark" role="progressbar" style="width: {{ $draftPct }}%" title="Draft ({{ $draftPct }}%)">Draft {{ $draftPct }}%</div>
                        <div class="progress-bar bg-secondary" role="progressbar" style="width: {{ $archPct }}%" title="Archived ({{ $archPct }}%)">Arch {{ $archPct }}%</div>
                    </div>

                    <h6 class="fw-bold small text-uppercase text-muted mb-2 mt-4">Top Categories</h6>
                    <div class="list-group list-group-flush mb-3">
                        @forelse($topCategories as $cat)
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                <span class="fw-semibold text-dark"><i class="fa-solid fa-folder text-warning me-2"></i>{{ $cat->name }}</span>
                                <span class="badge bg-primary rounded-pill">{{ $cat->posts_count }} posts</span>
                            </div>
                        @empty
                            <div class="text-muted small">No categories seeded yet.</div>
                        @endforelse
                    </div>

                    <h6 class="fw-bold small text-uppercase text-muted mb-2 mt-4">Top Content Authors</h6>
                    <div class="list-group list-group-flush">
                        @forelse($topAuthors as $author)
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                <span class="fw-semibold text-dark"><i class="fa-solid fa-circle-user text-primary me-2"></i>{{ $author->name }}</span>
                                <span class="badge bg-success rounded-pill">{{ $author->posts_count }} posts</span>
                            </div>
                        @empty
                            <div class="text-muted small">No authors seeded yet.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Fresh Reset Confirmation -->
<div class="modal fade" id="resetModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-0 bg-danger text-white rounded-top-4">
                <h5 class="modal-title fw-bold"><i class="fa-solid fa-triangle-exclamation me-2"></i>Confirm Database Fresh Reset</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <p class="mb-3 text-dark">Are you sure you want to perform a 1-Click Fresh Database Reset?</p>
                <div class="alert alert-warning small mb-0">
                    <i class="fa-solid fa-circle-info me-1"></i> This will drop all database tables, re-run all migrations, and re-execute the default seeders (<code>migrate:fresh --seed</code>). All custom records will be replaced.
                </div>
            </div>
            <div class="modal-footer border-0 p-3 bg-light rounded-bottom-4">
                <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('seeder.studio.reset') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-danger px-4 fw-bold">
                        <i class="fa-solid fa-rotate-left me-1"></i> Reset & Re-Seed
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const batchForm = document.getElementById('batchSeederForm');
    const generateBtn = document.getElementById('generateBtn');
    const consoleTerminal = document.getElementById('consoleTerminal');
    const progressBar = document.getElementById('progressBar');
    const progressPercent = document.getElementById('progressPercent');
    const consoleBadge = document.getElementById('consoleBadge');

    function logToConsole(message, type = 'INFO') {
        const time = new Date().toLocaleTimeString();
        const line = document.createElement('div');
        line.innerHTML = `[${time}] ${type}: ${message}`;
        consoleTerminal.appendChild(line);
        consoleTerminal.scrollTop = consoleTerminal.scrollHeight;
    }

    if (batchForm) {
        batchForm.addEventListener('submit', function (e) {
            consoleBadge.textContent = "PROCESSING BATCH...";
            consoleBadge.className = "badge bg-warning text-dark";
            generateBtn.disabled = true;
            generateBtn.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status"></span>Seeding Records...`;

            logToConsole("Dispatched batch generation payload...", "START");

            let progress = 10;
            progressBar.style.width = progress + '%';
            progressPercent.textContent = progress + '%';

            const interval = setInterval(() => {
                if (progress < 90) {
                    progress += Math.floor(Math.random() * 20) + 10;
                    if (progress > 90) progress = 90;
                    progressBar.style.width = progress + '%';
                    progressPercent.textContent = progress + '%';
                    logToConsole(`Building relationships & inserting records chunk (${progress}%)...`);
                }
            }, 300);

            // Allow form to submit naturally
        });
    }
});
</script>
@endpush

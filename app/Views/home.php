<nav class="navbar navbar-expand-lg shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="<?= url('/') ?>">
            <img src="<?= asset('assets/images/logo.png') ?>" alt="Logo">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="#home">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
                <li class="nav-item"><a class="nav-link" href="#roles">Roles</a></li>
                <li class="nav-item"><a class="nav-link" href="#features">Features</a></li>
                <li class="nav-item"><a class="btn btn-primary ms-2" href="<?= url('/login') ?>">Login</a></li>
            </ul>
        </div>
    </div>
</nav>

<section class="hero text-center" id="home">
    <div class="container py-5">
        <h1 class="display-3 fw-bold"><img src="<?= asset('assets/images/logo1.png') ?>" style="height: 100px;" alt="UMUnity"></h1>
        <h1 class="display-3 fw-bold">UMUnity School Organization Management System</h1>
        <p class="lead">A cleaner foundation for managing school clubs, members, approvals, and events.</p>
        <div class="d-flex justify-content-center gap-3 mt-4">
            <a href="<?= url('/login') ?>" class="btn btn-lg btn-primary px-4">Get Started</a>
            <a href="<?= url('/register') ?>" class="btn btn-lg btn-outline-light px-4">Create Account</a>
        </div>
    </div>
</section>

<section class="container text-center py-5 impact-section">
    <h2 class="fw-bold section-title">Platform Snapshot</h2>
    <p class="section-subtitle">Live numbers pulled from the current database.</p>
    <div class="row g-4">
        <div class="col-md-3">
            <div class="impact-box">
                <h3 class="fw-bold text-primary"><?= (int)$stats['active_organizations'] ?></h3>
                <p>Active Organizations</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="impact-box">
                <h3 class="fw-bold text-success"><?= (int)$stats['registered_students'] ?></h3>
                <p>Registered Students</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="impact-box">
                <h3 class="fw-bold text-danger"><?= (int)$stats['successful_events'] ?></h3>
                <p>Tracked Events</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="impact-box">
                <h3 class="fw-bold text-warning"><?= (int)$stats['event_participants'] ?></h3>
                <p>Organization Memberships</p>
            </div>
        </div>
    </div>
</section>

<section class="container py-5" id="about">
    <div class="row align-items-center g-5">
        <div class="col-lg-6">
            <h2 class="fw-bold mb-4">A cleaner base for the system</h2>
            <p class="lead mb-4">
                This project now has a front controller, route layer, controllers, layouts, and reusable views,
                so you can keep improving it without adding more page-level duplication.
            </p>
            <div class="d-flex flex-column gap-3">
                <div class="d-flex align-items-center">
                    <div class="icon-circle me-3"><i class="fas fa-layer-group"></i></div>
                    <span>Single application entrypoint and router</span>
                </div>
                <div class="d-flex align-items-center">
                    <div class="icon-circle me-3"><i class="fas fa-user-shield"></i></div>
                    <span>Centralized auth handling and session state</span>
                </div>
                <div class="d-flex align-items-center">
                    <div class="icon-circle me-3"><i class="fas fa-palette"></i></div>
                    <span>Shared guest and dashboard layouts for consistent UI</span>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <img src="<?= asset('assets/images/aboutus1.png') ?>" class="img-fluid rounded-4 shadow" alt="About UMUnity">
        </div>
    </div>
</section>

<section class="container-fluid role-section text-white py-5" id="roles">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Role-Based Workspaces</h2>
            <p class="text-light">Each role lands on the correct dashboard through the new route layer.</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 shadow-sm card-hover">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-user-shield fs-1 text-warning mb-3"></i>
                        <h3 class="card-title h5">Administrator</h3>
                        <p class="card-text">Manage users, organizations, events, and reports from one shell.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm card-hover">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-user-tie fs-1 text-warning mb-3"></i>
                        <h3 class="card-title h5">Organization Leader</h3>
                        <p class="card-text">Track members, run events, and maintain organization details.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm card-hover">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-user-graduate fs-1 text-warning mb-3"></i>
                        <h3 class="card-title h5">Student</h3>
                        <p class="card-text">See memberships, upcoming events, and recommended organizations.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="bg-white py-5" id="features">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Framework Direction</h2>
            <p class="text-muted">This is the first structural step toward a maintainable PHP application.</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card border-0 h-100 shadow-lg feature-card">
                    <div class="card-body text-center p-4">
                        <div class="icon-container bg-primary bg-opacity-10">
                            <i class="fas fa-route fs-3 text-primary"></i>
                        </div>
                        <h3 class="card-title h5 mb-3">Routing Layer</h3>
                        <p class="card-text">Core pages no longer need to be hard-coded direct scripts.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 h-100 shadow-lg feature-card">
                    <div class="card-body text-center p-4">
                        <div class="icon-container bg-success bg-opacity-10">
                            <i class="fas fa-window-restore fs-3 text-success"></i>
                        </div>
                        <h3 class="card-title h5 mb-3">Reusable Layouts</h3>
                        <p class="card-text">Guest pages and dashboard pages now share proper layouts.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 h-100 shadow-lg feature-card">
                    <div class="card-body text-center p-4">
                        <div class="icon-container bg-warning bg-opacity-10">
                            <i class="fas fa-arrows-rotate fs-3 text-warning"></i>
                        </div>
                        <h3 class="card-title h5 mb-3">Incremental Migration</h3>
                        <p class="card-text">Legacy management pages still work while you move them route by route.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

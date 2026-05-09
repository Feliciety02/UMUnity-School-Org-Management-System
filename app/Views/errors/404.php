<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Not Found') ?></title>
    <link rel="stylesheet" href="<?= asset('assets/css/login.css') ?>">
</head>
<body class="body">
    <div class="login-wrapper" style="grid-template-columns:1fr; min-height:auto;">
        <div class="login-right" style="max-width:none;">
            <div class="login-header">
                <h2>Page Not Found</h2>
                <p>The route you requested does not exist in the new application layer.</p>
            </div>
            <a href="<?= url('/') ?>" class="btn btn-primary text-center text-decoration-none">Go Home</a>
        </div>
    </div>
</body>
</html>

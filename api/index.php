<?php

// 1. Arahkan direktori cache ke folder /tmp Vercel
$_ENV['APP_SERVICES_CACHE'] = '/tmp/services.php';
$_ENV['APP_PACKAGES_CACHE'] = '/tmp/packages.php';
$_ENV['APP_CONFIG_CACHE']   = '/tmp/config.php';
$_ENV['APP_ROUTES_CACHE']   = '/tmp/routes.php';
$_ENV['APP_EVENTS_CACHE']   = '/tmp/events.php';

// 2. Buat folder temporary jika belum ada
$storageDirs = [
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/logs',
    '/tmp/bootstrap/cache',
];

foreach ($storageDirs as $dir) {
    if (!file_exists($dir)) {
        mkdir($dir, 0755, true);
    }
}

// 3. Jalankan aplikasi Laravel dasar terlebih dahulu
require __DIR__ . '/../public/index.php';

// 4. Paksa HTTPS & atur APP_URL secara dinamis setelah Laravel siap
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
    \Illuminate\Support\Facades\URL::forceScheme('https');
}

$host = $_SERVER['HTTP_X_FORWARDED_HOST'] ?? $_SERVER['HTTP_HOST'] ?? null;
if ($host) {
    $_ENV['APP_URL'] = 'https://' . $host;
    putenv('APP_URL=https://' . $host);
}
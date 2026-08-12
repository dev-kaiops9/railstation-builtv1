<?php

use Illuminate\Support\Facades\URL;

// Deteksi domain/host saat ini secara otomatis dari header Vercel
$scheme = (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ? 'https' : 'http';
$host = $_SERVER['HTTP_X_FORWARDED_HOST'] ?? $_SERVER['HTTP_HOST'] ?? null;

if ($host) {
    // Paksa scheme HTTPS
    URL::forceScheme('https');
    // Set APP_URL secara dinamis sesuai domain saat ini
    $_ENV['APP_URL'] = 'https://' . $host;
    putenv('APP_URL=https://' . $host);
}

// Arahkan direktori bootstrap cache & storage ke folder /tmp
$_ENV['APP_SERVICES_CACHE'] = '/tmp/services.php';
$_ENV['APP_PACKAGES_CACHE'] = '/tmp/packages.php';
$_ENV['APP_CONFIG_CACHE']   = '/tmp/config.php';
$_ENV['APP_ROUTES_CACHE']   = '/tmp/routes.php';
$_ENV['APP_EVENTS_CACHE']   = '/tmp/events.php';

// Buat folder temporary jika belum ada
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

// Jalankan aplikasi Laravel
require __DIR__ . '/../public/index.php';